<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\RetrievePaymentsRequest;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentsController extends Controller
{
    public function getStudent(Request $request, string $bank_id, int $university_id)
    {
        $student = Student::with(['fees', 'installments', 'reductions'])->where('university_id', $university_id)->first();

        if (!$student) {
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }

        if ($student->is_active == false) {
            return $this->sendError(['suspended student'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $studentData = [
            'name' => $student['name'],
            'department' => $student['department'],
            'bill' => static::getStudentBillInfo($student)
        ];

        return $this->sendData($studentData);
    }

    public function pay(Request $request, string $bank_id, int $university_id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|in:SDG,USD',
            'reference_id' => 'required',
            'follow_up_number' => 'required',
        ]);

        $student = Student::with(['fees', 'installments', 'reductions'])->where('university_id', $university_id)->first();

        if (!$student) {
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }

        if ($student->is_active == false) {
            return $this->sendError(['suspended student'], Response::HTTP_NOT_ACCEPTABLE);
        }

        $bill = static::getStudentBillInfo($student);

        $request->validate([
            'amount' => 'numeric|min:' . $bill['min'] . '|max:' . $bill['max'],
            'currency' => 'required|in:' . $bill['currency'],
        ]);

        $payment = Payment::create([
            'student_id' => $student->id,
            'name' => $student->name,
            'university_id' => $student->university_id,
            'level' =>  $student->level,
            'department' =>  $student->department,
            'batch' =>  $student->batch,
            'fees_data' =>  $student->fees,
            'installments_data' =>  $student->installments,
            'reductions_data' =>  $student->reductions,
            'payed_amount' =>  doubleval($request->amount),
            'currency' => $request->currency,
            'reference_id'  =>  $request->reference_id,
            'follow_up_number' => $request->follow_up_number,
        ]);

        return $this->sendData([
            'date' => $payment->created_at,
            'amount' => $payment->payed_amount,
            'currency' => $payment->currency
        ]);
    }

    private static function getStudentBillInfo(Student $student)
    {
        $bill = [
            'currency' => $student->fees->currency,
            'min' => 0,
            'max' => 0
        ];
        $feesAmount = doubleval($student->fees->amount);

        $reductionsAmount = doubleval($student->reductions->amount);

        if ($student->reductions->is_percentage) {
            $reductionsAmount = $reductionsAmount * $feesAmount / 100;
        }

        $totalBill = $feesAmount - $reductionsAmount;

        $paymentsHistory = Payment::where('student_id', $student->id)->get();
        $totalPayed = 0;
        foreach ($paymentsHistory as $payment) {
            $totalPayed += doubleval($payment->payed_amount);
        }

        $bill['max'] = $totalBill - $totalPayed;

        $divisions = [];
        foreach ($student->installments->divisions as $division) {
            $divisions[] = doubleval($division * $totalBill / 100);
        }

        $divisionsUpperRanges = [$divisions[0]];

        for ($i = 1; $i < count($divisions); $i++) {
            $divisionsUpperRanges[] += $divisionsUpperRanges[count($divisionsUpperRanges) - 1] + $divisions[$i];
        }

        for ($i = 0; $i < count($divisionsUpperRanges); $i++) {
            if ($totalPayed < $divisionsUpperRanges[$i]) {
                $bill['min'] = $divisionsUpperRanges[$i] - $totalPayed;
                break;
            }
        }

        // just for Al-Najah school the min is not mandatory
        $bill['min'] = 10;
        return $bill;
    }

    public function index(RetrievePaymentsRequest $request)
    {
        $this->logSuccess('Retrieve Payments');
        return $this->sendData(Payment::with('student')->get());
    }
}

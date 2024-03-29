<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\RemoveStudentRequest;
use App\Http\Requests\Students\RetrieveStudentsRequest;
use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StudentsController extends Controller
{


    public function store(StoreStudentRequest $request)
    {
        $studentData = $request->all(
            'name',
            'university_id',
            'level',
            'department',
            'batch',
            'is_active',
            'fees_id',
            'installments_id',
            'reductions_id',
        );
        $student = Student::create($studentData);

        $this->logSuccess('Create Student', $student->toArray());
        return $this->sendData($student);
    }

    public function index(RetrieveStudentsRequest $request)
    {
        //   "university_id",
        //   "name",
        //   "department",
        //   "batch",
        //   "level",
        //   "fees_name",
        //   "fees_amount",
        //   "fees_currency",
        //   "installments_name",
        //   "installments_details",
        //   "reductions_name",
        //   "reductions_amount",
        //   "total_payed_amount",
        //   "remaining_payments_amount",
        //   "payments_count",
        //   "last_payment",
        //   "payed_progress",
        //   "is_active",
        //   "created_at",
        //   "updated_at"
        $this->logSuccess('Retrieve Students');
        return $this->sendData(Student::with(['fees', 'installments', 'reductions'])->get());
    }

    public function show(RetrieveStudentsRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $this->logNotFound('Retrieve Students', $id);
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }

        $this->logSuccess('Retrieve Student', $student->toArray());
        return $this->sendData($student);
    }

    public function getPaymentsHistory(RetrieveStudentsRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $this->logNotFound('Retrieve Students', $id);
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }

        $this->logSuccess('Retrieve Student Payments', $student->toArray());
        return $this->sendData($student->payments);
    }
    public function update(UpdateStudentRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $this->logNotFound('Update Student', $id);
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }
        $student->update($request->all());

        $this->logSuccess('Update Student', $student->toArray());
        return $this->sendData($student);
    }

    public function destroy(RemoveStudentRequest $request, int $id)
    {
        $student = Student::find($id);
        if (!$student) {
            $this->logNotFound('Remove Student', $id);
            return $this->sendError(['student not sound'], Response::HTTP_NOT_FOUND);
        }
        $student->delete();

        $this->logSuccess('Remove Student', $student->toArray());
        return $this->sendData($student);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Installments\RemoveInstallmentsRequest;
use App\Http\Requests\Installments\RetrieveInstallmentRequest;
use App\Http\Requests\Installments\StoreInstallmentRequest;
use App\Http\Requests\Installments\UpdateInstallmentRequest;
use App\Models\Installment;
use Illuminate\Http\Response;

class InstallmentsController extends Controller
{
    public function store(StoreInstallmentRequest $request)
    {
        $installmentData = $request->all('name', 'divisions', 'description');
        $installment = Installment::create($installmentData);

        $this->logSuccess('Create Installment', $installment->toArray());
        return $this->sendData($installment);
    }

    public function index(RetrieveInstallmentRequest $request)
    {
        $this->logSuccess('Retrieve Installments');
        return $this->sendData(Installment::withCount('students')->get());
    }

    public function show(RetrieveInstallmentRequest $request, int $id)
    {
        $installment = Installment::withCount('students')->find($id);
        if (!$installment) {
            $this->logNotFound('Retrieve Installments', $id);
            return $this->sendError('installment not found', Response::HTTP_NOT_FOUND);
        }

        $this->logSuccess('Retrieve Installments', $installment->toArray());
        return $this->sendData($installment);
    }

    public function update(UpdateInstallmentRequest $request, int $id)
    {
        $installment = Installment::withCount('students')->find($id);
        if (!$installment) {
            $this->logNotFound('Retrieve Installments', $id);
            return $this->sendError('installment not found', Response::HTTP_NOT_FOUND);
        }
        $installment->update($request->all());

        $this->logSuccess('Update Installments', $installment->toArray());
        return $this->sendData($installment);
    }

    public function destroy(RemoveInstallmentsRequest $request, int $id)
    {
        $installment = Installment::withCount('students')->find($id);
        if (!$installment) {
            $this->logNotFound('Retrieve Installments', $id);
            return $this->sendError('installment not found', Response::HTTP_NOT_FOUND);
        }
        $installment->delete();

        $this->logSuccess('Remove Installments', $installment->toArray());
        return $this->sendData($installment);
    }
}

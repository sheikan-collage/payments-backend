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
        return $this->sendData($installment);
    }

    public function index(RetrieveInstallmentRequest $request)
    {
        return $this->sendData(Installment::all());
    }

    public function show(RetrieveInstallmentRequest $request, int $id)
    {
        $installment = Installment::find($id);
        if (!$installment) return $this->sendError('installment not found', Response::HTTP_NOT_FOUND);
        return $this->sendData($installment);
    }

    public function update(UpdateInstallmentRequest $request, int $id)
    {
        $installment = Installment::find($id);
        if (!$installment) return $this->sendError('installment not found', Response::HTTP_NOT_FOUND);
        $installment->update($request->all());
        return $this->sendData($installment);
    }

    public function destroy(RemoveInstallmentsRequest $request, int $id)
    {
        $installment = Installment::find($id);
        if (!$installment) return $this->sendError('installment not found', Response::HTTP_NOT_FOUND);
        $installment->delete();
        return $this->sendData($installment);
    }
}

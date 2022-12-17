<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fees\RemoveFeeRequest;
use App\Http\Requests\Fees\RetrieveFeesRequest;
use App\Http\Requests\Fees\StoreFeeRequest;
use App\Http\Requests\Fees\UpdateFeeRequest;
use App\Models\Fee;
use Illuminate\Http\Response;

class FeesController extends Controller
{
    public function store(StoreFeeRequest $request)
    {
        $fee = new Fee();
        $fee->name = $request->input('name');
        $fee->amount = $request->input('amount');
        $fee->currency = $request->input('currency');
        $fee->description = $request->input('description');

        $fee->save();
        $this->logSuccess('Create Fees', $fee->toArray());
        return $this->sendData($fee);
    }

    public function index(RetrieveFeesRequest $request)
    {
        $this->logSuccess('Retrieve Fees');
        return $this->sendData(Fee::withCount('students')->get());
    }

    public function show(RetrieveFeesRequest $request, int $id)
    {
        $fee = Fee::withCount('students')->find($id);
        if (!$fee) {
            $this->logNotFound('Retrieve Fees', $id);
            return $this->sendError(['fee not sound'], Response::HTTP_NOT_FOUND);
        }
        $this->logSuccess('Retrieve Fee', $fee->toArray());
        return $this->sendData($fee);
    }

    public function update(UpdateFeeRequest $request, int $id)
    {
        $fee = Fee::withCount('students')->find($id);
        if (!$fee) {
            $this->logNotFound('Update Fees', $id);
            return $this->sendError(['fee not sound'], Response::HTTP_NOT_FOUND);
        }

        $fee->update($request->all());
        $this->logSuccess('Update Fee', $fee->toArray());
        return $this->sendData($fee);
    }

    public function destroy(RemoveFeeRequest $request, int $id)
    {
        $fee = Fee::withCount('students')->find($id);
        if (!$fee) {
            $this->logNotFound('Remove Fees', $id);
            return $this->sendError(['fee not sound'], Response::HTTP_NOT_FOUND);
        }

        $fee->delete();
        
        $this->logSuccess('Remove Fee', $fee->toArray());
        return $this->sendData($fee);
    }
}

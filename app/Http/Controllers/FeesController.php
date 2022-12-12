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

        return $this->sendData($fee);
    }

    public function index(RetrieveFeesRequest $request)
    {
        return $this->sendData(Fee::all());
    }

    public function show(RetrieveFeesRequest $request, int $id)
    {
        $fee = Fee::find($id);
        if (!$fee) {
            return $this->sendError(['fee not sound'], Response::HTTP_NOT_FOUND);
        }
        return $this->sendData($fee);
    }

    public function update(UpdateFeeRequest $request, int $id)
    {
        $fee = Fee::find($id);
        if (!$fee) {
            return $this->sendError(['fee not sound'], Response::HTTP_NOT_FOUND);
        }

        $fee->update($request->all());
        return $this->sendData($fee);
    }

    public function destroy(RemoveFeeRequest $request, int $id)
    {
        $fee = Fee::find($id);
        if (!$fee) {
            return $this->sendError(['fee not sound'], Response::HTTP_NOT_FOUND);
        }
        
        $fee->delete();
        return $this->sendData($fee);
    }
}

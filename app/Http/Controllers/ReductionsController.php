<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reductions\RemoveReductionRequest;
use App\Http\Requests\Reductions\RetrieveReductionsRequest;
use App\Http\Requests\Reductions\StoreReductionRequest;
use App\Http\Requests\Reductions\UpdateReductionRequest;
use App\Models\Reduction;
use Illuminate\Http\Response;

class ReductionsController extends Controller
{

    public function store(StoreReductionRequest $request)
    {
        $reductionData = $request->all('name', 'amount', 'is_percentage');
        $reduction = Reduction::create($reductionData);

        $this->logSuccess('Create Reduction', $reduction->toArray());
        return $this->sendData($reduction);
    }

    public function index(RetrieveReductionsRequest $request)
    {
        $this->logSuccess('Retrieve Reductions');
        return $this->sendData(Reduction::withCount('students')->get());
    }

    public function show(RetrieveReductionsRequest $request, int $id)
    {
        $reduction = Reduction::withCount('students')->find($id);
        if (!$reduction) {
            $this->logNotFound('Retrieve Reductions', $id);
            return $this->sendError(['reduction not sound'], Response::HTTP_NOT_FOUND);
        }

        $this->logSuccess('Retrieve Reduction', $reduction->toArray());
        return $this->sendData($reduction);
    }

    public function update(UpdateReductionRequest $request, int $id)
    {
        $reduction = Reduction::withCount('students')->find($id);
        if (!$reduction) {
            $this->logNotFound('Update Reductions', $id);
            return $this->sendError(['reduction not sound'], Response::HTTP_NOT_FOUND);
        }
        $reduction->update($request->all());

        $this->logSuccess('Update Reduction', $reduction->toArray());
        return $this->sendData($reduction);
    }

    public function destroy(RemoveReductionRequest $request, int $id)
    {
        $reduction = Reduction::withCount('students')->find($id);
        if (!$reduction) {
            $this->logNotFound('Remove Reductions', $id);
            return $this->sendError(['reduction not sound'], Response::HTTP_NOT_FOUND);
        }
        $reduction->delete();

        $this->logSuccess('Remove Reduction', $reduction->toArray());
        return $this->sendData($reduction);
    }
}

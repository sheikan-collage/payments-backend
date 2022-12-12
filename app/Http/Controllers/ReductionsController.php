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
        return $this->sendData($reduction);
    }

    public function index(RetrieveReductionsRequest $request)
    {
        return $this->sendData(Reduction::all());
    }

    public function show(RetrieveReductionsRequest $request, int $id)
    {
        $reduction = Reduction::find($id);
        if (!$reduction) {
            return $this->sendError(['reduction not sound'], Response::HTTP_NOT_FOUND);
        }
        return $this->sendData($reduction);
    }

    public function update(UpdateReductionRequest $request, int $id)
    {
        $reduction = Reduction::find($id);
        if (!$reduction) {
            return $this->sendError(['reduction not sound'], Response::HTTP_NOT_FOUND);
        }

        $reduction->update($request->all());
        return $this->sendData($reduction);
    }

    public function destroy(RemoveReductionRequest $request, int $id)
    {
        $reduction = Reduction::find($id);
        if (!$reduction) {
            return $this->sendError(['reduction not sound'], Response::HTTP_NOT_FOUND);
        }

        $reduction->delete();
        return $this->sendData($reduction);
    }
}

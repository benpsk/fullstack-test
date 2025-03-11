<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Resources\PropertyResource;
use App\Services\PropertyService;


class PropertyController extends Controller
{
    public function __invoke(FilterRequest $request, PropertyService $service)
    {
        $input = $request->validated();
        return PropertyResource::collection($service->get($input));
    }
}

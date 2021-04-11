<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RootController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getTransformedCollectionData($collection, $transformer)
    {
        return fractal()
            ->parseIncludes($this->parseIncludes())
            ->collection($collection)
            ->transformWith($transformer)
            ->toArray();
    }

    public function getTransformedData($data, $transformer)
    {
        return fractal($data, $transformer)
            ->parseIncludes($this->parseIncludes())
            ->toArray();
    }

    public function getTransformedPaginatedData($paginatedObject, $transformer)
    {
        $collection = $paginatedObject->getCollection();

        return fractal()
            ->parseIncludes($this->parseIncludes())
            ->collection($collection, $transformer)
            ->paginateWith(new \League\Fractal\Pagination\IlluminatePaginatorAdapter($paginatedObject))
            ->toArray();
    }

    private function parseIncludes()
    {
        return array_filter(array_merge(explode(',', request()->get('includes')), explode(',', request()->get('include'))));
    }

    public function filterValBooleanInputs($inputs)
    {
        if(array_has($inputs, 'bulk')){
            $inputs['bulk'] = filter_var(array_get($inputs, 'bulk') , FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
        return $inputs;
    }
}

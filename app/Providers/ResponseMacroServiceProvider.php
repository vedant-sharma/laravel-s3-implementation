<?php

namespace App\Providers;

use Response;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        Response::macro('success', function ($data, $status = 200, $code = null) {
            $code = is_null($code) ? $status : $code;

            return Response::json([
                'success'  => true,
                'code' => $code,
                'data' => $data
            ], $status);
        });


        Response::macro('error', function ($errors, $status = 400, $code = null) {
            $errors = is_array($errors) ? $errors : ['message' => $errors];

            $code = is_null($code) ? $status : $code;

            return Response::json([
                'success'  => false,
                'code' => $code,
                'errors' => $errors
            ], $status);
        });


        Response::macro('withMeta', function($data, $status = 200, $code = null) {
            $code = is_null($code) ? $status : $code;

            return Response::json([
                'success'  => true,
                'code' => $code,
                'data' => Arr::except($data, ['meta']),
                'meta' => Arr::get($data, 'meta'),
            ], $status);
        });


        Response::macro('noContent', function(){
            return Response::make("", 204);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
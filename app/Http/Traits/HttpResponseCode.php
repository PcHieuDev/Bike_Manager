<?php

namespace App\Http\Traits;

trait HttpResponseCode {
    public function ok() {
        return '200';
    }

    public function noOk() {
        return '400';
    }

    public function notFound() {
        return '404';
    }

    public function internalServerError() {
        return '500';

    }



    
}

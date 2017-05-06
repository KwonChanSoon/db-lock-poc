<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function persist(Model $model)
    {
        DB::transaction(function () use ($model) {
            $model->push();
        });
    }

    protected function remove(Model $model)
    {
        DB::transaction(function () use ($model) {
            $model->delete();
        });
    }
}

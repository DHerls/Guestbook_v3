<?php
namespace App\Observers;

class RecordObserver {

    public function updating($model)
    {

    }


    public function creating($model) {
        if (!$model->user()->count()){
            $model->user_id = \Auth::user()->id;
        }
    }


    public function saving($model)
    {

    }


}
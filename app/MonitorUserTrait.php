<?php

namespace App;

use App\Observers\RecordObserver;

trait MonitorUserTrait
{
    public static function bootMonitorUserTrait()
    {
        static::observe(new RecordObserver);
    }
}
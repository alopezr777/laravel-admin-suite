<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('suite:summary', function () {
    $this->info('Laravel Admin Suite is ready.');
})->purpose('Display the application status');


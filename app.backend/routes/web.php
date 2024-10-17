<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return ['Laravel' => app()->version()];
    return "web.php";
});

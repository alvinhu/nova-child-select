<?php

use Illuminate\Support\Facades\Route;

Route::get('/options/{resource}', 'OptionsController@index');

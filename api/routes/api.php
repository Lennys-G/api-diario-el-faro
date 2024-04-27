<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\articlesController;


Route::get('/articles', [articlesController::class, 'getArticles']);

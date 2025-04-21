<?php 

use App\Http\Controllers\BidCalculationController;
use Illuminate\Support\Facades\Route;


Route::post('/calculate-bid', [BidCalculationController::class, 'calculate']);

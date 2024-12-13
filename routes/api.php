<?php

use App\Http\Controllers\feedbackController;
use App\Http\Controllers\FronteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//Submitted Feedback
Route::post('postFeedback', [feedbackController::class, 'AddFeed']);
//Route for Auth
Route::post('register', [UserController::class, 'Register']);
Route::post('login', [UserController::class, 'Login']);
// Route::get('login', [UserController::class, 'showLoginForm'])->name('login'); 




Route::middleware(['auth:api', CheckRole::class . ':admin'])->group(function () {
    Route::get('feedbackGet', [FeedbackController::class,'getFeedback']);
    Route::delete('deletefeedback/{id}', [FeedbackController::class,'deleteFeback']);
    Route::get('feedbacStatisitique', [FeedbackController::class,'getFeedbackStatistics']);
    Route::get('feedbackGet/{id}', [FeedbackController::class,'showFedback']);
    Route::get('ratingDistrubution', [FeedbackController::class,'getRatingDistribution']);
    Route::post('feedback/{id}/respond', [FeedbackController::class,'RespondToFeedback']);

    Route::post('logout', [UserController::class, 'Logout']);
});




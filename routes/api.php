<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('forgotPassword', [UserController::class, 'forgotPassword']);
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('get_user', [UserController::class, 'get_user']);
    Route::post('verifyMail', [UserController::class, 'verifyMail']);
    Route::post('resetPassword', [UserController::class, 'resetPassword']);
    Route::post('createNote', [NoteController::class, 'createNote']);
    Route::post('getNoteById', [NoteController::class, 'getNoteById']);
    Route::get('getAllNotes', [NoteController::class, 'getAllNotes']);
    Route::post('updateNotebyid', [NoteController::class, 'updateNoteById']);
    Route::delete('deleteNoteById', [NoteController::class, 'deleteNoteById']);
    Route::post('addNoteLabel', [NoteController::class, 'addNoteLabel']);
    Route::post('searchNotes', [NoteController::class, 'searchNotes']);

    Route::post('createLabel', [LabelController::class, 'createLabel']);
    Route::get('getLableById', [LabelController::class, 'getLableById']);
    Route::get('getAllLabel', [LabelController::class, 'getAllLabel']);
    Route::post('updateLabelById', [LabelController::class, 'updateLabelById']);
    Route::delete('deleteLabelById', [LabelController::class, 'deleteLabelById']);
    Route::post('pinnote', [NoteController::class, 'pinNoteById']);
    Route::post('unpinnote', [NoteController::class, 'unpinNoteById']);
    Route::post('archivenote', [NoteController::class, 'archiveNoteById']);
    Route::post('unarchivenote', [NoteController::class, 'unarchiveNoteById']);
    Route::post('colournote', [NoteController::class, 'colourNoteById']);
    Route::get('getallpinnednotes', [NoteController::class, 'getAllPinnedNotes']);
    Route::get('getallarchivednotes', [NoteController::class, 'getAllArchivedNotes']);
    Route::post('trashNoteById', [NoteController::class, 'trashNoteById']);
    Route::post('restoreNoteById', [NoteController::class, 'restoreNoteById']);
    Route::get('getAllTrashedNotes', [NoteController::class, 'getAllTrashedNotes']);
});

<?php
Route::group(['middleware' => ['web']], function () {
	Route::prefix('upload-manager')->group(function () {
	    Route::get('/', '\\abdulsametsahin\\UploadManager\\UploadManager@mainPage');
	    Route::get('/get-dir', '\\abdulsametsahin\\UploadManager\\UploadManager@getDir');
	    Route::post('/upload', '\\abdulsametsahin\\UploadManager\\UploadManager@upload');
	    Route::post('/create-folder', '\\abdulsametsahin\\UploadManager\\UploadManager@createFolder');
	    Route::post('/delete', '\\abdulsametsahin\\UploadManager\\UploadManager@delete');
	});
});
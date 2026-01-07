<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', function () {
        return view('contact');
    })->name('form');

    Route::post('/', function (Request $request) {
        $data = $request->only('name', 'email');
        return view('contact', $data);
    })->name('submit');
});
?>
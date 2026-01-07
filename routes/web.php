<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/user/{id}', function ($id) {
    $users = [
        [
            'id' => 1,
            'name' => 'Trần Văn A',
            'gender' => 'Nam',
        ],
        [
            'id' => 2,
            'name' => 'Nguyễn Thị B',
            'gender' => 'Nữ',
        ],
        [
            'id' => 3,
            'name' => 'Lê Văn C',
            'gender' => 'Nam',
        ],
    ];
    return view('user', ['users' => $users, 'id' => (int) $id]);
});

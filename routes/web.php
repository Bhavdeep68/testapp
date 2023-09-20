<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



Route::group(['middleware' => 'userguest'], function() {
    Route::get('/',                                                         'HomeController@index');
    Route::post('/load',                                                    'HomeController@load');

    Route::get('/login',                                                    'LoginController@login');
    Route::post('/login',                                                   'LoginController@submit');
    Route::get('/register',                                                 'LoginController@register');
    Route::Post('/register',                                                'LoginController@submitregister');
    
    Route::get('/enroll/{course}',                                          'HomeController@view');
    Route::post('/enroll/{course}',                                         'HomeController@enroll');
});


Route::group(['middleware' => 'userauth'], function() {
    Route::get('/logout',                                                   'LogoutController@logout');
    
    Route::get('/courses',                                                  'CourseController@index');
    Route::Post('/courses/load',                                            'CourseController@load');
    Route::get('/courses/add',                                              'CourseController@add');
    Route::get('/courses/edit/{course}',                                    'CourseController@edit');
    Route::Post('/courses/store',                                           'CourseController@store');
    Route::Post('/courses/destroy',                                         'CourseController@destroy');

});
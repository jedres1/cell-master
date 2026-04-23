<?php

use Illuminate\Support\Facades\Route;
use App\AssignmentCellphoneEmployee;
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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route number
Route::get('/numbers','NumberController@index')->name('numbers.index');
Route::get('/numbers/create','NumberController@create')->name('numbers.create');
Route::get('/numbers/edit/{number}','NumberController@edit')->name('numbers.edit');
Route::get('/numbers/show/{number}','NumberController@show')->name('numbers.show');
Route::patch('/numbers/update/{number}','NumberController@update')->name('numbers.update');
Route::post('/numbers/store','NumberController@store')->name('numbers.store');
Route::post('/numbers/import','NumberController@import')->name('numbers.import');
//Route search
Route::get('/search/', 'PostsController@search')->name('search');

//Route assignments
Route::get('/assignments', 'AssignmentCellphoneEmployeeController@index');
Route::get('/assignments/create','AssignmentCellphoneEmployeeController@create')->name('assignments.create');

Route::get('/assignments/edit/{assignment}','AssignmentCellphoneEmployeeController@edit')->name('assignments.edit');
Route::patch('/assignments/update/{assignment}','AssignmentCellphoneEmployeeController@update')->name('assignments.update');

Route::post('/assignments','AssignmentCellphoneEmployeeController@store')->name('assignments.store');
Route::get('/assignments/show/{assignment}','AssignmentCellphoneEmployeeController@show')->name('assignments.show');

Route::delete('/assignments/{assignment}','AssignmentCellphoneEmployeeController@destroy')->name('assignments.delete');
//Routes cellphone
Route::get('/cellphones', 'CellphoneController@index');
Route::get('/cellphones/create','CellphoneController@create')->name('cellphones.create');
Route::get('/cellphones/edit/{cellphone}','CellphoneController@edit')->name('cellphones.edit');
Route::get('/cellphones/show/{cellphone}','CellphoneController@show')->name('cellphones.show');
Route::patch('/cellphones/update/{cellphone}','CellphoneController@update')->name('cellphones.update');
Route::post('/cellphones','CellphoneController@store')->name('cellphones.store');
Route::post('/cellphones/import','CellphoneController@import')->name('cellphones.import');
//Route employee
Route::get('/employees', 'EmployeeController@index');
Route::get('/employees/create','EmployeeController@create')->name('employees.create');
Route::get('/employees/edit/{employee}','EmployeeController@edit')->name('employees.edit');
Route::get('/employees/show/{employee}','EmployeeController@show')->name('employees.show');
Route::patch('/employees/update/{employee}','EmployeeController@update')->name('employees.update');
Route::post('/employees','EmployeeController@store')->name('employees.store');
//Route Document
Route::get('/download/{id}','AssignmentCellphoneEmployeeController@download' )->name('download.acuerdo');

Route::get('/test',function(){
    return view('/assignments.test');
});

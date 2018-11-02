<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users/{id}/assignedroles', 'AssignedroleController@index')->name('abilities');
Route::get('/users/search', array('as' => 'users.search', 'uses' => 'UserController@search'));
Route::get('/users/searchmesa', array('as' => 'users.searchmesa', 'uses' => 'UserController@searchmesa'));
Route::get('/users/{id}/create', 'UserController@create')->name('users');
Route::get('/users/{id}/habilitar', 'UserController@habilitar')->name('users');
Route::get('/users', 'UserController@index')->name('users');

Route::get('/roles', 'RoleController@index')->name('roles');
Route::get('/roles/create', 'RoleController@create')->name('roles');
Route::get( '/roles/search', array('as' => 'roles.search', 'uses' => 'RoleController@search'));
Route::get('/roles/{id}/permissions', 'PermissionController@index')->name('permissions');
Route::post('/roles', [ 'as' => 'roles.store', 'uses' => 'RoleController@store']);
Route::get('/roles/{id}/destroy', 'RoleController@destroy')->name('roles');

Route::get('/abilities/create', 'AbilitieController@create')->name('abilities');
Route::get('/abilities', 'AbilitieController@index')->name('abilities');
Route::post('/abilities', [ 'as' => 'abilities.store', 'uses' => 'AbilitieController@store']);
Route::get( '/abilities/search', array('as' => 'abilities.search', 'uses' => 'AbilitieController@search'));

Route::post('/assignedroles', [ 'as' => 'assignedroles.store', 'uses' => 'AssignedroleController@store']);
Route::post('/permissions', [ 'as' => 'permissions.store', 'uses' => 'PermissionController@store']);

Route::post('/alumnos/finder', [ 'as' => 'alumnos.finder', 'uses' => 'AlumnoController@finder']);
Route::get( '/alumnos/search', array('as' => 'alumnos.search', 'uses' => 'AlumnoController@search'));
Route::get('/alumnos/{id}/create', 'AlumnoController@create')->name('alumnos');
Route::post('/alumnos', [ 'as' => 'alumnos.store', 'uses' => 'AlumnoController@store']);
Route::resource('alumnos', 'AlumnoController');

Route::get( '/votantes/search', array('as' => 'votantes.search', 'uses' => 'VotanteController@search'));
Route::post('/votantes/finder', [ 'as' => 'votantes.finder', 'uses' => 'VotanteController@finder']);
Route::get('/votantes/{id}/create', 'VotanteController@create')->name('votantes');

Route::resource('votantes', 'VotanteController');

Route::get( '/voto/{categorias_id}/{votantes_id}/{proyectos_id}', array('as' => 'voto.store', 'uses' => 'VotoController@store'));

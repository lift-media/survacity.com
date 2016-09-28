<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@show');

Route::get('/home', 'HomeController@show');

Route::get('/team/name', function() {
		return Auth::user()->currentTeam->name;	
	});

Route::get('manage-email-template', 'HomeController@showEmailTemplate');
Route::get('add-email-template', 'HomeController@showAddEmailTemplate');
Route::post('add-email-template', 'HomeController@saveAddEmailTemplate');
Route::get('edit-email-template/{id}', 'HomeController@editEmailTemplate');
Route::post('edit-email-template/{id}', 'HomeController@updateEmailTemplate');
Route::get('delete-email-template/{id}', 'HomeController@deleteEmailTemplate');
Route::get('import-contacts', 'HomeController@showImportContacts');
Route::post('import-contacts', 'HomeController@saveImportContacts');
Route::get('listed-contacts', 'HomeController@showContacts');
Route::get('add-contact', 'HomeController@showAddContact');
Route::post('add-contact', 'HomeController@saveAddContact');
Route::get('edit-contact/{id}', 'HomeController@showEditContact');
Route::post('edit-contact/{id}', 'HomeController@saveEditContact');
Route::get('delete-contact/{id}', 'HomeController@deleteContact');

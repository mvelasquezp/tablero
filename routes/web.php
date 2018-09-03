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

//Route::get("/", "Intranet");
Route::middleware("auth")->namespace("Intranet")->group(function() {
	Route::get("/", "Main@home");
	//modulo de registros
	Route::prefix("intranet")->group(function() {
		Route::prefix("registros")->group(function() {
			Route::get("usuarios", "Registros@usuarios");
		});
	});
});
//ajax
Route::middleware("auth")->namespace("Ajax")->prefix("ajax")->group(function() {
	//modulo de registros
	Route::prefix("registros")->group(function() {
		Route::post("sv-usuario", "Registros@sv_usuario");
	});
});
//autenticacion de usuarios
Route::group(["prefix" => "login"], function() {
	Route::get("/", ["as" => "login", "uses" => "Autenticacion@form_login"]);
	Route::post("verificar", "Autenticacion@post_login");
	Route::get("logout", "Autenticacion@logout");
	Route::get("start", "Autenticacion@start");
	Route::post("start", "Autenticacion@post_start");
});
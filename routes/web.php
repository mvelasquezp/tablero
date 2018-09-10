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
			Route::get("organigrama", "Registros@organigrama");
		});
		//modulo de estandarizacion de procesos
		Route::prefix("estandarizacion")->group(function() {
			Route::get("maestros", "Estandarizacion@maestros");
			Route::get("procesos", "Estandarizacion@procesos");
		});
	});
});
//ajax
Route::middleware("auth")->namespace("Ajax")->prefix("ajax")->group(function() {
	//modulo de registros
	Route::prefix("registros")->group(function() {
		Route::post("sv-usuario", "Registros@sv_usuario");
		Route::any("ls-puestos", "Registros@ls_puestos");
		Route::post("sv-puesto", "Registros@sv_puesto");
	});
	//modulo de estandarizacion de procesos
	Route::prefix("estandarizacion")->group(function() {
		Route::any("ls-interfaz", "Estandarizacion@ls_interfaz");
		Route::post("ls-campos-hito", "Estandarizacion@ls_campos_hito");
		Route::post("sv-campo", "Estandarizacion@sv_campo");
		Route::post("sv-hito", "Estandarizacion@sv_hito");
		Route::post("sv-eproceso", "Estandarizacion@sv_eproceso");
		Route::post("sv-econtrol", "Estandarizacion@sv_econtrol");
		Route::post("ls-detalle-campos", "Estandarizacion@ls_detalle_campos");
		Route::post("sv-agrega-campo", "Estandarizacion@sv_agrega_campo");
		Route::post("sv-retira-campo", "Estandarizacion@sv_retira_campo");
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
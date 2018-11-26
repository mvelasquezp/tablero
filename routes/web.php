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
	Route::get("mail", "Main@mail");
	//modulo de registros
	Route::prefix("intranet")->group(function() {
		Route::prefix("registros")->group(function() {
			Route::get("usuarios", "Registros@usuarios");
			Route::get("organigrama", "Registros@organigrama");
			Route::get("administradores", "Registros@administradores");
			Route::get("bienvenida", "Registros@bienvenida");
		});
		//modulo de estandarizacion de procesos
		Route::prefix("estandarizacion")->group(function() {
			Route::get("maestros", "Estandarizacion@maestros");
			Route::get("procesos", "Estandarizacion@procesos");
			Route::get("valoracion", "Estandarizacion@valoracion");
			Route::get("usuarios", "Estandarizacion@usuarios");
		});
		//modulo de control y seguimiento
		Route::prefix("seguimiento")->group(function() {
			Route::get("resumen", "Control@resumen");
			Route::get("crea-proyecto", "Control@crear");
			Route::get("alertas", "Control@alertas");
		});
		//modulo de informes
		Route::prefix("reportes")->group(function() {
			Route::get("informe", "Reportes@informe");
			Route::get("estadisticas", "Reportes@estadisticas");
			//exportar
			Route::prefix("export")->group(function() {
				Route::get("informe", "Export@informe");
				Route::get("estadistica", "Export@estadistica");
			});
		});
	});
	Route::get("perfil", "Main@perfil");
});
//ajax
Route::middleware("auth")->namespace("Ajax")->prefix("ajax")->group(function() {
	//modulo de registros
	Route::prefix("registros")->group(function() {
		Route::post("sv-usuario", "Registros@sv_usuario");
		Route::post("dt-usuario", "Registros@dt_usuario");
		Route::any("ls-puestos", "Registros@ls_puestos");
		Route::any("ls-combo-puestos", "Registros@ls_combo_puestos");
		Route::post("ed-usuario", "Registros@ed_usuario");
		Route::post("sv-puesto", "Registros@sv_puesto");
		Route::post("ls-permisos", "Registros@ls_permisos");
		Route::post("sv-permisos", "Registros@sv_permisos");
		Route::post("dt-puesto", "Registros@dt_puesto");
		Route::post("ed-puesto", "Registros@ed_puesto");
		Route::post("sv-mensaje", "Registros@sv_mensaje");
	});
	//modulo de estandarizacion de procesos
	Route::prefix("estandarizacion")->group(function() {
		Route::any("ls-interfaz", "Estandarizacion@ls_interfaz");
		Route::post("ls-campos-hito", "Estandarizacion@ls_campos_hito");
		Route::post("sv-campo", "Estandarizacion@sv_campo");
		Route::post("upd-obligat-campo", "Estandarizacion@upd_obligat_campo");
		Route::post("sv-hito", "Estandarizacion@sv_hito");
		Route::post("sv-eproceso", "Estandarizacion@sv_eproceso");
		Route::post("sv-econtrol", "Estandarizacion@sv_econtrol");
		Route::post("ls-detalle-campos", "Estandarizacion@ls_detalle_campos");
		Route::post("sv-agrega-campo", "Estandarizacion@sv_agrega_campo");
		Route::post("sv-retira-campo", "Estandarizacion@sv_retira_campo");
		Route::post("sv-elimina-campo", "Estandarizacion@sv_elimina_campo");
		Route::post("sv-elimina-hito", "Estandarizacion@sv_elimina_hito");
		Route::post("sv-elimina-estado", "Estandarizacion@sv_elimina_estado");
		//
		Route::post("ls-hitos-proyecto", "Estandarizacion@ls_hitos_proyecto");
		Route::post("upd-retira-hito", "Estandarizacion@ls_retira_hito");
		Route::post("sv-hito-proyecto", "Estandarizacion@sv_hito_proyecto");
		Route::post("upd-hito-proyecto", "Estandarizacion@upd_hito_proyecto");
		Route::post("upd-sube-hito", "Estandarizacion@upd_sube_hito");
		Route::post("upd-baja-hito", "Estandarizacion@upd_baja_hito");
		//
		Route::post("sv-matriz-valoracion", "Estandarizacion@sv_matriz_valoracion");
		//
		Route::post("sv-organo", "Estandarizacion@sv_organo");
		Route::post("sv-direccion", "Estandarizacion@sv_direccion");
		Route::post("ls-combo-direcciones", "Estandarizacion@ls_combo_direcciones");
		Route::post("sv-area", "Estandarizacion@sv_area");
		Route::post("ls-combo-areas", "Estandarizacion@ls_combo_areas");
		Route::post("dt-organo", "Estandarizacion@dt_organo");
		Route::post("dt-direccion", "Estandarizacion@dt_direccion");
		Route::post("dt-area", "Estandarizacion@dt_area");
		Route::post("ed-organo", "Estandarizacion@ed_organo");
		Route::post("ed-direccion", "Estandarizacion@ed_direccion");
		Route::post("ed-area", "Estandarizacion@ed_area");
	});
	//modulo de control de proyectos
	Route::prefix("control")->group(function() {
		Route::post("ls-hitos-control", "Control@ls_hitos_control");
		Route::post("dt-proyecto", "Control@dt_proyecto");
		Route::post("sv-nueva-area", "Control@sv_nueva_area");
		Route::post("sv-proyecto", "Control@sv_proyecto");
		Route::post("upd-proyecto", "Control@upd_proyecto");
		Route::post("upd-responsable-proyecto", "Control@upd_responsable_proyecto");
		Route::post("ls-hitos-proyecto", "Control@ls_hitos_proyecto");
		Route::post("ls-estado-hito", "Control@ls_estado_hito");
		Route::post("upd-estado-hito", "Control@upd_estado_hito");
		Route::post("fl-busca-campo", "Control@fl_busca_campo");
		Route::post("sv-mensaje", "Control@sv_mensaje");
		Route::post("clona-proyecto", "Control@clona_proyecto");
	});
	//opciones generales
	Route::prefix("intranet")->group(function() {
		Route::post("upd-datos", "Intranet@upd_datos");
		Route::post("upd-clave", "Intranet@upd_clave");
		Route::post("upd-imagen", "Intranet@upd_imagen");
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
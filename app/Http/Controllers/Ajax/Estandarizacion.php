<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Estandarizacion extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    public function ls_interfaz() {
        $usuario = Auth::user();
        $campos = DB::table("ma_campos as mc")
        	->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
        	->select(
        		"mc.id_campo as id",
        		"mc.des_campo as campo",
        		"std.des_tipo as tipo",
        		"mc.created_at as registro",
                "mc.st_obligatorio as obligatorio"
        	)
            ->where("mc.st_vigente", "Vigente")
            ->get();
        $hitos = DB::table("ma_hitos_control as mhc")
        	->where("mhc.id_empresa", $usuario->id_empresa)
        	->where("mhc.st_vigente", "Vigente")
        	->select(
        		"mhc.id_hito as id",
        		"mhc.des_hito as hito",
                "mhc.nu_dias_disparador as dias",
        		DB::raw("date_format(mhc.created_at,'%Y-%m-%d') as fecha")
        	)
        	->orderBy("id", "asc")
        	->get();
    	$eprocesos = DB::table("sys_estados")
    		->where("tp_estado", "P")
            ->where("st_vigente", "Vigente")
    		->select(
    			"id_estado as id",
    			"cod_estado as codigo",
    			"des_estado as estado",
    			"created_at as fecha"
    		)
    		->orderBy("id", "asc")
    		->get();
		$econtrol = DB::table("sys_estados")
    		->where("tp_estado", "C")
            ->where("st_vigente", "Vigente")
    		->select(
    			"id_estado as id",
    			"cod_estado as codigo",
    			"des_estado as estado",
    			"created_at as fecha"
    		)
    		->orderBy("id", "asc")
    		->get();
        return Response::json([
        	"data" => [
        		"campos" => $campos,
        		"hitos" => $hitos,
        		"procesos" => $eprocesos,
        		"control" => $econtrol
        	]
        ]);
    }

    public function ls_campos_hito() {
    	extract(Request::input());
    	if(isset($hito)) {
            $usuario = Auth::user();
    		$campos = DB::table("pr_hitos_campo as phc")
    			->join("ma_campos as mc", function($join) {
    				$join->on("phc.id_campo", "=", "mc.id_campo")
    					->on("phc.id_empresa", "=", "mc.id_empresa");
    			})
    			->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
    			->where("phc.id_empresa", $usuario->id_empresa)
    			->where("phc.st_vigente", "Vigente")
    			->where("phc.id_hito", $hito)
    			->select(
    				"mc.id_campo as id",
    				"mc.des_campo as campo",
    				"std.des_tipo as tipo",
    				"phc.created_at as fecha",
                    "mc.st_obligatorio as obligatorio"
    			)
    			->get();
			return Response::json([
				"state" => "success",
				"data" => [
					"campos" => $campos
				]
			]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_campo() {
        extract(Request::input());
        if(isset($nombre, $tipo, $obligatorio)) {
            $usuario = Auth::user();
            DB::table("ma_campos")->insert([
            	"id_empresa" => $usuario->id_empresa,
            	"id_tipo" => $tipo,
            	"des_campo" => $nombre,
                "st_obligatorio" => $obligatorio
            ]);
	        $campos = DB::table("ma_campos as mc")
	        	->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
                ->where("mc.st_vigente", "Vigente")
	        	->select(
	        		"mc.id_campo as id",
	        		"mc.des_campo as campo",
	        		"std.des_tipo as tipo",
	        		"mc.created_at as registro",
                    "mc.st_obligatorio as obligatorio"
	        	)
	        	->orderBy("id", "asc")
	            ->get();
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró el campo " . $nombre
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "campos" => $campos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function upd_obligat_campo() {
        extract(Request::input());
        if(isset($hito, $tipo)) {
            $usuario = Auth::user();
            $nTipo = strcmp($tipo, "S") == 0 ? "N" : "S";
            DB::table("ma_campos")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_campo", $hito)
                ->update([
                    "st_obligatorio" => $nTipo,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            $campos = DB::table("ma_campos as mc")
                ->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
                ->select(
                    "mc.id_campo as id",
                    "mc.des_campo as campo",
                    "std.des_tipo as tipo",
                    "mc.created_at as registro",
                    "mc.st_obligatorio as obligatorio"
                )
                ->orderBy("id", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "campos" => $campos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_hito() {
    	extract(Request::input());
    	if(isset($nombre, $dias)) {
    		$usuario = Auth::user();
    		DB::table("ma_hitos_control")->insert([
    			"id_empresa" => $usuario->id_empresa,
    			"des_hito" => $nombre,
                "nu_dias_disparador" => $dias
    		]);
		    $hitos = DB::table("ma_hitos_control as mhc")
		    	->where("mhc.id_empresa", $usuario->id_empresa)
		    	->where("mhc.st_vigente", "Vigente")
		    	->select(
		    		"mhc.id_hito as id",
		    		"mhc.des_hito as hito",
		    		"mhc.created_at as fecha",
                    DB::raw("date_format(mhc.created_at,'%Y-%m-%d') as fecha")
		    	)
		    	->orderBy("id", "asc")
		    	->get();
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró el hito " . $nombre
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "hitos" => $hitos
                ]
            ]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_eproceso() {
    	extract(Request::input());
    	if(isset($estado, $codigo)) {
            $usuario = Auth::user();
    		DB::table("sys_estados")->insert([
    			"des_estado" => $estado,
    			"cod_estado" => $codigo,
    			"tp_estado" => "P"
    		]);
    		$estados = DB::table("sys_estados")
	    		->where("tp_estado", "P")
                ->where("st_vigente", "Vigente")
	    		->select(
	    			"id_estado as id",
	    			"cod_estado as codigo",
	    			"des_estado as estado",
	    			"created_at as fecha"
	    		)
	    		->orderBy("id", "asc")
	    		->get();
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró el estado de proceso " . $estado
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "estados" => $estados
                ]
            ]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_econtrol() {
    	extract(Request::input());
    	if(isset($estado, $codigo)) {
            $usuario = Auth::user();
    		DB::table("sys_estados")->insert([
    			"des_estado" => $estado,
    			"cod_estado" => $codigo,
    			"tp_estado" => "C"
    		]);
    		$estados = DB::table("sys_estados")
	    		->where("tp_estado", "C")
                ->where("st_vigente", "Vigente")
	    		->select(
	    			"id_estado as id",
	    			"cod_estado as codigo",
	    			"des_estado as estado",
	    			"created_at as fecha"
	    		)
	    		->orderBy("id", "asc")
	    		->get();
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró el estado de control " . $estado
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "estados" => $estados
                ]
            ]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_detalle_campos() {
    	extract(Request::input());
    	if(isset($hito)) {
    		$usuario = Auth::user();
    		$campos = DB::table("ma_campos as mc")
    			->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
    			->leftJoin("pr_hitos_campo as phc", function($join) use($hito) {
    				$join->on("mc.id_campo", "=", "phc.id_campo")
    					->on("mc.id_empresa", "=", "phc.id_empresa")
    					->on("phc.id_hito", "=", DB::raw($hito));
    			})
    			->where("mc.id_empresa", $usuario->id_empresa)
                ->where("mc.st_obligatorio", "N")
                ->where("mc.st_vigente", "VIgente")
    			->select(
    				"mc.id_campo as id",
    				"mc.des_campo as campo",
    				"std.des_tipo as tipo",
    				DB::raw("ifnull(phc.id_hito,0) as hito"),
                    "mc.st_obligatorio as obligatorio"
    			)
                ->orderBy("id", "asc")
    			->get();
			return Response::json([
				"state" => "success",
				"data" => [
					"campos" => $campos
				]
			]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_agrega_campo() {
    	extract(Request::input());
    	if(isset($hito, $campo)) {
    		$usuario = Auth::user();
    		DB::table("pr_hitos_campo")->insert([
    			"id_hito" => $hito,
    			"id_empresa" => $usuario->id_empresa,
    			"id_campo" => $campo,
    			"id_usuario_asigna" => $usuario->id_usuario
    		]);
            $campos = DB::table("pr_hitos_campo as phc")
                ->join("ma_campos as mc", function($join) {
                    $join->on("phc.id_campo", "=", "mc.id_campo")
                        ->on("phc.id_empresa", "=", "mc.id_empresa");
                })
                ->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
                ->where("phc.id_empresa", $usuario->id_empresa)
                ->where("phc.st_vigente", "Vigente")
                ->where("phc.id_hito", $hito)
                ->select(
                    "mc.id_campo as id",
                    "mc.des_campo as campo",
                    "std.des_tipo as tipo",
                    "phc.created_at as fecha",
                    "mc.st_obligatorio as obligatorio"
                )
                ->get();
            //registra en el historial
            $dcampo = DB::table("ma_campos")->where("id_campo", $campo)->select("des_campo")->first();
            $dhito = DB::table("ma_hitos_control")->where("id_hito", $hito)->select("des_hito")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Asignó el campo " . $dcampo->des_campo . " al hito de control " . $dhito->des_hito
            ]);
    		return Response::json([
    			"state" => "success",
                "data" => [
                    "campos" => $campos
                ]
    		]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_retira_campo() {
    	extract(Request::input());
    	if(isset($hito, $campo)) {
    		$usuario = Auth::user();
    		DB::table("pr_hitos_campo")
    			->where("id_hito", $hito)
    			->where("id_campo", $campo)
    			->where("id_empresa", $usuario->id_empresa)
    			->delete();
            $campos = DB::table("pr_hitos_campo as phc")
                ->join("ma_campos as mc", function($join) {
                    $join->on("phc.id_campo", "=", "mc.id_campo")
                        ->on("phc.id_empresa", "=", "mc.id_empresa");
                })
                ->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
                ->where("phc.id_empresa", $usuario->id_empresa)
                ->where("phc.st_vigente", "Vigente")
                ->where("phc.id_hito", $hito)
                ->select(
                    "mc.id_campo as id",
                    "mc.des_campo as campo",
                    "std.des_tipo as tipo",
                    "phc.created_at as fecha",
                    "mc.st_obligatorio as obligatorio"
                )
                ->get();
            //registra en el historial
            $dcampo = DB::table("ma_campos")->where("id_campo", $campo)->select("des_campo")->first();
            $dhito = DB::table("ma_hitos_control")->where("id_hito", $hito)->select("des_hito")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Retiró el campo " . $dcampo->des_campo . " del hito de control " . $dhito->des_hito
            ]);
    		return Response::json([
    			"state" => "success",
                "data" => [
                    "campos" => $campos
                ]
    		]);
    	}
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_hitos_proyecto() {
        extract(Request::input());
        if(isset($tipo)) {
            $usuario = Auth::user();
            $procesos = DB::table("ma_hitos_control as mhc")
                ->leftJoin("pr_catalogo_hitos as pch", function($join) use($tipo) {
                    $join->on("mhc.id_hito", "=", "pch.id_hito")
                        ->on("mhc.id_empresa", "=", "pch.id_empresa")
                        ->on("pch.id_catalogo", "=", DB::raw($tipo))
                        ->on("pch.st_vigente", DB::raw("'Vigente'"));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("pch.id_usuario_registra", "=", "mu.id_usuario")
                        ->on("pch.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->where("mhc.id_empresa", $usuario->id_empresa)
                ->where("mhc.st_vigente", "Vigente")
                ->select(
                    "mhc.id_hito as id",
                    "pch.nu_orden as orden",
                    "pch.id_catalogo as tipo",
                    "mhc.des_hito as proceso",
                    "pch.nu_peso as peso",
                    "me.des_nombre_1 as agrega",
                    "pch.created_at as fregistro"
                )
                ->orderBy("pch.nu_orden", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "procesos" => $procesos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_retira_hito() {
        extract(Request::input());
        if(isset($hito, $tipo)) {
            $usuario = Auth::user();
            /*$count = DB::table("pr_proyecto")->count();
            if($count == 0) {
                //
            }
            return Response::json();*/
            $orden = DB::table("pr_catalogo_hitos")
                ->where("id_hito", $hito)
                ->where("id_catalogo", $tipo)
                ->where("id_empresa", $usuario->id_empresa)
                ->select("nu_orden as orden")
                ->first();
            DB::table("pr_catalogo_hitos")
                ->where("id_hito", $hito)
                ->where("id_catalogo", $tipo)
                ->where("id_empresa", $usuario->id_empresa)
                ->update([
                    "st_vigente" => "Retirado",
                    "nu_orden" => 0
                ]);
            DB::table("pr_catalogo_hitos")
                ->where("id_catalogo", $tipo)
                ->where("id_empresa", $usuario->id_empresa)
                ->where("nu_orden", ">", $orden->orden)
                ->decrement("nu_orden", 1);
            $procesos = DB::table("ma_hitos_control as mhc")
                ->leftJoin("pr_catalogo_hitos as pch", function($join) use($tipo) {
                    $join->on("mhc.id_hito", "=", "pch.id_hito")
                        ->on("mhc.id_empresa", "=", "pch.id_empresa")
                        ->on("pch.id_catalogo", "=", DB::raw($tipo));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("pch.id_usuario_registra", "=", "mu.id_usuario")
                        ->on("pch.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->where("mhc.id_empresa", $usuario->id_empresa)
                ->where("mhc.st_vigente", "Vigente")
                ->where("pch.st_vigente", "Vigente")
                ->select(
                    "mhc.id_hito as id",
                    "pch.nu_orden as orden",
                    "pch.id_catalogo as tipo",
                    "mhc.des_hito as proceso",
                    "pch.nu_peso as peso",
                    "me.des_nombre_1 as agrega",
                    "pch.created_at as fregistro"
                )
                ->orderBy("pch.nu_orden", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "procesos" => $procesos,
                    "orden" => $orden
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_hito_proyecto() {
        extract(Request::input());
        if(isset($tipo, $hito, $peso)) {
            $usuario = Auth::user();
            $orden = DB::table("pr_catalogo_hitos")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_catalogo", $tipo)
                ->max("nu_orden");
            DB::table("pr_catalogo_hitos")->insert([
                "id_hito" => $hito,
                "id_empresa" => $usuario->id_empresa,
                "id_catalogo" => $tipo,
                "id_usuario_registra" => $usuario->id_usuario,
                "nu_peso" => $peso,
                "nu_orden" => ($orden + 1)
            ]);
            $procesos = DB::table("ma_hitos_control as mhc")
                ->leftJoin("pr_catalogo_hitos as pch", function($join) use($tipo) {
                    $join->on("mhc.id_hito", "=", "pch.id_hito")
                        ->on("mhc.id_empresa", "=", "pch.id_empresa")
                        ->on("pch.id_catalogo", "=", DB::raw($tipo));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("pch.id_usuario_registra", "=", "mu.id_usuario")
                        ->on("pch.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->where("mhc.id_empresa", $usuario->id_empresa)
                ->select(
                    "mhc.id_hito as id",
                    "pch.nu_orden as orden",
                    "pch.id_catalogo as tipo",
                    "mhc.des_hito as proceso",
                    "pch.nu_peso as peso",
                    "me.des_nombre_1 as agrega",
                    "pch.created_at as fregistro"
                )
                ->orderBy("pch.nu_orden", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "procesos" => $procesos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function upd_hito_proyecto() {
        extract(Request::input());
        if(isset($tipo, $hito, $peso)) {
            $usuario = Auth::user();
            DB::table("pr_catalogo_hitos")
                ->where("id_hito", $hito)
                ->where("id_catalogo", $tipo)
                ->where("id_empresa", $usuario->id_empresa)
                ->update([
                    "nu_peso" => $peso,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            return Response::json([
                "state" => "success"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function upd_sube_hito() {
        extract(Request::input());
        if(isset($hito, $tipo, $orden)) {
            $usuario = Auth::user();
            DB::table("pr_catalogo_hitos")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_catalogo", $tipo)
                ->where("nu_orden", $orden - 1)
                ->update([
                    "nu_orden" => $orden,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            DB::table("pr_catalogo_hitos")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_catalogo", $tipo)
                ->where("id_hito", $hito)
                ->update([
                    "nu_orden" => ($orden - 1),
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            $procesos = DB::table("ma_hitos_control as mhc")
                ->leftJoin("pr_catalogo_hitos as pch", function($join) use($tipo) {
                    $join->on("mhc.id_hito", "=", "pch.id_hito")
                        ->on("mhc.id_empresa", "=", "pch.id_empresa")
                        ->on("pch.id_catalogo", "=", DB::raw($tipo));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("pch.id_usuario_registra", "=", "mu.id_usuario")
                        ->on("pch.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->where("mhc.id_empresa", $usuario->id_empresa)
                ->where("pch.st_vigente", "Vigente")
                ->select(
                    "mhc.id_hito as id",
                    "pch.nu_orden as orden",
                    "pch.id_catalogo as tipo",
                    "mhc.des_hito as proceso",
                    "pch.nu_peso as peso",
                    "me.des_nombre_1 as agrega",
                    "pch.created_at as fregistro"
                )
                ->orderBy("pch.nu_orden", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "procesos" => $procesos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function upd_baja_hito() {
        extract(Request::input());
        if(isset($hito, $tipo, $orden)) {
            $usuario = Auth::user();
            DB::table("pr_catalogo_hitos")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_catalogo", $tipo)
                ->where("nu_orden", $orden + 1)
                ->update([
                    "nu_orden" => $orden,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            DB::table("pr_catalogo_hitos")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_catalogo", $tipo)
                ->where("id_hito", $hito)
                ->update([
                    "nu_orden" => ($orden + 1),
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            $procesos = DB::table("ma_hitos_control as mhc")
                ->leftJoin("pr_catalogo_hitos as pch", function($join) use($tipo) {
                    $join->on("mhc.id_hito", "=", "pch.id_hito")
                        ->on("mhc.id_empresa", "=", "pch.id_empresa")
                        ->on("pch.id_catalogo", "=", DB::raw($tipo));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("pch.id_usuario_registra", "=", "mu.id_usuario")
                        ->on("pch.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->where("mhc.id_empresa", $usuario->id_empresa)
                ->where("pch.st_vigente", "Vigente")
                ->select(
                    "mhc.id_hito as id",
                    "pch.nu_orden as orden",
                    "pch.id_catalogo as tipo",
                    "mhc.des_hito as proceso",
                    "pch.nu_peso as peso",
                    "me.des_nombre_1 as agrega",
                    "pch.created_at as fregistro"
                )
                ->orderBy("pch.nu_orden", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "procesos" => $procesos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_matriz_valoracion() {
        extract(Request::input());
        if(isset($pesos)) {
            $usuario = Auth::user();
            foreach ($pesos as $peso) {
                extract($peso);
                $count = DB::table("pr_valoracion")
                    ->where("id_estado_p", $catp)
                    ->where("id_estado_c", $catc)
                    ->update([
                        "num_puntaje" => $peso,
                        "updated_at" => date("Y-m-d H:i:s")
                    ]);
                if($count == 0) {
                    DB::table("pr_valoracion")->insert([
                        "id_estado_p" => $catp,
                        "id_estado_c" => $catc,
                        "num_puntaje" => $peso,
                        "id_usuario_registra" => $usuario->id_usuario
                    ]);
                }
            }
            $puntajes = DB::table("pr_valoracion")
                ->select("id_estado_p as pest", "id_estado_c as cest", "num_puntaje as puntaje")
                ->get();
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Actualizó la matriz de valoración"
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "puntajes" => $puntajes
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_organo() {
        extract(Request::input());
        if(isset($nombre, $abrev)) {
            $usuario = Auth::user();
            DB::table("ma_organo_control")->insert([
                "id_empresa" => $usuario->id_empresa,
                "des_organo" => $nombre,
                "des_abreviatura" => $abrev
            ]);
            //
            $organos = DB::table("ma_organo_control")
                ->where("id_empresa", $usuario->id_empresa)
                ->select(
                    "id_organo as id",
                    "des_organo as organo",
                    "des_abreviatura as abrev"
                )
                ->orderBy("des_organo", "asc")
                ->get();
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró el órgano central " . $nombre
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "organos" => $organos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_direccion() {
        extract(Request::input());
        if(isset($organo, $nombre, $abrev)) {
            $usuario = Auth::user();
            DB::table("ma_direccion_central")->insert([
                "id_empresa" => $usuario->id_empresa,
                "id_organo" => $organo,
                "des_direccion" => $nombre,
                "des_abreviatura" => $abrev
            ]);
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró la dirección general " . $nombre
            ]);
            //
            $direcciones = DB::table("ma_direccion_central as mdc")
                ->join("ma_organo_control as moc", function($join) {
                    $join->on("mdc.id_empresa", "=", "moc.id_empresa")
                        ->on("mdc.id_organo", "=", "moc.id_organo");
                })
                ->select(
                    "mdc.id_direccion as id",
                    "moc.des_organo as organo",
                    "mdc.des_direccion as direccion",
                    "mdc.des_abreviatura as abrev"
                )
                ->orderBy("mdc.des_direccion", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "direcciones" => $direcciones
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_direccion_2() {
        extract(Request::input());
        if(isset($organo, $nombre, $abrev)) {
            $usuario = Auth::user();
            DB::table("ma_direccion_central")->insert([
                "id_empresa" => $usuario->id_empresa,
                "id_organo" => $organo,
                "des_direccion" => $nombre,
                "des_abreviatura" => $abrev
            ]);
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró la dirección general " . $nombre
            ]);
            //
            $direcciones = DB::table("ma_direccion_central")
                ->where("id_organo", $organo)
                ->select(
                    "id_direccion as value",
                    "des_direccion as text"
                )
                ->orderBy("des_direccion", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "direcciones" => $direcciones
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_combo_direcciones() {
        extract(Request::input());
        if(isset($organo)) {
            $usuario = Auth::user();
            $direcciones = DB::table("ma_direccion_central")
                ->select("id_direccion as value", "des_direccion as text")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_organo", $organo)
                ->orderBy("des_direccion", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "direcciones" => $direcciones
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_area() {
        extract(Request::input());
        if(isset($organo, $direccion, $nombre, $abrev)) {
            $usuario = Auth::user();
            DB::table("ma_area_usuaria")->insert([
                "id_direccion" => $direccion,
                "id_organo" => $organo,
                "id_empresa" => $usuario->id_empresa,
                "des_area" => $nombre,
                "des_abreviatura" => $abrev
            ]);
            //registra en el historial
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Registró el área usuaria " . $nombre
            ]);
            $areas = DB::table("ma_area_usuaria as mau")
                ->join("ma_direccion_central as mdc", function($join) {
                    $join->on("mau.id_empresa", "=", "mdc.id_empresa")
                        ->on("mau.id_direccion", "=", "mdc.id_direccion")
                        ->on("mau.id_organo", "=", "mdc.id_organo");
                })
                ->join("ma_organo_control as moc", function($join) {
                    $join->on("mdc.id_empresa", "=", "moc.id_empresa")
                        ->on("mdc.id_organo", "=", "moc.id_organo");
                })
                ->select(
                    "mau.id_area as id",
                    "moc.des_organo as organo",
                    "mdc.des_direccion as direccion",
                    "mau.des_area as area",
                    "mau.des_abreviatura as abrev"
                )
                ->where("mau.id_empresa", $usuario->id_empresa)
                ->orderBy("moc.des_organo", "asc")
                ->orderBy("mdc.des_direccion", "asc")
                ->orderBy("mau.des_area", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "areas" => $areas
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_combo_areas() {
        extract(Request::input());
        if(isset($organo, $direccion)) {
            $usuario = Auth::user();
            $areas = DB::table("ma_area_usuaria")
                ->select("id_area as value", "des_area as text")
                ->where("id_empresa", $usuario->id_empresa)
                ->where("id_organo", $organo)
                ->where("id_direccion", $direccion)
                ->orderBy("des_area", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "areas" => $areas
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_elimina_campo() {
        extract(Request::input());
        if(isset($id)) {
            DB::table("ma_campos")
                ->where("id_campo", $id)
                ->update([
                    "st_vigente" => "Retirado"
                ]);
            $campos = DB::table("ma_campos as mc")
                ->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
                ->select(
                    "mc.id_campo as id",
                    "mc.des_campo as campo",
                    "std.des_tipo as tipo",
                    "mc.created_at as registro",
                    "mc.st_obligatorio as obligatorio"
                )
                ->where("mc.st_vigente", "Vigente")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "campos" => $campos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_elimina_hito() {
        extract(Request::input());
        if(isset($id)) {
            $usuario = Auth::user();
            DB::table("ma_hitos_control")
                ->where("id_hito", $id)
                ->where("id_empresa", $usuario->id_empresa)
                ->update([
                    "st_vigente" => "Retirado"
                ]);
            //registra en el historial
            $dhito = DB::table("ma_hitos_control")->where("id_hito", $id)->select("des_hito")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Eliminó el hito " . $dhito->des_hito
            ]);
            //
            $hitos = DB::table("ma_hitos_control as mhc")
                ->where("mhc.id_empresa", $usuario->id_empresa)
                ->where("mhc.st_vigente", "Vigente")
                ->select(
                    "mhc.id_hito as id",
                    "mhc.des_hito as hito",
                    "mhc.nu_dias_disparador as dias",
                    DB::raw("date_format(mhc.created_at,'%Y-%m-%d') as fecha")
                )
                ->orderBy("id", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "hitos" => $hitos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function sv_elimina_estado() {
        extract(Request::input());
        if(isset($id)) {
            DB::table("sys_estados")
                ->where("id_estado", $id)
                ->update([
                    "st_vigente" => "Retirado"
                ]);
            $eprocesos = DB::table("sys_estados")
                ->where("tp_estado", "P")
                ->where("st_vigente", "Vigente")
                ->select(
                    "id_estado as id",
                    "cod_estado as codigo",
                    "des_estado as estado",
                    "created_at as fecha"
                )
                ->orderBy("id", "asc")
                ->get();
            $econtrol = DB::table("sys_estados")
                ->where("tp_estado", "C")
                ->where("st_vigente", "Vigente")
                ->select(
                    "id_estado as id",
                    "cod_estado as codigo",
                    "des_estado as estado",
                    "created_at as fecha"
                )
                ->orderBy("id", "asc")
                ->get();
            return Response::json([
                "state" => "success",
                "data" => [
                    "eprocesos" => $eprocesos,
                    "econtrol" => $econtrol
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function dt_organo() {
        extract(Request::input());
        if(isset($id)) {
            $usuario = Auth::user();
            $data = DB::table("ma_organo_control")
                ->where("id_organo", $id)
                ->select("id_organo as id", "des_organo as nombre", "des_abreviatura as abrev")
                ->first();
            if($data) return Response::json([
                "state" => "success",
                "data" => [
                    "organo" => $data
                ]
            ]);
        }
    }

    public function dt_direccion() {
        extract(Request::input());
        if(isset($id)) {
            $usuario = Auth::user();
            $data = DB::table("ma_direccion_central")
                ->where("id_direccion", $id)
                ->select("id_direccion as id", "des_direccion as nombre", "des_abreviatura as abrev")
                ->first();
            if($data) return Response::json([
                "state" => "success",
                "data" => [
                    "direccion" => $data
                ]
            ]);
        }
    }

    public function dt_area() {
        extract(Request::input());
        if(isset($id)) {
            $usuario = Auth::user();
            $data = DB::table("ma_area_usuaria")
                ->where("id_area", $id)
                ->select("id_area as id", "des_area as nombre", "des_abreviatura as abrev", "st_vigente as vigencia")
                ->first();
            if($data) return Response::json([
                "state" => "success",
                "data" => [
                    "area" => $data
                ]
            ]);
        }
    }

    public function ed_organo() {
        extract(Request::input());
        if(isset($id, $nombre, $abreviatura)) {
            $usuario = Auth::user();
            DB::table("ma_organo_control")
                ->where("id_organo", $id)
                ->update([
                    "des_organo" => $nombre,
                    "des_abreviatura" => $abreviatura,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            $organos = DB::table("ma_organo_control")
                ->where("id_empresa", $usuario->id_empresa)
                ->select(
                    "id_organo as id",
                    "des_organo as organo",
                    "des_abreviatura as abrev"
                )
                ->orderBy("des_organo", "asc")
                ->get();
            //registra en el historial
            $dorgano = DB::table("ma_organo_control")->where("id_organo", $id)->select("des_organo as nombre")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Actualizó el órgano central  " . $dorgano->nombre
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "organos" => $organos
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ed_direccion() {
        extract(Request::input());
        if(isset($id, $nombre, $abreviatura)) {
            $usuario = Auth::user();
            DB::table("ma_direccion_central")
                ->where("id_direccion", $id)
                ->update([
                    "des_direccion" => $nombre,
                    "des_abreviatura" => $abreviatura,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            $direcciones = DB::table("ma_direccion_central as mdc")
                ->join("ma_organo_control as moc", function($join) {
                    $join->on("mdc.id_empresa", "=", "moc.id_empresa")
                        ->on("mdc.id_organo", "=", "moc.id_organo");
                })
                ->select(
                    "mdc.id_direccion as id",
                    "moc.des_organo as organo",
                    "mdc.des_direccion as direccion",
                    "mdc.des_abreviatura as abrev"
                )
                ->where("mdc.id_empresa", $usuario->id_empresa)
                ->orderBy("moc.des_organo", "asc")
                ->orderBy("mdc.des_direccion", "asc")
                ->get();
            //registra en el historial
            $ddireccion = DB::table("ma_direccion_central")->where("id_direccion", $id)->select("des_direccion as nombre")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Actualizó el órgano central  " . $ddireccion->nombre
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "direcciones" => $direcciones
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ed_area() {
        extract(Request::input());
        if(isset($id, $nombre, $abreviatura, $vigencia)) {
            $usuario = Auth::user();
            DB::table("ma_area_usuaria")
                ->where("id_area", $id)
                ->update([
                    "des_area" => $nombre,
                    "des_abreviatura" => $abreviatura,
                    "st_vigente" => $vigencia,
                    "updated_at" => date("Y-m-d H:i:s")
                ]);
            $areas = DB::table("ma_area_usuaria as mau")
                ->join("ma_direccion_central as mdc", function($join) {
                    $join->on("mau.id_empresa", "=", "mdc.id_empresa")
                        ->on("mau.id_direccion", "=", "mdc.id_direccion")
                        ->on("mau.id_organo", "=", "mdc.id_organo");
                })
                ->join("ma_organo_control as moc", function($join) {
                    $join->on("mdc.id_empresa", "=", "moc.id_empresa")
                        ->on("mdc.id_organo", "=", "moc.id_organo");
                })
                ->select(
                    "mau.id_area as id",
                    "moc.des_organo as organo",
                    "mdc.des_direccion as direccion",
                    "mau.des_area as area",
                    "mau.des_abreviatura as abrev",
                    "mau.st_vigente as vigencia"
                )
                ->where("mau.id_empresa", $usuario->id_empresa)
                ->orderBy("moc.des_organo", "asc")
                ->orderBy("mdc.des_direccion", "asc")
                ->orderBy("mau.des_area", "asc")
                ->get();
            //registra en el historial
            $darea = DB::table("ma_area_usuaria")->where("id_area", $id)->select("des_area as nombre")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Actualizó el área usuaria  " . $darea->nombre
            ]);
            return Response::json([
                "state" => "success",
                "data" => [
                    "areas" => $areas
                ]
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function upd_dias_disparador() {
        extract(Request::input());
        if(isset($hito, $disparador)) {
            $usuario = Auth::user();
            DB::table("ma_hitos_control")
                ->where("id_hito", $hito)
                ->where("id_empresa", $usuario->id_empresa)
                ->update([
                    "nu_dias_disparador" => $disparador
                ]);
            $dhito = DB::table("ma_hitos_control")->where("id_hito", $hito)->select("des_hito")->first();
            DB::table("ma_control_cambios")->insert([
                "id_usuario" => $usuario->id_usuario,
                "id_empresa" => $usuario->id_empresa,
                "des_accion" => "Actualizó el disparador del hito " . $dhito->des_hito . " en " . $disparador . " días"
            ]);
            return Response::json([
                "state" => "success"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

}
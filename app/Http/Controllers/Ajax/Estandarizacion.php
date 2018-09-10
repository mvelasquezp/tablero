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
        		"mc.created_at as registro"
        	)
            ->get();
        $hitos = DB::table("ma_hitos_control as mhc")
        	->join("ma_puesto as mp", function($join) {
        		$join->on("mp.id_puesto", "=", "mhc.id_responsable")
        			->on("mp.id_empresa", "=", "mhc.id_empresa");
        	})
        	->where("mhc.id_empresa", $usuario->id_empresa)
        	->where("mhc.st_vigente", "Vigente")
        	->select(
        		"mhc.id_hito as id",
        		"mhc.des_hito as hito",
        		"mp.des_puesto as puesto",
        		"mhc.created_at as fecha"
        	)
        	->orderBy("id", "asc")
        	->get();
    	$eprocesos = DB::table("sys_estados")
    		->where("tp_estado", "P")
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
    				"phc.created_at as fecha"
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
        if(isset($nombre, $tipo)) {
            $usuario = Auth::user();
            DB::table("ma_campos")->insert([
            	"id_empresa" => $usuario->id_empresa,
            	"id_tipo" => $tipo,
            	"des_campo" => $nombre
            ]);
	        $campos = DB::table("ma_campos as mc")
	        	->join("sys_tipos_dato as std", "mc.id_tipo", "=", "std.id_tipo")
	        	->select(
	        		"mc.id_campo as id",
	        		"mc.des_campo as campo",
	        		"std.des_tipo as tipo",
	        		"mc.created_at as registro"
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
    	if(isset($nombre, $responsable)) {
    		$usuario = Auth::user();
    		DB::table("ma_hitos_control")->insert([
    			"id_empresa" => $usuario->id_empresa,
    			"id_responsable" => $responsable,
    			"des_hito" => $nombre
    		]);
		    $hitos = DB::table("ma_hitos_control as mhc")
		    	->join("ma_puesto as mp", function($join) {
		    		$join->on("mp.id_puesto", "=", "mhc.id_responsable")
		    			->on("mp.id_empresa", "=", "mhc.id_empresa");
		    	})
		    	->where("mhc.id_empresa", $usuario->id_empresa)
		    	->where("mhc.st_vigente", "Vigente")
		    	->select(
		    		"mhc.id_hito as id",
		    		"mhc.des_hito as hito",
		    		"mp.des_puesto as puesto",
		    		"mhc.created_at as fecha"
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

    public function sv_eproceso() {
    	extract(Request::input());
    	if(isset($estado, $codigo)) {
    		DB::table("sys_estados")->insert([
    			"des_estado" => $estado,
    			"cod_estado" => $codigo,
    			"tp_estado" => "P"
    		]);
    		$estados = DB::table("sys_estados")
	    		->where("tp_estado", "P")
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
    		DB::table("sys_estados")->insert([
    			"des_estado" => $estado,
    			"cod_estado" => $codigo,
    			"tp_estado" => "C"
    		]);
    		$estados = DB::table("sys_estados")
	    		->where("tp_estado", "C")
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
    			->select(
    				"mc.id_campo as id",
    				"mc.des_campo as campo",
    				"std.des_tipo as tipo",
    				DB::raw("ifnull(phc.id_hito,0) as hito")
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
    		return Response::json([
    			"state" => "success"
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
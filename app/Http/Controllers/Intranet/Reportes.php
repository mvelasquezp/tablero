<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Reportes extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    private function ObtenerMenu($usuario) {
        $menu = DB::table("sys_permisos as sp")
            ->join("ma_menu as mm", "sp.id_item", "=", "mm.id_item")
            ->where("sp.id_empresa", $usuario->id_empresa)
            ->where("sp.id_usuario", $usuario->id_usuario)
            ->where("mm.st_vigente", "Vigente")
            ->whereNull("mm.id_ancestro")
            ->select(
                "sp.id_item as id",
                "mm.des_nombre as nombre",
                "mm.des_url as url"
            )
            ->orderBy("mm.id_item", "asc")
            ->get();
        foreach($menu as $i => $item) {
            $submenu = DB::table("sys_permisos as sp")
                ->join("ma_menu as mm", "sp.id_item", "=", "mm.id_item")
                ->where("sp.id_empresa", $usuario->id_empresa)
                ->where("sp.id_usuario", $usuario->id_usuario)
                ->where("mm.id_ancestro", $item->id)
                ->where("mm.st_vigente", "Vigente")
                ->select(
                    "sp.id_item as id",
                    "mm.des_nombre as nombre",
                    "mm.des_url as url"
                )
                ->orderBy("mm.id_item", "asc")
                ->get();
            $menu[$i]->items = $submenu;
        }
        return $menu;
    }

    public function informe() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $proyectos = DB::table("pr_proyecto as pp")
            ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
            ->join("ma_area_usuaria as mau", function($join) {
                $join->on("pp.id_area", "=", "mau.id_area")
                    ->on("pp.id_direccion", "=", "mau.id_direccion")
                    ->on("pp.id_organo", "=", "mau.id_organo")
                    ->on("pp.id_empresa", "=", "mau.id_empresa");
            })
            ->join("pr_proyecto_hitos as pph", function($join) {
                $join->on("pp.id_proyecto", "=", "pph.id_proyecto")
                    ->on("pp.id_empresa", "=", "pph.id_empresa");
            })
            ->join("pr_catalogo_hitos as pch", function($join) {
                $join->on("pph.id_empresa", "=", "pch.id_empresa")
                    ->on("pph.id_hito", "=", "pch.id_hito")
                    ->on("pph.id_catalogo", "=", "pch.id_catalogo");
            })
            ->join("pr_valoracion as pv", function($join) {
                $join->on("pph.id_estado_proceso", "=", "pv.id_estado_p")
                    ->on("pph.id_estado_documentacion", "=", "pv.id_estado_c");
            })
            ->select(
                "pp.id_proyecto as id",
                "pp.id_catalogo as catalogo",
                "pcp.des_catalogo as tipo",
                DB::raw("if(pp.tp_orden = 'C','Compras','Servicios') as orden"),
                "pp.des_expediente as expediente",
                DB::raw("date_format(pp.created_at,'%Y-%m-%d') as femision"),
                "mau.des_area as areausr",
                "pp.des_proyecto as proyecto",
                DB::raw("date_format(pp.fe_fin,'%Y-%m-%d') as fentrega"),
                "pp.num_valor as valor",
                "pp.num_armadas as armadas",
                DB::raw("if(datediff(current_timestamp,pp.fe_fin) < 0,0,datediff(current_timestamp,pp.fe_fin)) as diasvence"),
                "pp.des_observaciones as observaciones",
                DB::raw("100 * sum(pch.nu_peso * pv.num_puntaje)/sum(pch.nu_peso) as avance")
            )
            ->where("pp.id_empresa", $usuario->id_empresa)
            ->groupBy("id", "catalogo", "tipo", "orden", "expediente", "femision", "areausr", "proyecto", "fentrega", "valor", "armadas", "diasvence", "observaciones")
            ->orderBy("pp.id_proyecto", "asc")
            ->get();
        //busca los ultimos hitos por proyecto
        foreach($proyectos as $idx => $proyecto) {
            $detalle = DB::table("pr_proyecto_hitos as pph")
                ->join("ma_hitos_control as mhc", function($join) {
                    $join->on("pph.id_hito", "=", "mhc.id_hito")
                        ->on("pph.id_empresa", "=", "mhc.id_empresa");
                })
                ->join("ma_puesto as mp", function($join) {
                    $join->on("pph.id_responsable", "=", "mp.id_puesto")
                        ->on("pph.id_empresa", "=", "mp.id_empresa");
                })
                ->leftJoin("us_usuario_puesto as uup", function($join) {
                    $join->on("mp.id_puesto", "=", "uup.id_puesto")
                        ->on("mp.id_empresa", "=", "uup.id_empresa")
                        ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
                })
                ->leftJoin("ma_usuarios as mu", function($join) {
                    $join->on("uup.id_usuario", "=", "mu.id_usuario")
                        ->on("uup.id_empresa", "=", "mu.id_empresa");
                })
                ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
                ->select(
                    DB::raw("ifnull(pph.des_hito, mhc.des_hito) as hito"),
                    DB::raw("if(me.cod_entidad is null, mp.des_puesto, concat(me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3)) as responsable"),
                    "pph.des_observaciones as observaciones",
                    DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador")
                )
                ->where("pph.id_estado_proceso", 3)
                ->where("pph.id_proyecto", $proyecto->id)
                ->where("pph.id_empresa", $usuario->id_empresa)
                ->orderBy("pph.id_detalle", "desc")
                ->get();
            if(count($detalle) > 0) {
                $detalle = $detalle[0];
                $proyectos[$idx]->estado = $detalle->hito;
                $proyectos[$idx]->responsable = $detalle->responsable;
                $proyectos[$idx]->hobservaciones = $detalle->observaciones;
                $proyectos[$idx]->indicador = $detalle->indicador;
            }
            else {
                $proyectos[$idx]->estado = "";
                $proyectos[$idx]->responsable = "";
                $proyectos[$idx]->hobservaciones = "";
                $proyectos[$idx]->indicador = "secondary";
            }
            //cálculo del % avance
        }
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "proyectos" => $proyectos,
        ];
        return view("reportes.informe")->with($arr_data);
    }

    public function estadisticas() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        //grafico 1
        $data1 = DB::table("pr_proyecto as pp")
        	->join("pr_catalogo_proyecto as pcp", function($join) {
        		$join->on("pp.id_catalogo", "=", "pcp.id_catalogo")
        			->on("pp.id_empresa", "=", "pcp.id_empresa");
        	})
        	->select(
        		"pcp.des_catalogo as catalogo",
        		DB::raw("count(pp.id_proyecto) as cantidad")
        	)
        	->groupBy("catalogo")
        	->get();
    	$data2 = DB::table("pr_proyecto")
    		->select(
    			DB::raw("if(tp_orden = 'C', 'Compras', 'Servicios') as tipo"),
    			DB::raw("count(id_proyecto) as cantidad")
    		)
    		->where("id_catalogo", 1)
    		->groupBy("tipo")
    		->get();
		$data31 = DB::table("pr_proyecto as pp")
			->join("ma_area_usuaria as maa", function($join) {
				$join->on("pp.id_area", "=", "maa.id_area")
					->on("pp.id_direccion", "=", "maa.id_direccion")
					->on("pp.id_organo", "=", "maa.id_organo")
					->on("pp.id_empresa", "=", "maa.id_empresa");
			})
			->where("pp.id_catalogo", 1)
			->select(
				"maa.des_area as area",
				DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
				DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
				DB::raw("count(pp.id_proyecto) as total")
			)
    		->groupBy("area")
			->get();
		$data32 = DB::table("pr_proyecto as pp")
			->join("ma_area_usuaria as maa", function($join) {
				$join->on("pp.id_area", "=", "maa.id_area")
					->on("pp.id_direccion", "=", "maa.id_direccion")
					->on("pp.id_organo", "=", "maa.id_organo")
					->on("pp.id_empresa", "=", "maa.id_empresa");
			})
			->where("pp.id_catalogo", 2)
			->select(
				"maa.des_area as area",
				DB::raw("count(pp.id_proyecto) as total")
			)
    		->groupBy("area")
			->get();
		$data41 = DB::table("pr_proyecto as pp")
			->leftJoin("pr_proyecto_hitos as pph", function($join) {
				$join->on("pp.id_proyecto", "=", "pph.id_proyecto")
					->on("pp.id_empresa", "=", "pph.id_empresa")
					->on("pp.id_catalogo", "=", "pph.id_catalogo")
					->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
			})
			->leftJoin("us_usuario_puesto as uup", function($join) {
				$join->on("pph.id_responsable", "=", "uup.id_puesto")
					->on("pph.id_empresa", "=", "uup.id_empresa");
			})
			->leftJoin("ma_puesto as mp", function($join) {
				$join->on("pph.id_empresa", "=", "mp.id_empresa")
					->on("pph.id_responsable", "=", "mp.id_puesto");
			})
			->leftJoin("ma_usuarios as mu", function($join) {
				$join->on("uup.id_usuario", "=", "mu.id_usuario")
					->on("uup.id_empresa", "=", "mu.id_empresa");
			})
			->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
			->select(
				DB::raw("if(pph.id_responsable is null, '(sin agisnar)', if(uup.id_usuario is null,mp.des_puesto,concat(me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3))) as responsable"),
				DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
				DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
				DB::raw("count(pp.id_proyecto) as total")
			)
			->where("pp.id_catalogo", 1)
			->groupBy("responsable")
			->get();
		$data42 = DB::table("pr_proyecto as pp")
			->leftJoin("pr_proyecto_hitos as pph", function($join) {
				$join->on("pp.id_proyecto", "=", "pph.id_proyecto")
					->on("pp.id_empresa", "=", "pph.id_empresa")
					->on("pp.id_catalogo", "=", "pph.id_catalogo")
					->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
			})
			->leftJoin("us_usuario_puesto as uup", function($join) {
				$join->on("pph.id_responsable", "=", "uup.id_puesto")
					->on("pph.id_empresa", "=", "uup.id_empresa");
			})
			->leftJoin("ma_puesto as mp", function($join) {
				$join->on("pph.id_empresa", "=", "mp.id_empresa")
					->on("pph.id_responsable", "=", "mp.id_puesto");
			})
			->leftJoin("ma_usuarios as mu", function($join) {
				$join->on("uup.id_usuario", "=", "mu.id_usuario")
					->on("uup.id_empresa", "=", "mu.id_empresa");
			})
			->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
			->select(
				DB::raw("if(pph.id_responsable is null, '(sin agisnar)', if(uup.id_usuario is null,mp.des_puesto,concat(me.des_nombre_1,' ',me.des_nombre_2,' ',me.des_nombre_3))) as responsable"),
				DB::raw("count(pp.id_proyecto) as total")
			)
			->where("pp.id_catalogo", 2)
			->groupBy("responsable")
			->get();
		$data51 = DB::table("pr_proyecto as pp")
			->leftJoin("pr_proyecto_hitos as pph", function($join) {
				$join->on("pp.id_proyecto", "=", "pph.id_proyecto")
					->on("pp.id_empresa", "=", "pph.id_empresa")
					->on("pp.id_catalogo", "=", "pph.id_catalogo")
					->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
			})
			->select(
				DB::raw("if(datediff(current_timestamp, pph.fe_fin) > 0, datediff(current_timestamp, pph.fe_fin), 0) as dias"),
				DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
				DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
				DB::raw("count(pp.id_proyecto) as total")
			)
			->where("pp.id_catalogo", 1)
			->groupBy("dias")
			->get();
		$data52 = DB::table("pr_proyecto as pp")
			->leftJoin("pr_proyecto_hitos as pph", function($join) {
				$join->on("pp.id_proyecto", "=", "pph.id_proyecto")
					->on("pp.id_empresa", "=", "pph.id_empresa")
					->on("pp.id_catalogo", "=", "pph.id_catalogo")
					->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
			})
			->select(
				DB::raw("if(datediff(current_timestamp, pph.fe_fin) > 0, datediff(current_timestamp, pph.fe_fin), 0) as dias"),
				DB::raw("count(pp.id_proyecto) as total")
			)
			->where("pp.id_catalogo", 2)
			->groupBy("dias")
			->get();
		$data61 = DB::table("pr_proyecto as pp")
			->leftJoin("pr_proyecto_hitos as pph", function($join) {
				$join->on("pp.id_proyecto", "=", "pph.id_proyecto")
					->on("pp.id_empresa", "=", "pph.id_empresa")
					->on("pp.id_catalogo", "=", "pph.id_catalogo")
					->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
			})
			->leftJoin("ma_hitos_control as mhc", function($join) {
				$join->on("pph.id_hito", "=", "mhc.id_hito")
					->on("pph.id_empresa", "=", "mhc.id_empresa");
			})
			->select(
				DB::raw("ifnull(ifnull(pph.des_hito,mhc.des_hito),'(sin iniciar)') as hito"),
				DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
				DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
				DB::raw("count(pp.id_proyecto) as total")
			)
			->where("pp.id_catalogo", 1)
			->groupBy("hito")
			->get();
		$data62 = DB::table("pr_proyecto as pp")
			->leftJoin("pr_proyecto_hitos as pph", function($join) {
				$join->on("pp.id_proyecto", "=", "pph.id_proyecto")
					->on("pp.id_empresa", "=", "pph.id_empresa")
					->on("pp.id_catalogo", "=", "pph.id_catalogo")
					->on(DB::raw("f_ultimo_hito(pp.id_proyecto,pp.id_empresa)"), "=", "pph.id_detalle");
			})
			->leftJoin("ma_hitos_control as mhc", function($join) {
				$join->on("pph.id_hito", "=", "mhc.id_hito")
					->on("pph.id_empresa", "=", "mhc.id_empresa");
			})
			->select(
				DB::raw("ifnull(ifnull(pph.des_hito,mhc.des_hito),'(sin iniciar)') as hito"),
				DB::raw("count(if(pp.tp_orden = 'C',1,null)) as compras"),
				DB::raw("count(if(pp.tp_orden = 'S',1,null)) as servicios"),
				DB::raw("count(pp.id_proyecto) as total")
			)
			->where("pp.id_catalogo", 2)
			->groupBy("hito")
			->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "data1" => $data1,
            "data2" => $data2,
            "data31" => $data31,
            "data32" => $data32,
            "data41" => $data41,
            "data42" => $data42,
            "data51" => $data51,
            "data52" => $data52,
            "data61" => $data61,
            "data62" => $data62,
        ];
        return view("reportes.estadisticas")->with($arr_data);
    }

}
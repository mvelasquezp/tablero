<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Control extends Controller {
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

    public function resumen() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $atributos = DB::table("ma_campos")
            ->select(
                "id_campo as value",
                "des_campo as text",
                "id_tipo as tipo"
            )
            ->where("id_empresa", $usuario->id_empresa)
            ->where("st_obligatorio", "N")
            ->where("st_vigente", "Vigente")
            ->orderBy("text", "asc")
            ->get();
        $proyectos = DB::table("pr_proyecto as pp")
            ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
            ->join("ma_area_usuaria as mau", function($join) {
                $join->on("pp.id_area", "=", "mau.id_area")
                    ->on("pp.id_direccion", "=", "mau.id_direccion")
                    ->on("pp.id_organo", "=", "mau.id_organo")
                    ->on("pp.id_empresa", "=", "mau.id_empresa");
            })
            ->join("ma_direccion_central as mdc", function($join) {
                $join->on("mau.id_direccion", "=", "mdc.id_direccion")
                    ->on("mau.id_organo", "=", "mdc.id_organo")
                    ->on("mau.id_empresa", "=", "mdc.id_empresa");
            })
            ->join("ma_organo_control as moc", function($join) {
                $join->on("mdc.id_organo", "=", "moc.id_organo")
                    ->on("mdc.id_empresa", "=", "moc.id_empresa");
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
                DB::raw("concat(moc.des_organo,' - ',mdc.des_direccion,' - ',mau.des_area) as areausr"),
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
                    DB::raw("if(pph.id_estado_proceso = 3,(if(datediff(current_timestamp, pph.fe_fin) > 0,'danger',if(datediff(pph.fe_fin, current_timestamp) < mhc.nu_dias_disparador,'success','warning'))),'secondary') as indicador"),
                    DB::raw("if(datediff(current_timestamp,pph.fe_fin) < 0,0,datediff(current_timestamp,pph.fe_fin)) as diasvence")
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
                $proyectos[$idx]->diasvence = $detalle->diasvence;
            }
            else {
                $proyectos[$idx]->estado = "";
                $proyectos[$idx]->responsable = "";
                $proyectos[$idx]->hobservaciones = "";
                $proyectos[$idx]->indicador = "secondary";
                $proyectos[$idx]->diasvence = 0;
            }
            //cálculo del % avance
        }
        //listo
        $estados = DB::table("sys_estados")
            ->select("id_estado as value", "des_estado as text", "tp_estado as tipo")
            ->orderBy("tp_estado", "asc")
            ->orderBy("id_estado", "asc")
            ->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "proyectos" => $proyectos,
            "estados" => $estados,
            "atributos" => $atributos
        ];
        return view("control.resumen")->with($arr_data);
    }

    public function crear() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $tipos = DB::table("pr_catalogo_proyecto")
            ->where("id_empresa", $usuario->id_empresa)
            ->where("st_vigente", "Vigente")
            ->select(
                "id_catalogo as value",
                "des_catalogo as text"
            )
            ->orderBy("text", "asc")
            ->get();
        $organos = DB::table("ma_organo_control")
            ->where("id_empresa", $usuario->id_empresa)
            ->select(
                "id_organo as value",
                "des_organo as text"
            )
            ->orderBy("des_organo", "asc")
            ->get();
        $id_pago = DB::table("ma_hitos_control")
            ->select("id_hito as id")
            ->where("des_hito", "Pago")
            ->where("id_empresa", $usuario->id_empresa)
            ->where("st_vigente", "Vigente")
            ->first();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "tipos" => $tipos,
            "organos" => $organos,
            "id_pago" => $id_pago->id,
        ];
        return view("control.crear")->with($arr_data);
    }

    public function alertas() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $mensaje = DB::table("sys_mensaje")
            ->select("des_titulo as saludo", "des_cuerpo as cuerpo", "des_boton as boton")
            ->first();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "mensaje" => $mensaje,
        ];
        return view("control.alertas")->with($arr_data);
    }

}
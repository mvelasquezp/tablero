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
        $proyectos = DB::table("pr_proyecto as pp")
            ->join("pr_catalogo_proyecto as pcp", "pp.id_catalogo", "=", "pcp.id_catalogo")
            ->join("ma_area_usuaria as mau", function($join) {
                $join->on("pp.id_area", "=", "mau.id_area")
                    ->on("pp.id_direccion", "=", "mau.id_direccion")
                    ->on("pp.id_organo", "=", "mau.id_organo")
                    ->on("pp.id_empresa", "=", "mau.id_empresa");
            })
            ->select(
                "pp.id_proyecto as id",
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
                "pp.des_observaciones as observaciones"
            )
            ->where("pp.id_empresa", $usuario->id_empresa)
            ->orderBy("pp.id_proyecto", "asc")
            ->get();
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

}
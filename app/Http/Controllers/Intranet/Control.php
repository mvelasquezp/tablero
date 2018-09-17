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
            ->join("ma_oficina as mo", function($join) {
                $join->on("pp.id_oficina", "=", "mo.id_oficina")
                    ->on("pp.id_empresa", "=", "mo.id_empresa");
            })
            ->select(
                "pp.id_proyecto as id",
                "pcp.des_catalogo as tipo",
                "pp.created_at as fregistro",
                "pp.des_proyecto as proyecto",
                "pp.des_expediente as expediente",
                "pp.des_hoja_tramite as hojatramite",
                "mo.des_oficina as areausr",
                "pp.num_valor as valor",
                "pp.des_observaciones as observaciones"
            )
            ->where("pp.id_empresa", $usuario->id_empresa)
            ->orderBy("pp.id_proyecto", "asc")
            ->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "proyectos" => $proyectos,
        ];
        return view("control.resumen")->with($arr_data);
    }

}
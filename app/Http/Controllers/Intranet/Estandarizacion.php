<?php

namespace App\Http\Controllers\Intranet;

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

    public function maestros() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $tipos = DB::table("sys_tipos_dato")
            ->select("id_tipo as value", "des_tipo as text")
            ->orderBy("text", "asc")
            ->get();
        $puestos = DB::table("ma_puesto")
            ->where("id_empresa", $usuario->id_empresa)
            ->select("id_puesto as value", "des_puesto as text")
            ->orderBy("text", "asc")
            ->get();
        //
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "tipos" => $tipos,
            "puestos" => $puestos
        ];
        return view("estandarizacion.maestros")->with($arr_data);
    }

    public function procesos() {
        $usuario = Auth::user();
        $menu = $this->ObtenerMenu($usuario);
        $tipos_proyecto = DB::table("pr_catalogo_proyecto")
            ->where("id_empresa", $usuario->id_empresa)
            ->where("st_vigente", "Vigente")
            ->select(
                "id_catalogo as value",
                "des_catalogo as text"
            )
            ->orderBy("text", "asc")
            ->get();
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu,
            "tipos" => $tipos_proyecto,
        ];
        return view("estandarizacion.procesos")->with($arr_data);
    }

}
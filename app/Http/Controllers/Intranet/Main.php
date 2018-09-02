<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Main extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    public function home() {
        $usuario = Auth::user();
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
        $arr_data = [
            "usuario" => $usuario,
            "menu" => $menu
        ];
        return view("intranet.home")->with($arr_data);
    }

}
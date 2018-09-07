<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Registros extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    public function sv_usuario() {
        extract(Request::input());
        if(isset($apemat, $apepat, $dni, $mail, $nombres, $tpdoc)) {
            $usuario = Auth::user();
            $telefono = isset($telefono) ? $telefono : "";
            //genera la entidad
            DB::table("ma_entidad")->insert([
                "cod_entidad" => $dni,
                "des_nombre_1" => strtoupper($apepat),
                "des_nombre_2" => strtoupper($apemat),
                "des_nombre_3" => strtoupper($nombres),
                "tp_documento" => $tpdoc
            ]);
            //genera el usuario
            $password = substr($apepat,0,2) . substr($apemat,0,2) . $dni;
            DB::table("ma_usuarios")->insert([
                "des_alias" => substr($nombres,0,1) . $apepat . substr($apemat,0,1),
                "des_email" => $mail,
                "des_telefono" => $telefono,
                "tp_usuario" => "U",
                "password" => \Hash::make($password),
                "st_verifica_mail" => "N",
                "id_empresa" => $usuario->id_empresa,
                "cod_entidad" => $dni
            ]);
            if(isset($_FILES["foto"])) {
                $sourcePath = $_FILES["foto"]["tmp_name"];
                $targetPath = env("APP_STORAGE_PATH") . DIRECTORY_SEPARATOR . $_FILES["foto"]["name"];
                move_uploaded_file($sourcePath, $targetPath);
            }
            return Response::json([
                "state" => "success"
            ]);
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

    public function ls_puestos() {
        $usuario = Auth::user();
        $puestos = DB::table("ma_puesto as mp")
            ->leftJoin("us_usuario_puesto as uup", function($join) {
                $join->on("mp.id_puesto", "=", "uup.id_puesto")
                    ->on("mp.id_empresa", "=", "uup.id_empresa")
                    ->on("uup.st_vigente", "=", DB::raw("'Vigente'"));
            })
            ->leftJoin("ma_usuarios as mu", function($join) {
                $join->on("uup.id_usuario", "=", "mu.id_usuario")
                    ->on("uup.id_empresa", "=", "mu.id_empresa");
            })
            ->leftJoin("ma_oficina as mo", function($join) {
                $join->on("mp.id_oficina", "=", "mo.id_oficina")
                    ->on("mp.id_empresa", "=", "mo.id_empresa");
            })
            ->leftJoin("ma_entidad as me", "mu.cod_entidad", "=", "me.cod_entidad")
            ->where("mp.st_vigente", "Vigente")
            ->where("mp.id_empresa", $usuario->id_empresa)
            ->select(
                "mp.id_puesto as id",
                "mp.id_superior as parentId",
                "mp.des_puesto as name",
                DB::raw("ifnull(concat(me.des_nombre_1, ' ', me.des_nombre_2, ' ', me.des_nombre_3),'(sin asignar)') as title"),
                DB::raw("ifnull(mu.des_telefono,'-') as phone"),
                DB::raw("ifnull(mu.des_email,'-') as mail"),
                DB::raw("ifnull(mo.des_oficina, '(sin asignar)') as oficina"),
                "mp.st_vigente as vigencia"
            )
            ->get();
        return Response::json($puestos);
    }

    public function sv_puesto() {
        extract(Request::input());
        if(isset($xxx)) {
            $usuario = Auth::user();
            $nuevoPuesto = [
                "des_puesto" => $nombre,
                "num_jerarquia" => 1,
                "id_empresa" => $usuario->id_empresa
            ];
            if(isset($ancestro)) {
                //
            }
            if(isset($oficina)) {
                //
            }
            //hacer la insercion
        }
        return Response::json([
            "state" => "error",
            "msg" => "Parámetros incorrectos"
        ]);
    }

}
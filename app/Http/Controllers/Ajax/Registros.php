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

}
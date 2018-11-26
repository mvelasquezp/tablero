<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Request;
use Response;
use App\User as User;

class Intranet extends Controller {
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */

    public function __construct() {
        //
    }

    public function upd_datos() {
        extract(Request::input());
        if(isset($key, $telefono, $email)) {
            list($yUsuario, $yDni) = explode("@", decrypt($key));
            DB::table("ma_usuarios")
                ->where("id_usuario", $yUsuario)
                ->where("cod_entidad", $yDni)
                ->update([
                    "des_email" => $email,
                    "des_telefono" => $telefono
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

    public function upd_clave() {
        extract(Request::input());
        if(isset($key, $nclave)) {
            list($yUsuario, $yDni) = explode("@", decrypt($key));
            DB::table("ma_usuarios")
                ->where("id_usuario", $yUsuario)
                ->where("cod_entidad", $yDni)
                ->update([
                    "password" => \Hash::make($nclave)
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

    public function upd_imagen() {
        extract(Request::input());
        if(isset($key)) {
            list($yUsuario, $yDni) = explode("@", decrypt($key));
            if(isset($_FILES["imagen"])) {
                $b64 = file_get_contents($_FILES["imagen"]["tmp_name"]);
                $imgpath = implode(DIRECTORY_SEPARATOR, [env("APP_STORAGE_PATH"), $yDni . ".jpg"]);
                if(file_exists($imgpath)) unlink($imgpath);
                file_put_contents($imgpath, $b64);
                return redirect("perfil");
            }
            return "Por favor, seleccione una imagen para actualizar la foto de perfil";
        }
        return "Parámetros incorrectos";
    }

}
<?php

namespace Controllers;

use Model\Paquete;
use Model\Registro;
use Model\Usuario;
use MVC\Router;


class RegistroController
{

    public static function crear(Router $router)
    {
        if (!is_auth()) {
            header("location: /");
        }
        // $registro = Registro::where("usuario_id", $_SESSION["id"]);
        // if (isset($registro) && $registro->paquete_id == "3") {
        //     header("location: /boleto?id=" . urldecode($registro->token));
        // }
        $router->render("registro/crear", [
            "titulo" => "Finalizar Registro"
        ]);
    }
    public static function gratis(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!is_auth()) {
                header("location: /login");
            }

            $registro = Registro::where("usuario_id", $_SESSION["id"]);
            if (isset($registro) && $registro->paquete_id == "3") {
                header("location: /boleto?id=" . urldecode($registro->token));
            }


            $token = substr(md5(uniqid(rand())), 0, 8);
            $datos = [
                "paquete_id" => 3,
                "pago_id" => "",
                "token" => $token,
                "usuario_id" => $_SESSION["id"]
            ];
            $registro = new Registro($datos);
            $resultado = $registro->guardar();
            if ($resultado) {
                header("location: /boleto?id=" . urldecode($registro->token));
            }
        }
    }
    public static function pagar(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!is_auth()) {
                header("location: /login");
            }

            //validar el post si viene vacio
            if (empty($_POST)) {
               echo json_encode([]);
               return;
            }

            //crear registro
            $token = substr(md5(uniqid(rand())), 0, 8);
            $datos = $_POST;
            $datos["token"] = $token;
            $datos["usuario_id"]= $_SESSION["id"];
            try {
                $registro = new Registro($datos);
                $resultado = $registro->guardar();
                echo json_encode($resultado);
                
            } catch (\Throwable $th) {
                echo json_encode(["resultado"=> "error"]);
            }
        }
    }
    public static function boleto(Router $router)
    {
        $token = $_GET["id"];
        if (!$token ||  !strlen($token) == 8) {
            header("location: / ");
        }
        $registro = Registro::where("token", $token);

        if (!$registro) {
            header("location: / ");
        }
        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);



        $router->render("registro/boleto", [

            "titulo" => "Asistencia a DevWebCamp",
            "registro" => $registro
        ]);
    }
}

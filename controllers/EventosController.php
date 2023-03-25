<?php

namespace Controllers;

use Model\Categoria;
use Model\Dia;
use Model\Evento;
use Model\Horas;
use MVC\Router;

class EventosController
{
    public static function index(Router $router)
    {
        $router->render("admin/eventos/index", ["titulo" => "Conferencias / Workshops"]);
    }
    public static function crear(Router $router)
    {
        $alertas = [];
        $categorias = Categoria::all();
        $dias = Dia::all("ASC");
        $horas = Horas::all("ASC");
        $evento = new Evento;


        if ($_SERVER["REQUEST_METHOD"]== "POST") {
            $evento->sincronizar($_POST);
            $alertas= $evento->validar();
            if (empty($alertas)) {
                $resultado = $evento->guardar();

                if ($resultado) {
                    header("location: /admin/eventos");
                }
            }
        }

        $alertas= Evento::getAlertas();

        $router->render("/admin/eventos/crear", [
            "titulo" => "Registrar Evento",
            "alertas" => $alertas,
            "categorias" => $categorias,
            "dias" => $dias,
            "horas" => $horas,
            "evento"=> $evento
        ]);
    }
}

<?php 
namespace Controllers;

use Classes\Paginado;
use Model\Paquete;
use Model\Ponentes;
use Model\Registro;
use Model\Usuario;
use MVC\Router;

class RegistradosController {
    public static function index(Router $router){
        if(!is_admin()){
            header("location: /login");
        }

        $pagina_actual =$_GET["page"];
        $pagina_actual = filter_var($pagina_actual,FILTER_VALIDATE_INT);
        if (!$pagina_actual || $pagina_actual < 1) {
            header("location: /admin/registrados?page=1");
        }
        
        $registro_por_pagina=10;
        
        $total= Registro::total();
        $paginacion = new Paginado($pagina_actual,$registro_por_pagina, $total); 
       
        if ($paginacion->total_paginas() < $pagina_actual) {
            header("location:  /admin/registrados?page=1");
        }     

        $registros = Registro::paginar($registro_por_pagina, $paginacion->offset());
        foreach ($registros as $registro) {
           $registro->usuario = Usuario::find($registro->usuario_id);
           $registro->paquete = Paquete::find($registro->paquete_id);
        }

        
        $router->render("admin/registrados/index", [
            "titulo"=>"Usuarios Registrados",
            "registros" => $registros, 
            "paginacion" => $paginacion->paginacion()
    ]);

    }
}

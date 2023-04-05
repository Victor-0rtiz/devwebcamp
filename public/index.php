<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\APIeventos;
use Controllers\APIponentes;
use Controllers\AuthController;
use Controllers\DashboardController;
use Controllers\EventosController;
use Controllers\PaginasController;
use Controllers\PonentesController;
use Controllers\RegalosController;
use Controllers\RegistradosController;
use Controllers\RegistroController;
use MVC\Router;

$router = new Router();


// Login
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// Formulario de olvide mi password
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [AuthController::class, 'reestablecer']);
$router->post('/reestablecer', [AuthController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

//?area de administracion
$router->get('/admin/dashboard', [DashboardController::class, 'index']);
$router->post('/admin/dashboard', [DashboardController::class, 'index']);

$router->get('/admin/ponentes', [PonentesController::class, 'index']);

$router->get('/admin/ponentes/crear', [PonentesController::class, 'crear']);
$router->post('/admin/ponentes/crear', [PonentesController::class, 'crear']);

$router->get('/admin/ponentes/editar', [PonentesController::class, 'editar']);
$router->post('/admin/ponentes/editar', [PonentesController::class, 'editar']);

$router->post('/admin/ponentes/eliminar', [PonentesController::class, 'eliminar']);

$router->get('/admin/eventos', [EventosController::class, 'index']);
$router->get('/admin/eventos/crear', [EventosController::class, 'crear']);
$router->post('/admin/eventos/crear', [EventosController::class, 'crear']);

$router->get('/admin/eventos/editar', [EventosController::class, 'editar']);
$router->post('/admin/eventos/editar', [EventosController::class, 'editar']);
$router->post('/admin/eventos/eliminar', [EventosController::class, 'eliminar']);

$router->get('/admin/registrados', [RegistradosController::class, 'index']);
$router->get('/admin/regalos', [RegalosController::class, 'index']);


//!Api de dia-hora

$router->get('/api/eventos-horario', [APIeventos::class, 'index']);
$router->get('/api/ponentes', [APIponentes::class, 'index']);
$router->get('/api/ponente', [APIponentes::class, 'ponente']);

//?Area Publica

$router->get("/",[PaginasController::class, "index"]);
$router->get("/devwebcamp",[PaginasController::class, "evento"]);
$router->get("/paquetes",[PaginasController::class, "paquetes"]);
$router->get("/workshops-conferencias",[PaginasController::class, "conferencias"]);
$router->get("/404",[PaginasController::class, "error"]);

//?Registro de usuarios

$router->get("/finalizar-registro",[RegistroController::class, "crear"]);
$router->post("/finalizar-registro/gratis",[RegistroController::class, "gratis"]);
$router->post("/finalizar-registro/pagar",[RegistroController::class, "pagar"]);

//boleto virtual
$router->get("/boleto",[RegistroController::class, "boleto"]);





$router->comprobarRutas();

?>
<?php 

header('Content-Type: application/script; charset=utf-8');
header("Access-Control-Allow-Origin: *");


require_once "mdl.php";
session_start();

$App = new App();
$accion = isset( $_REQUEST['accion'] ) ? $_REQUEST['accion'] : null;

	switch($accion) { 
		case 'entrar':
			session_destroy();
			session_unset();
			session_start();
			echo $App->entrar( $_REQUEST ); 
		break;
		/*case 'salir':
			session_destroy();
			echo "{success:true}";
		break;
		case 'buscarUsuario':
			$res = $App->buscarUsuario( $_REQUEST ); 
			echo $res;
		break;
		case 'verUsuarios':
			$res = $App->verUsuarios(); 
			echo $res;
		break;
		case 'guardarUsuario':
			$res = $App->guardarUsuario( $_SESSION , $_REQUEST );
			echo $res;
		break;
		case 'verRoles':
			$res = $App->verRoles(); 
			echo $res;
		break;
		case 'actDesUsr':
			$res = $App->actDesUsr( $_REQUEST ); 
			echo $res;
		break;
		case 'verRequerimientos':
			$res = $App->verRequerimientos( $_REQUEST ); 
			echo $res;
		break;
		case 'verNiveles':
			$res = $App->verNiveles(); 
			echo $res;
		break;
		case 'verGerencias':
			$res = $App->verGerencias(); 
			echo $res;
		break;
		case 'verTiposReq':
			$res = $App->verTiposReq(); 
			echo $res;
		break;
		case 'guardarRequerimiento':
			$res = $App->guardarRequerimiento( $_REQUEST);
			echo $res;
		break;
		case 'guardarPostulado':
			$res = $App->guardarPostulado( $_REQUEST);
			echo $res;
		break;
		case 'borrarRequerimiento':
			$res = $App->borrarRequerimiento( $_REQUEST);
			echo $res;
		break;
		case 'verEstados':
			$res = $App->verEstados(); 
			echo $res;
		break;
		case 'verPostulados':
			$res = $App->verPostulados( $_REQUEST ); 
			echo $res;
		break;
		case 'verPotenciales':
			$res = $App->verPotenciales( $_REQUEST ); 
			echo $res;
		break;
		case 'borrarPostulado':
			$res = $App->borrarPostulado( $_REQUEST ); 
			echo $res;
		break;
		case 'vincularPostulado':
			$res = $App->vincularPostulado( $_REQUEST ); 
			echo $res;
		break;
		case 'desvincularPostulado':
			$res = $App->desvincularPostulado( $_REQUEST ); 
			echo $res;
		break;
		case 'cambiarIME':
			$res = $App->cambiarIME( $_REQUEST ); 
			echo $res;
		break;
		case 'cambiarMED':
			$res = $App->cambiarMED( $_REQUEST ); 
			echo $res;
		break;
		case 'cambiarPCP':
			$res = $App->cambiarPCP( $_REQUEST ); 
			echo $res;
		break;
		case 'cambiarSUP':
			$res = $App->cambiarSUP( $_REQUEST ); 
			echo $res;
		break;*/
		
		
	}
 ?> 
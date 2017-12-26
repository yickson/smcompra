<?php

/**
 * Controlador para manejar peticiones REST
 * 
 * Por defecto cada acción se llama como el método usado por el cliente
 * (GET, POST, PUT, DELETE, OPTIONS, HEADERS, PURGE...)
 * ademas se puede añadir mas acciones colocando delante el nombre del método
 * seguido del nombre de la acción put_cancel, post_reset...
 *
 * @category Kumbia
 * @package Controller
 * @author kumbiaPHP Team
 */
require_once CORE_PATH . 'kumbia/kumbia_rest.php';
class RestController extends KumbiaRest {

    /**
     * Autenticación de la API
     * @var type 
     */
//    protected $usuarios = array(
//        'connecta' => '123',
//        'test' => '123'
//    );
    
    final protected function initialize() {
	header('Access-Control-Allow-Origin: *');
//	$usuario = !empty(Session::get("usuario")) ? $usuario = Session::get("usuario") : null;
//        $pass    = !empty(Session::get("pass")) ? $pass = Session::get("pass") : null;
//        if (isset($this->usuarios[$usuario]) && ($this->usuarios[$usuario] == $pass)) {
//	    print_r("trueeee");
//            return true;
//        } else {
//            $this->data = $this->error("Fail authentication", 401);
//            header('WWW-Authenticate: Basic realm="Private Area"');
//            return false;
//	    print_r("faslsee");
//        }
    }

    final protected function finalize() {
        
    }

}
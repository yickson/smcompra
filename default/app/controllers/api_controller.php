<?php
class apiController extends RestController{
    
    //Constantes
    const PAGADO    = 1;
    const NO_PAGADO = 0;
    
     /**
     * Autenticación de la API
     * @var type 
     */
    protected $usuarios = array(
        'connecta' => '123',
        'test' => '123'
    );
    
//    public function __construct($arg) {
//	Session::delete("codigo");
//	Session::delete("usuario");
//	Session::delete("pass");
//	//
//	Session::set("codigo",  $arg["parameters"][0]);
//	Session::set("usuario", $arg["parameters"][1]);
//	Session::set("pass",    $arg["parameters"][2]);
//    }
    
    public function get(){
	
    }
    
    public function get_licencia($codigo, $user, $pass){
	if (isset($this->usuarios[$user]) && ($this->usuarios[$user] == $pass)) {
	    $licencia = (new Licences)->find_by_codigo($codigo);
	    $estado = ($licencia->estado == $this::PAGADO)?true:false;
	    $this->data =  $estado; 
        } else {
            $this->data = $this->error("Fail authentication", 401);
            header('WWW-Authenticate: Basic realm="Private Area"');
	}
       
        View::select(null, "json");
    }
    
    public function post(){
        
    }
    
    public function options() 
    {
    
    }
    
    public function delete(){
        
    }
} 

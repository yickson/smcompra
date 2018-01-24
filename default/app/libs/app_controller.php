<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller
{

    final protected function initialize()
    {
      /*$id = Session::get('id', 'administrador');
      if($id != ''){
        $adm = (New Administrador)->find($id);
          $rol = $adm->nivel;
      }
      else{
        $rol = '4';
      }

      if(Administrador::logged()) $this->userRol =$rol;

        $this->acl = new Acl();
        //Se agregan los roles
        //$this->acl->add_role(new AclRole("")); // Visitantes
        $this->acl->add_role(new AclRole('1')); // Administradores
        $this->acl->add_role(new AclRole('2')); // Call Center
        $this->acl->add_role(new AclRole('3')); // Gestores de pedidos
        $this->acl->add_role(new AclRole('4')); // Ver estadisticas

        //Se agregan los recursos
        $this->acl->add_resource(new AclResource('usuarios'), 'index', 'ver_usuarios','ver_usuario', 'listar_usuarios', 'editar_usuario', 'nuevo_usuario', 'borrar');
        $this->acl->add_resource(new AclResource('administrador'), 'index', 'crear', 'editar', 'listar', 'eliminar', 'almacenar',
                                                                   'ver_usuarios', 'ver_usuario', 'listar_usuarios', 'nuevo_usuario',
                                                                   'editar_usuario', 'borrar');
        $this->acl->add_resource(new AclResource("index"), 'index');
        $this->acl->add_resource(new AclResource('roles'), 'index', 'listar', 'editar', 'nuevo_rol','actualizar', 'eliminar','almacenar');
        $this->acl->add_resource(new AclResource('pedidos'), 'index', 'getPedidos', 'getPedidosSap', 'listar', 'eliminar', 'guardar', 'getNombre');
        $this->acl->add_resource(new AclResource('licencias'), 'index', 'listar');
        $this->acl->add_resource(new AclResource('estadisticas'), 'index', 'getTransAll', 'getUsoBySucursal', 'getUserByRol', 'getUserByRBD', 'getDispoByRBD',
                                                                  'getDispoByRBDdatatable', 'getUsoRepresentante');
        $this->acl->add_resource(new AclResource('productos'), 'index', 'listar', 'editar', 'crear','eliminar');
        $this->acl->add_resource(new AclResource('webpay'), 'index', 'editar', 'listar', 'nuevo', 'get');
        $this->acl->add_resource(new AclResource('cupones'), 'index', 'ingresar', 'exito', 'correo', 'correo2', 'registrar');
        $this->acl->add_resource(new AclResource('dashboard'), 'index');
        //$this->acl->add_resource(new AclResource('parvulos'), 'creacion', 'cupon');

        //Se crean los permisos
         // Inicio
        //$this->acl->allow("1", '*', '*'); No lee el comodín
        $this->acl->allow('1', 'usuarios', array('index', 'ver_usuarios', 'ver_usuario', 'listar_usuarios', 'editar_usuario', 'nuevo_usuario', 'borrar'));
        $this->acl->allow('1', 'administrador', array('index', 'crear', 'editar', 'listar', 'eliminar', 'almacenar',
                                                      'ver_usuarios', 'ver_usuario', 'listar_usuarios', 'nuevo_usuario',
                                                      'editar_usuario', 'borrar'));
        $this->acl->allow('1', 'roles', array('index', 'listar', 'editar', 'nuevo_rol','actualizar', 'eliminar','almacenar'));
        $this->acl->allow('1', 'pedidos', array('index', 'crear', 'envio', 'listar', 'guardar', 'getNombre'));
        $this->acl->allow('1', 'licencias', array('index', 'listar'));
        $this->acl->allow('1', 'estadisticas', array('index', 'getTransAll', 'getUsoBySucursal', 'getUserByRol', 'getUserByRBD',
                                                     'getDispoByRBD', 'getDispoByRBDdatatable', 'getUsoRepresentante'));
        $this->acl->allow('1', 'productos', array('index', 'listar', 'editar', 'crear','eliminar'));
        $this->acl->allow('1', 'webpay', array('index', 'editar', 'listar', 'nuevo', 'get'));
         // Acceso para el usuario nivel 2 CallCenter
        $this->acl->allow('2', "usuarios", array('index', 'ver_usuarios', 'listar_usuarios'));
        $this->acl->allow('2', 'administrador', 'index');
        // Acceso para el usuario nivel 3 Parvularios
        $this->acl->allow("3", 'pedidos', 'index', 'getPedidos', 'getPedidosSap');
        $this->acl->allow("3", 'parvulos', 'creacion');
         // Vistas de cupones
        $this->acl->allow('4', 'estadistica', array('index', 'ingresar', 'exito', 'correo', 'correo2', 'registrar'));*/
    }

    final protected function finalize()
    {

    }

}

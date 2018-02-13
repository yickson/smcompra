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
      $id = Session::get('id', 'administrador');
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
        $this->acl->add_role(new AclRole('4')); // Representantes de ventas

        //Se agregan los recursos
        $this->acl->add_resource(new AclResource('usuarios'), 'index', 'editar', 'editar_usuario', 'listar_con_hijos', 'consultarHijos', 'consultarDireccion');
        $this->acl->add_resource(new AclResource("index"), 'index', 'entrar');
        $this->acl->add_resource(new AclResource('administrador'), 'index', 'crear', 'editar', 'eliminar', 'listar_usuarios');
        $this->acl->add_resource(new AclResource('dashboard'), 'index');
        $this->acl->add_resource(new AclResource('pedidos'), 'index', 'mailer', 'listar_pedidos', 'listar_pedidos_mailer', 'enviarMail');
        $this->acl->add_resource(new AclResource('estadisticas'), 'index', 'getUsuarios', 'getComprasByUsuario', 'compra_mes', 'compra_semana', 'compra_dias');
        $this->acl->add_resource(new AclResource('productos'), 'index', 'agregar', 'editar', 'listar_productos', 'editar_producto', 'agregar_producto');
        $this->acl->add_resource(new AclResource('licencias'), 'index', 'listar_licencias');
        $this->acl->add_resource(new AclResource('representante'), 'index', 'data_representante', 'hijosProductos');
        $this->acl->add_resource(new AclResource('webpay'), 'index', 'licencias', 'productos', 'ingresos', 'listar_operaciones', 'compras_licencias', 'compras_productos', 'licPorcentaje', 'texPorcentaje');

        //Se crean los permisos
         // Inicio
        //$this->acl->allow("1", '*', '*'); No lee el comodín
        $this->acl->allow('1', 'usuarios', array('index', 'editar', 'editar_usuario', 'listar_con_hijos', 'consultarHijos', 'consultarDireccion'));
        $this->acl->allow('1', 'administrador', array('index', 'crear', 'editar', 'eliminar', 'listar_usuarios'));
        $this->acl->allow('1', 'index', array('index', 'entrar'));
        $this->acl->allow('1', 'dashboard', array('index'));
        $this->acl->allow('1', 'pedidos', array('index', 'mailer', 'listar_pedidos', 'listar_pedidos_mailer', 'enviarMail'));
        $this->acl->allow('1', 'licencias', array('index', 'listar_licencias'));
        $this->acl->allow('1', 'estadisticas', array('index', 'getUsuarios', 'getComprasByUsuario', 'compra_mes', 'compra_semana', 'compra_dias'));
        $this->acl->allow('1', 'productos', array('index', 'agregar', 'editar', 'listar_productos', 'editar_producto', 'agregar_producto'));
        $this->acl->allow('1', 'representante', array('index', 'data_representante', 'hijosProductos'));
        $this->acl->allow('1', 'webpay', array('index', 'licencias', 'productos', 'ingresos', 'listar_operaciones', 'compras_licencias', 'compras_productos', 'licPorcentaje', 'texPorcentaje'));
         // Acceso para el usuario nivel 2 CallCenter
        $this->acl->allow('2', "usuarios", array('index', 'editar', 'editar_usuario', 'listar_con_hijos', 'consultarHijos', 'consultarDireccion'));
        $this->acl->allow('2', 'dashboard', array('index'));
        $this->acl->allow('2', 'pedidos', array('index', 'mailer', 'listar_pedidos', 'listar_pedidos_mailer', 'enviarMail'));
        $this->acl->allow('2', 'webpay', array('index', 'licencias', 'productos', 'ingresos', 'listar_operaciones', 'compras_licencias', 'compras_productos', 'licPorcentaje', 'texPorcentaje'));
        //$this->acl->allow('2', 'administrador', 'index');
        // Acceso para el usuario nivel 3 Parvularios

         // Vistas de cupones
         $this->acl->allow('3', 'pedidos', array('index', 'mailer', 'listar_pedidos', 'listar_pedidos_mailer', 'enviarMail'));
         $this->acl->allow('3', 'licencias', array('index', 'listar_licencias'));
         $this->acl->allow('3', 'estadisticas', array('index', 'getUsuarios', 'getComprasByUsuario', 'compra_mes', 'compra_semana', 'compra_dias'));
         $this->acl->allow('3', 'productos', array('index', 'agregar', 'editar', 'listar_productos', 'editar_producto', 'agregar_producto'));
         $this->acl->allow('3', 'webpay', array('index', 'licencias', 'productos', 'ingresos', 'listar_operaciones', 'compras_licencias', 'compras_productos', 'licPorcentaje', 'texPorcentaje'));

         // Representantes
         $this->acl->allow('4', 'representante', array('index', 'data_representante', 'hijosProductos'));
    }

    final protected function finalize()
    {

    }

}

<?php  
////// DESDE VISUAL A CERNET   DESCE CERNET a Visua test
//LLAMADA AL ARCHIVO PRINCIPAL DE ACCESO A BASE DE DATOS E INTEGRACION DE SMARTY
error_reporting(0); //develop changes
ob_start(); //ob start call
include("config.ini.php");
 //A added
 //B added
<<<<<<< Updated upstream
<<<<<<< Updated upstream
 //update another one
<<<<<<< Updated upstream
<<<<<<< Updated upstream
=======
 //working on develop
 //working some other
>>>>>>> Stashed changes
=======

 //update fot git stash
>>>>>>> Stashed changes
=======

 //update fot git stash
>>>>>>> Stashed changes
=======
 //working on develop
 //working some other
>>>>>>> Stashed changes
$current_page = $_SERVER['REQUEST_URI'];

session_start();//start session
$mi_nombre=$_COOKIE['name'];
$mi_usuario=$_COOKIE['user'];
$mi_pass=$_COOKIE['pass'];
$mi_id=$_COOKIE['myid'];
$mi_cargo=$_COOKIE['cargo'];
$smarty->assign('mi_nombre',$mi_nombre);
$smarty->assign('mi_usuario',$mi_usuario);
$smarty->assign('mi_pass',$mi_pass);
$smarty->assign('mi_cargo',$mi_cargo);
$smarty->assign('mi_id',$mi_id);


/*if(!$mi_id == 2 or $mi_id == 106){
  header('Location: https://200.73.116.67/CerNet2.0/index.php');
}*/

   

$query = "SELECT a.id_privilegio, a.id_rol, b.imagen_usuario FROM usuario as a, persona as b WHERE a.id_usuario = ?";
$execute_query = mysqli_prepare($connect,$query);
mysqli_stmt_bind_param($execute_query, 'i', $mi_id);
mysqli_stmt_execute($execute_query);
mysqli_stmt_store_result($execute_query);
mysqli_stmt_bind_result($execute_query, $id_privilegio, $id_rol, $imagen);
mysqli_stmt_fetch($execute_query);

$smarty->assign("imagen",$imagen);
$smarty->assign("id_privilegio_actual",$id_privilegio);

//mostrar imagen 
$img = "SELECT imagen_usuario FROM persona WHERE id_usuario = ?";
$execute_query2 = mysqli_prepare($connect,$img);
mysqli_stmt_bind_param($execute_query2, 'i', $mi_id);
mysqli_stmt_execute($execute_query2);
mysqli_stmt_store_result($execute_query2);
mysqli_stmt_bind_result($execute_query2, $imagen_usuario);
mysqli_stmt_fetch($execute_query2);

$smarty->assign("imagen_usuario",$imagen_usuario);




$query2 ="SELECT Modulos, Control_cambios, Usuarios, Clientes, Items, Ordenes_trabajo, Servicios, Informes, Documentacion, Cargos FROM privilegio WHERE id_privilegio = ?";
$execute_query_2 = mysqli_prepare($connect,$query2);
mysqli_stmt_bind_param($execute_query_2, 'i', $id_privilegio);
mysqli_stmt_execute($execute_query_2);
mysqli_stmt_store_result($execute_query_2);
mysqli_stmt_bind_result($execute_query_2, $primer_modulo, $segundo_modulo, $tercer_modulo, $cuarto_modulo, $quinto_modulo, $sexto_modulo, $septimo_modulo, $octavo_modulo, $noveno_modulo, $decimo_modulo);
mysqli_stmt_fetch($execute_query_2);
 
 
//ARRAYS DE MODULOS Y NAVEGAVILIDAD hace referencia al modulo ej: usuario = 1 
$consultar_modulo = mysqli_prepare($connect,"SELECT id_modulo, nombre FROM modulo ORDER BY id_modulo ASC");
mysqli_stmt_execute($consultar_modulo);
mysqli_stmt_store_result($consultar_modulo);
mysqli_stmt_bind_result($consultar_modulo, $id_modulo, $nombre);

$modulo = array();

	while($row = mysqli_stmt_fetch($consultar_modulo)){
  
		$modulo[] = array(
		$nombre => $id_modulo
		);
	}
$smarty->assign('modulo',array(1,3,9,10,4,6,8,5,7,11,12,13,14));
$smarty->assign("page",array(1,2,3,4,5,6,7,8,9,10,11,12,13,14));


$aprobaciones = array();

$query_1 = mysqli_prepare($connect,"SELECT b.nombre, a.id_aprobacion, a.estado, a.observacion FROM aprobacion_informes as a, informe_refrigerador as b WHERE a.estado =1 AND a.id_informe = b.id_informe_refrigerador ORDER BY a.fecha_registro ASC");
mysqli_stmt_execute($query_1);
mysqli_stmt_store_result($query_1);
mysqli_stmt_bind_result($query_1, $nombre, $id_aprobacion, $estado, $observacion);

while($row = mysqli_stmt_fetch($query_1)){
	$aprobaciones[]=array(
	'nombre_informe'=>$nombre,
	'id_aprobado'=>$id_aprobacion,
	'estado'=>$estado,
	'observaciones'=>$observacion	
	);
	
}


$smarty->assign('aprobaciones',$aprobaciones);


//HEADER PRINCIPAL DONDE SE GUARDAN TODO LO QUE VA ENTRE LA ETIQUETA <HEAD></HEAD> DE HTML
$smarty->display("main_header.php"); 
//VALIDACIÓN SI EL USUARIO NO HA INICIADO SESIÓN SE MOSTRARÁ POR DEFECTO LA PÁGINA DE INICIO DE SESIÓN
echo "<input type='hidden' value='$mi_id' id='id_valida'>";


if($_COOKIE["user"]==false)
{
    header("refresh:0; url=mi_acceso.php");
}
//SI EL USUARIO ESTÁ LOGEADO SE MOSTRARÁ TODO EL CONTENIDO DE CERNET
else
{
  
  //IMPRESION DEL ENCABEZA DE LA PAGINA
  $smarty->display("header.php"); 
  //IMPRESIÓN DEL MÉNU IZQUIERDO EN ÉL SE REGISTRARAN TODOS LOS LINKS DE ACCESO	

  $module = $_REQUEST['module'];
  $page = $_REQUEST['page'];
  $certi = ($module == 13) ? 'mm-active' : '';
  $smarty->assign('certi', $certi);

  
  $smarty->display("left_menu.php"); 


  //INICIA EL CONTENIDO
  echo "
  <div class='app-main__outer'>
  <div class='app-main__inner' id='div_principal'>";
	
  
  ////////////////COMIENZO DE PRIVILEGIOS PARA MOSTRAR O NO PAGINAS//////////////////////
  if(isset($_GET['module'])){
    
    switch($_GET["module"])
      {
        
 /////////////////// GESTION DE MODULO DE GESTIÓN ///////////       
        case 1:
          if($_GET["page"] == 1 && $primer_modulo == 1){
            include("templates/modulo/modulo_gestion.php");
          }elseif ($_GET["page"] == 2 ){
            include("templates/informes/informes_clientes.php");
          }else{
            $smarty->display("error3.tpl");
          }
        break;
        
/////////////////////// GESTION DE CONTROL DE CAMBIO //////////// 
        case 2:
          if($segundo_modulo == 1){  
              if($_GET["page"] == 1){
                  include("templates/control_cambio/nuevo_control.php");
              }else if($_GET["page"] == 2){
                  include("templates/control_cambio/gestionar_control.php");
              }else if($_GET["page"] == 3){
                  include("templates/control_cambio/editar_control.php");  
              } 
          }
        break;
        
/////////////////////////// GESTION DE USUARIO ////////////////
        case 3:
          if($tercer_modulo == 1){
            if($_GET["page"] == 1){
             
              include("templates/usuario/nuevo_usuario.php");
            }else if($_GET["page"] == 2){
              include("templates/usuario/gestionar_usuario.php");
            }else if ($_GET["page"] == 3){
              
              include("templates/usuario/editar_usuario.php");
            }else if($_GET["page"] == 4){
             
              include("templates/usuario/historial_usuario.php");
            }else if($_GET["page"] == 5){
              
              include("templates/usuario/privilegio_rol.php");
            }
         }else if($tercer_modulo == 0 && $id_rol == 5){
            if($_GET["page"] == 6){
                include("templates/usuario/nombres_usuarios.php");
            }
          }
        break;

///////////////////////// GESTION DE CLIENTE /////////////////
        case 4:
          if($cuarto_modulo == 1){
            if($_GET["page"] == 1){
              include("templates/cliente/nuevo_cliente.php");
            }else if($_GET["page"] == 2){
              include("templates/cliente/gestionar_cliente.php");
            }else if($_GET["page"] == 3){
              include("templates/cliente/editar_cliente.php"); 
            }else if($_GET["page"] == 4){
              include("templates/cliente/historial_cliente.php");
            }
          }else if($cuarto_modulo == 1){
            if($_GET["page"] == 3){
              include("templates/cliente/editar_empresa_cliente.php");
            }else if($_GET["page"] == 4){
              include("templates/cliente/historial_cliente_cliente.php");
            }
          }
        break;

////////////////// GESTION PARA ACTUALIZAR EQUIPOS ///////////////////////        
        case 5:
          if($quinto_modulo == 1){
            if($_GET["page"] == 1){
              include("templates/item/nuevo_item.php");
            }else if($_GET["page"] == 2){
              include("templates/item/gestionar_item.php");
            }else if($_GET["page"] == 3){
              if($_GET["type"] == 1){
                include("templates/item/update_bodega.php");
              }else if($_GET["type"] == 2){
                include("templates/item/update_refrigerador.php");
              }else if($_GET["type"] == 3){
                include("templates/item/update_freezer.php");
              }else if($_GET["type"] == 4){
                include("templates/item/update_ultrafreezer.php");
              }else if($_GET["type"] == 5){
                include("templates/item/update_estufa.php");
              }else if($_GET["type"] == 6){
                include("templates/item/update_incubadora.php");
              }else if($_GET["type"] == 7){
                include("templates/item/update_automovil.php");
              }else if($_GET["type"] == 8){
                include("templates/item/update_sala_limpia.php");
              }else if($_GET["type"] == 11){
                include("templates/item/update_filtro.php");
              }else if($_GET["type"] == 12){
                include("templates/item/update_campana_extraccion.php");
              }else if($_GET["type"] == 13){
                include("templates/item/update_flujo_laminar.php");
              }else if($_GET["type"] == 14){
                include("templates/item/update_camara_congelada.php");
              }else if ($_GET["type"] == 15) {
                include("templates/item/update_aire_comprimido.php");
              }
            }else if($_GET["page"] == 4){
              include("templates/item/historial_item.php");
            }
          /*
          }else if($quinto_modulo == 1 && $id_rol == 4 or $id_rol == 3){
            if($_GET["page"] == 1){
              include("templates/item/nuevo_item_cliente.php");
            }else if($_GET["page"] == 3){
              if($_GET["type"] == 1){
                include("templates/item/update_bodega_cliente.php");
              }
            }*/
          }
        break;

/////////////////// GESTION DE OT ///////////////////
        case 6:
          if($sexto_modulo == 1){
            if($_GET["page"] == 1){
              include("templates/OT/nueva_ot.php");
            }else if($_GET["page"] == 2){
              include("templates/OT/gestionar_ot.php");
            }else if($_GET["page"] == 3){
              include("templates/OT/asignar_servicio_ot.php");
            }else if($_GET["page"] == 4){
              include("templates/OT/historial_ot.php"); 
            }
          }
        break;

///////////// GESTION PARA SERVICIOS ///////////////////////
        case 7:
          if($septimo_modulo == 1){
            if($_GET["page"] == 1){
              include("templates/servicio/nuevo_servicio.php");
            }
          }
        break;

/////////////////////// GESTIÓN DE INFORMES ////////////////////// 
        case 8: 
          if($octavo_modulo == 1){
            if($_GET["page"] == 1){
              include("templates/mapeos_generales/gestionar_informes.php");
            }else if($_GET["page"] == 2){
              include("templates/refrigeradores/gestionar_informes.php");
            }else if($_GET["page"] == 3){
              include("templates/ultrafreezer/gestionar_informes.php");
            }else if($_GET["page"] == 4){
              include("templates/freezer/gestionar_informes.php");
            }else if($_GET["page"] == 5){
              include("templates/estufaeincubadora/gestionar_informes.php");
            }else if($_GET["page"] == 6){
              include("templates/automovil/gestionar_informes.php");
            }else if ($_GET["page"] == 7){
              include("templates/filtros/gestionar_informes.php");
            }else if ($_GET["page"] == 8){
              include("templates/campana_extraccion/gestionar_informes.php");
            }else if ($_GET["page"] == 9){
              include("templates/Calificacion/gestionar_calificaciones.php");
            }

            else if($_GET["page"] == 10){
               if($_GET["type"] == 1){
                include("templates/mapeos_generales/datos_informe_mapeo.php");
              }else if($_GET["type"] == 2){
                include("templates/refrigeradores/informes/datos_informe_mapeo.php");
              }else if($_GET["type"] == 3){
                include("templates/freezer/datos_informe_mapeo.php");
              }else if($_GET["type"] == 4){
                include("templates/ultrafreezer/datos_informe_mapeo.php");
              }else if($_GET["type"] == 5){
                include("templates/estufaeincubadora/datos_informe_mapeo.php");
              }else if($_GET["type"] == 6){
                include("templates/automovil/datos_informe_mapeo.php");
              }else if($_GET["type"] == 7){
                include("templates/filtros/datos_informe_mapeo.php");
              }else if($_GET["type"] == 8){
                include("templates/campana_extraccion/datos_informe_mapeo.php");
              }else if($_GET["type"] == 9){
                include("templates/protocolos/datos_protocolos.php");
              }else if($_GET["type"] == 10){
                include("templates/flujo_laminar/datos_informe_mapeo.php");
              }else if($_GET["type"] == 11){
                include("templates/sala_limpia/datos_informe_mapeo.php");
              }else if($_GET["type"] == 12){
               include("templates/mapeos_generales/datos_informe_mapeo.php");
              }else if($_GET["type"]==13){
                include("templates/Calificacion/datos_calificacion.php");
              }else if ($_GET['type']==14) {
                include("templates/aire_comprimido/datos_informe_mapeo.php");
              }
              
      
            }else if($_GET['page'] == 11 ){
              include("templates/flujo_laminar/gestionar_informe.php");
            }else if($_GET['page'] == 12 ){
              include("templates/sala_limpia/gestionar_informe.php");
            }else if($_GET['page'] == 13 ){
              include("templates/URS/gestionar_informe.php");
            }else if ($_GET['page'] == 14) {
              include("templates/aire_comprimido/gestionar_informe.php");
            }
          }
       break;

///////////////// GESTION DE DOCUMENTACIÓN //////////////////////
       case 9:
        if($noveno_modulo == 1){
          if($_GET["page"] == 1){
            include("templates/documentacion/inicio_documentacion.php");
          }else if($_GET["page"] == 2){
            include("templates/documentacion/head_templates/administracion_documentacion.php"); 
          }
        }
      break;

        
//////////////  GESTION DE CARGOS
      case 10:
        if($decimo_modulo == 1){
          if($_GET["page"] == 1){
            include("templates/cargos/inicio_cargos.php");
          }
        }   
      break;


/////////PERFIL///////////////

      case 11:
       if (isset($_GET['id_user'])) {
        include("templates/perfil/perfil_usuario.php");
       }
        break;

//////GESTIONAR EQUIPOS//////////////////
        case 12:
        if (isset($_GET['page']) && $_GET['page'] == 1) {
          include("templates/equipos_cercal/update_equipo.php");   
        }else{
          include("templates/equipos_cercal/gestionar_equipos.php");
        }
        
        break;

        case 13:
          if(isset($_GET['page']) && $_GET['page']==1){
            include('templates/metrologia/gestion_equipos.php');
          }else if(isset($_GET['page']) && $_GET['page']==2){
            include('templates/metrologia/gestion_certificados.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==3){
            include('templates/metrologia/sensores.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==4){
            include('templates/metrologia/certificados.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==5){
            include('templates/metrologia/certificados_add.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==6){
            include('templates/metrologia/certificados_editar.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==7){
            include('templates/metrologia/certificados_ver.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==8){
            include('templates/metrologia/sensor_add.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==9){
            include('templates/metrologia/sensor_editar.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==10){
            include('templates/metrologia/sensor_var.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==11){
            include('templates/metrologia/certificados_mass.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==12){
            include('templates/metrologia/carga_masiva.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==13){
            include('templates/metrologia/mantenimiento_ver.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==14){
            include('templates/metrologia/sensores.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==15){
            include('templates/metrologia/sensores_mass.php');
          }
          else if(isset($_GET['page']) && $_GET['page']==16){
            include('templates/metrologia/certificados_mass_múltiple_sensores.php');
          }
          break;

/////////////// CASE PARA LA MANIPULACIÓN DE LAS CALIFICACIONES        
      case "Calificacion":
        if($_GET['type']=="URS"){
          include("templates/Calificacion/URS/index.php");
        }else if($_GET['type']=="DQ"){
          include("templates/Calificacion/DQ/index.php");
        }
        break;
/////////////// CASE APROBACIÓN DE INFORMES        
case 14:
  if(isset($_GET['page']) && $_GET['page']==1){
    include('templates/aprobacion_informes/gestionar_aprobacion_informes.php');
  }
  break;      
   }//FIN DEL SWITCH

}else if(isset($_GET['clave'])){
    
  switch($_GET['parameter']){
    case 2:
      if($id_privilegio != 7){
        include("templates/documentacion/añadir_participantes.php");
        ?>
        <script>
          Swal.fire({
            title:'Hola'
          })

        </script>
        <?php
      }else{
        ?>
        <script>
          Swal.fire({
            title:'Hola'
          })

        </script>
        <?php
      }
      
    break;
      
    case 3:
      include("templates/documentacion/gestor_documentacion.php");
    break;  
  }
    
}else{

    include("dashboard_cliente.php");
  
}



  
echo "
</div>
</div>
<div class='app-drawer-overlay d-none animated fadeIn'></div>";
$smarty->display("footer.php"); 		
$smarty->display("right_menu.php"); 	
}

//DECLARACIÓN DEL PIE DE PÁGINA, TODAS LAS BIBLIOTECAS SCRIPT REQUERIDAS 
//PARA EL FUNCIONAMIENTO DE CERNET, DEBEN DE ESTAR ALOJADAS EN DICHO ARCHIVO
$smarty->display("main_footer.php"); 
mysqli_close($connect); 
ob_end_flush();
?>
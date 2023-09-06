<?php


//error_reporting(E_ALL);
//ini_set('display_errors', 1);

 $current_page = $_SERVER['REQUEST_URI'];
 if (isset($_REQUEST["action2"]) && $_REQUEST["action2"] == "delete" && !empty($_REQUEST["id"])) {
  $sql = "DELETE FROM sensores_certificados WHERE `id_certificado`='" . $db_cms->removeQuote($_REQUEST["id"]) . "'";
  $res = $db_cms->delete_query($sql);
  if ($res != FALSE) {

    // Construct the description for the backtrack record
    $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
    $action = "Eliminó"; // Example action
    $date_time_action = date('Y-m-d H:i:s'); // Current date and time


// Add more fields and values as needed
$field1 = "Id Certificado";
$field1_value = $_REQUEST["id"];
$field6 = "Página";
$field6_value = "GESTIÓN CERTIFICADOS";

$description = "$user ha $action el $date_time_action<br>"
. "$field1 - $field1_value<br>"
. "$field6 - $field6_value<br>";

$description_base64 = base64_encode($description);

$backtrack_data = array(
  'fecha' => $date_time_action,
  'persona' =>  $_COOKIE['myid'],
  'movimiento' => $action,
  'modulo' => "Metrologia",
  'descripcion' => $description_base64
);

$res_backtrack = $db_cms->add_query($backtrack_data,'backtrack');



      $_SESSION["cms_status"] = "success";
      $_SESSION["cms_msg"] = "¡Borrado exitosamente!";
  } else {
      $_SESSION["cms_status"] = "error";
      $_SESSION["cms_msg"] = "¡No se puede eliminar!";
  }

  // Remove the 'action2' and 'id' parameters from the current URL
  $current_page = $_SERVER['REQUEST_URI'];
  $modified_url = preg_replace('/([?&])action2=delete(&|$)/', '$2', $current_page);
  $modified_url = preg_replace('/([?&])id=\d+(&|$)/', '$2', $modified_url);

  // Perform the redirect
  header('Location: ' . $modified_url);
  exit();
}
$baseurl = getBaseURL();

 


 
 

?>
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h2>GESTIÓN CERTIFICADOS</h2>
        </div>

        
        <div class="err">
                            <?php
                            if (!empty($_SESSION["cms_status"]) && !empty($_SESSION["cms_msg"])) {
                              $status_class = ($_SESSION["cms_status"] == 'error') ? 'status_msg_error' : 'status_msg_success';
                              ?>
                              <div class="<?php echo $status_class; ?>">
                                  <?php echo $_SESSION["cms_msg"]; ?>
                              </div>
                              <?php
                              unset($_SESSION["cms_status"]);
                              unset($_SESSION["cms_msg"]);
                          }
                          
                            ?>
                        </div>

                        <div class="card-body d-flex justify-content-end">
    <div class="wideget-user">
    <!-- <a href="index.php?module=13&page=5&amp;s="  > <button id="hello" type="button" class="btn btn-radius btn-success">Agregar Certificado</button>	
                        </a></div> -->
  
    <a href="index.php?module=13&page=8&amp;s="  > <button id="hello" type="button" class="btn btn-radius btn-success">Agregar Sensores</button>	
                        </a></div>
</div>

        <div class="card-body">
        <table id="certificados" style="width: 100%; text-align:center;" id="example" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>    
                    <td><strong>Id Sensor</strong></td>              
                    <td><strong>Sensor</strong></td>
                    <td><strong>No. Serie</strong></td>
                    <td><strong>Estado</strong></td>
                    <td><strong>Id Certificado</strong></td>                    
                    <td><strong>Certificado</strong></td>
                    <td><strong>Vence el</strong></td>
                    <td><strong>Días</strong></td>                      
                    <td><strong>Acciones</strong></td>
                </tr>
                </thead>
            <tbody>                
            </tbody>
        </table>
        </div>
    </div>
</div>
 
<div class="modal fade" id="modal-default" tabindex="-1" role="dialog" aria-labelledby="modalConfirmLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalConfirmLabel">Confirmar eliminación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      ¿Estás seguro de que quieres eliminar este registro?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <a href="#" id="del_proceed" class="btn btn-danger">Proceder</a>
        
      </div>
    </div>
  </div>
</div>

 
  <?php


//include_once("sensores.php");

?>

 
	 
    <script>
$(document).ready(function() {
 
  $('#certificados').dataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    'aaSorting': [[4, 'desc']],
      "columnDefs": [
         { "orderable": true, "targets": "_all" } // Make all columns orderable
      ],
    'ajax': {
      'url': 'templates/metrologia/includes/ajax_fetch_data.php',
      'type': 'POST',
      'data': function(d) {
        // Add additional data to the AJAX request        
        d.pagename = "certifacados";        
        d.fields = "id_sensor,Sensor,noSerie,Estado,id_certificado,Certificado,Vence el,Días";
        d.table_name ="sensores_certificados";
        d.baseurl="<?=$baseurl?>";
        d.whereCondition  = "";
      }
    },
    'columns': [
      {data: 'id_sensor'},
      {data: 'Sensor'},
      {data: 'noSerie'},
      {data: 'Estado'},
      {data: 'id_certificado'},     
      {data: 'Certificado'},
      {data: 'Vence el'},
      {data: 'Días'},
      {data: 'action'}
    ]
  });
  $(document).on('click', '.delete_process', function() {
    // Retrieve the data-delete-id attribute value
    var deleteId = $(this).data('delete-id');

    // Display the modal
    //$('#modal-default').modal('show');    
    
    // Set the action when the proceed button is clicked in the modal
    
    $('#del_proceed').attr('href', '?module=13&page=4&s=-&action2=delete&id=' + deleteId);

    

    // Prevent the default action of the delete button (href="javascript:void(0);")
    return false;
});

$(document).on('click', '#del_proceed', function() {
    // Get the href attribute value of the 'del_proceed' button
    var href = $(this).attr('href');

    // Perform the delete action by redirecting to the specified URL
    window.location.href = href;
  });

 
 
});
  
</script>

<script type="text/javascript" src="scripts/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="scripts/dataTables.bootstrap.min.js"></script>
<!-- <link rel="stylesheet" href="scripts/jquery.dataTables.min.css"> -->

	

<style>
.dataTables_wrapper {
    position: relative;
    clear: both;
    display: block !important; 
}

.modal-backdrop.show, .show.blockOverlay, .modal-backdrop, .blockOverlay {   
    position: inherit;
}
.modal {
     
    top: 50px;
}

#sensores_wrapper .btn, #certificados_wrapper .btn  {
     
     font-size:11px;
 }
  </style>
 

   
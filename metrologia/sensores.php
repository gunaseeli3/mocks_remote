<?php

 
 $current_page = $_SERVER['REQUEST_URI'];
 if (isset($_REQUEST["action3"]) && $_REQUEST["action3"] == "delete" && !empty($_REQUEST["id"])) {
  $sql = "DELETE FROM sensores WHERE `id_sensor`='" . $db_cms->removeQuote($_REQUEST["id"]) . "'";
   $res = $db_cms->delete_query($sql);
  if ($res != FALSE) {

     // Construct the description for the backtrack record
     $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
     $action = "Eliminó"; // Example action
     $date_time_action = date('Y-m-d H:i:s'); // Current date and time


// Add more fields and values as needed
$field1 = "Sensor ID";
$field1_value = $_REQUEST["id"];
$field6 = "Página";
$field6_value = "SENSOR";

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



      $_SESSION["cms_status2"] = "success";
      $_SESSION["cms_msg2"] = "¡Borrado exitosamente!";
  } else {
      $_SESSION["cms_status2"] = "error";
      $_SESSION["cms_msg2"] = "¡No se puede eliminar!";
  }


  

  // Remove the 'action3' and 'id' parameters from the current URL
  $current_page = $_SERVER['REQUEST_URI'];
  $modified_url = preg_replace('/([?&])action3=delete(&|$)/', '$2', $current_page);
  $modified_url = preg_replace('/([?&])id=\d+(&|$)/', '$2', $modified_url);

  // Perform the redirect
  header('Location: ' . $modified_url);
  exit();
}

?>
<div class="col-sm-12" style="margin-top:10px;">
    <div class="card">
        <div class="card-header">
            <h2>GESTIÓN DE SENSORES DE METROLOGÍA</h2>
        </div>


        <div class="err">
          
                            <?php
                            if (!empty($_SESSION["cms_status2"]) && !empty($_SESSION["cms_msg2"])) {
                              $status_class = ($_SESSION["cms_status2"] == 'error') ? 'status_msg_error' : 'status_msg_success';
                              ?>
                              <div class="<?php echo $status_class; ?>">
                                  <?php echo $_SESSION["cms_msg2"]; ?>
                              </div>
                              <?php
                              unset($_SESSION["cms_status2"]);
                              unset($_SESSION["cms_msg2"]);
                          }
                          
                            ?>
                        </div>
                        <div class="card-body d-flex justify-content-end">
    <div class="wideget-user">
    <a href="index.php?module=13&page=8&amp;s="  > <button id="hello" type="button" class="btn btn-radius btn-success">Agregar Sensores</button>	
                        </a></div>
</div>
        <div class="card-body sensores">
        
        <table id="sensores" style="width: 100%; text-align:center;" id="example" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                
                <td><strong>Id Sensor</strong></td>
                    <td><strong>Nombre</strong></td>
                    <td><strong>Número de serie</strong></td>
                    <td><strong>Tipo de sensor</strong></td>
                    <td><strong>Estado</strong></td>
                    <td><strong>País</strong></td> 
                    <td><strong>Acciones</strong></td>
                </tr>
                </thead>
            <tbody>                
            </tbody>
        </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default2" tabindex="-1" role="dialog" aria-labelledby="modalConfirmLabel" aria-hidden="true">
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
        <a href="#" id="del_proceed2" class="btn btn-danger">Proceder</a>
      </div>
    </div>
  </div>
</div>
<!-- jQuery -->
 
 
	 
<script>
$(document).ready(function() {

  var $columnIndex = 0;
  var $columnName = 'nombre';
 
  $('#sensores').dataTable({
    'processing': true,
    'serverSide': true,
    'aaSorting': [[0, 'desc']],
    'serverMethod': 'post', 
       
    "columnDefs": [
        { "orderable": true, "targets": "_all" }  // Make all columns orderable
        
    ],
    'ajax': {
      'url': 'templates/metrologia/includes/ajax_fetch_data_sensor.php',
        'type': 'POST',
        'data': function(d) {
            // Add additional data to the AJAX request
            d.pagename = "sensores";
            d.fields = "id_sensor,nombre,serie,tipo,estado,pais";
            d.table_name = "sensores";
            d.whereCondition = "";
 
             
        }
    },
    'columns': [
      {data: 'id_sensor'},
        {data: 'nombre'},
        {data: 'serie'},
        {data: 'tipo'},
        {data: 'estado'},
        {data: 'pais'},
        {data: 'action'}
    ]
});


$(document).on('click', '.delete_process_sensor', function() {
    // Retrieve the data-delete-id attribute value
    var deleteId = $(this).data('delete-id');

    // Display the modal
    //$('#modal-default').modal('show');    
    
    // Set the action when the proceed button is clicked in the modal
    
    $('#del_proceed2').attr('href', '?module=13&page=4&s=-&action3=delete&id=' + deleteId);

    

    // Prevent the default action of the delete button (href="javascript:void(0);")
    return false;
});

$(document).on('click', '#del_proceed2', function() {
    // Get the href attribute value of the 'del_proceed2' button
    var href = $(this).attr('href');

    // Perform the delete action by redirecting to the specified URL
    window.location.href = href;
  });

});
  
</script>
<style>
.sensores .row .row {     display: inline !important; width: 100%; }
.modal-backdrop.show, .show.blockOverlay, .modal-backdrop, .blockOverlay {   
    position: inherit;
}
.modal {
     
    top: 50px;
}

  </style>

 
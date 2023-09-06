<?php
//$mi_sensor=$array_nombres[$sensor];

$table_name='sensores';
 


if (!empty($_POST["submit_action"]) && !empty($_POST["add_action"])) {
    $field = array();

    $fields_to_check = array("nombre", "serie", "tipo", "pais", "estado");
    foreach ($fields_to_check as $field_name) {
        if (!empty($_POST[$field_name])) {
            $field[$field_name] = $_POST[$field_name];
        }
    }

    $res = $db_cms->add_query($field, $table_name);

    if ($res !== FALSE) {
            
        // Construct the description for the backtrack record
                      
        $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
        $action = "Creó"; // Example action
        $date_time_action = date('Y-m-d H:i:s'); // Current date and time

        // Add more fields and values as needed
        $field1 = "Identificación del sensor";
        $field1_value = $_POST['nombre'];
        $field2 = "Número de serie";
        $field2_value = $_POST['serie'];
        $field3 = "Tipo de sensor";
        $field3_value = $_POST['tipo'];
        $field4 = "Pais";
        $field4_value = $_POST['pais'];
        $field5 = "Estado";
        $field5_value = $_POST['estado'];
        $field6 = "Página";
        $field6_value = "AÑADIR SENSOR";

        $description = "$user has $action on $date_time_action <br>"
            . "$field1 - $field1_value<br>"
            . "$field2 - $field2_value<br>"
            . "$field3 - $field3_value<br>"
            . "$field4 - $field4_value<br>"
            . "$field5 - $field5_value<br>"
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
        if (!empty($_POST["add_action"])) {
            $_SESSION["cms_msg"] = "Datos modificados con éxito";
        } else {
            header('Location:' . $current_page);
            exit();
        }
    } else {
        $_SESSION["cms_status"] = "error";
        $_SESSION["cms_msg"] = "no actualizado";
    }
}

 

?>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>AÑADIR sensor </h2>
                <div class="btn-actions-pane-right">
                    <a href="index.php?module=13&page=4&s=-" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x"></i> Cancelar</a>
                    <a href="index.php?module=13&page=12"><button class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">Cargas masivas de sensores</button></a>

                </div>
            </div>
            <div class="card-body">
            <div class="err">
            <?php
if (!empty($_SESSION["cms_status"]) && !empty($_SESSION["cms_msg"])) {
    $statusClass = ($_SESSION["cms_status"] === 'error') ? 'error' : 'success';
    ?>
    <div class="status_msg_<?php echo $statusClass; ?>">
        <?php
        echo $_SESSION["cms_msg"];
        ?>
    </div>
    <?php
    unset($_SESSION["cms_status"]);
    unset($_SESSION["cms_msg"]);
}
?>

                        </div>
            <form method="post" enctype="multipart/form-data">
                <input name="s" type="hidden" value="<?php echo $_REQUEST['s'];?>">
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Identificación del sensor</label><input type="text" class="form-control" name="nombre" required value=""></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Número de serie</label><input type="text" class="form-control" name="serie" required value=""></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="">Tipo de sensor</label>
                                <?php
$options = array('Temperatura', 'Temp. y Hum.', 'Termocupla');
?>

<select class="form-control"  name="tipo">
  <?php foreach ($options as $option): ?>
    <option value="<?php echo $option; ?>" ><?php echo $option; ?></option>
  <?php endforeach; ?>
</select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">País</label><input type="text" class="form-control" name="pais" required value=""></div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Estado</label><input type="text" class="form-control" name="estado" required  value=""></div>
                        </div>
                    </div>
                    <div style="text-align:center;">
                         
                        <input type="hidden" name="add_action" value="1"/>
					 		<button type="submit" name="submit_action" value="Edit Category" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">Actualizar</button>
                    </div>
                </form>
                <br><br>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<style>
/* CSS class for error messages */
.status_msg_error {
  background-color: #f44336; /* Red background color */
  color: #fff; /* White text color */
  padding: 10px;
  border-radius: 5px;     margin-bottom: 20px;
}

/* CSS class for success messages */
.status_msg_success {
  background-color: #4caf50; /* Green background color */
  color: #fff; /* White text color */
  padding: 10px;
  border-radius: 5px;     margin-bottom: 20px;
}

    </style>


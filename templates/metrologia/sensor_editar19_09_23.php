<?php
 if (isset($_REQUEST["s"]) && $_REQUEST["s"] != '') {
    $sensor_existid = $_REQUEST["s"];
    $sql3 = "SELECT * FROM sensores WHERE id_sensor='$sensor_existid'";
    $res3 = $db_cms->select_query_with_row($sql3);

    $mi_sensor = $res3['nombre'];
}

$table_name='sensores';
$cid=$_REQUEST['s'];

$sql3 = "SELECT * FROM sensores WHERE id_sensor = '$cid'";
    $res_bf = $db_cms->select_query_with_row($sql3);


    error_reporting(E_ALL);
    ini_set('display_errors', 1);

if (!empty($_POST["submit_action"]) && !empty($_POST["edit_action"])) {
    $fields_to_check = array("nombre", "serie", "tipo", "pais", "estado");
    $field = array();

    foreach ($fields_to_check as $field_name) {
        if (!empty($_POST[$field_name])) {
            $field[$field_name] = $_POST[$field_name];
        }
    }


    if (!empty($_POST["edit_action"])) {
        $where = " WHERE id_sensor='" . $db_cms->removeQuote($_POST["s"]) . "'";
        $res = $db_cms->update_query_new($field, $table_name, $where);
    }

    if ($res !== FALSE) {

        // Construct the description for the backtrack record
        $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
        $action = "Modificó"; // Example action
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
$field6_value = "EDITAR SENSOR";

$description = "$user ha $action el $date_time_action<br>"
. "$field1 cambio de {$res_bf['nombre']} a $field1_value<br>"
. "$field2 cambio de {$res_bf['serie']} a $field2_value<br>"
. "$field3 cambio de {$res_bf['tipo']} a $field3_value<br>"
. "$field4 cambio de {$res_bf['pais']} a $field4_value<br>"
. "$field5 cambio de {$res_bf['estado']} a $field5_value<br>"
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
        if (!empty($_POST["edit_action"])) {
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

$sql="SELECT * FROM sensores WHERE id_sensor='$cid'"; 
$res=$db_cms->select_query_with_row($sql);


?>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>Editar SENSOR <?php echo $mi_sensor; ?></h2>
                <div class="btn-actions-pane-right">
                    <a href="index.php?module=13&page=4&s=-" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x"></i> Cancelar</a>
                </div>
            </div>
            <div class="card-body">
            <div class="err">
                            <?php
                            if(!empty($_SESSION["cms_status"]) && !empty($_SESSION["cms_msg"])){
                                switch($_SESSION["cms_status"]){
                                    case 'error':
                                        ?>
                                        <div class="status_msg_error">
                                            <?php
                                            echo $_SESSION["cms_msg"];
                                            ?>
                                        </div>
                                        <?php
                                        break;
                                    case 'success':
                                        ?>
                                        <div class="status_msg_success">
                                            <?php
                                            echo $_SESSION["cms_msg"];
                                            ?>
                                        </div>
                                        <?php
                                        break;
                                }
                                unset($_SESSION["cms_status"]);
                                unset($_SESSION["cms_msg"]);
                            }
                            ?>
                        </div>
            <form method="post" enctype="multipart/form-data">
                <input name="s" type="hidden" value="<?php echo $_REQUEST['s'];?>">
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Identificación del sensor</label><input type="text" class="form-control" name="nombre" required value="<?php echo $res['nombre'];?>"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Número de serie</label><input type="text" class="form-control" name="serie" required value="<?php echo $res['serie'];?>"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="">Tipo de sensor</label>
                                <?php
$options = array('Temperatura', 'Temp. y Hum.', 'Termocupla');
?>

<select class="form-control"  name="tipo">
  <?php foreach ($options as $option): ?>
    <option value="<?php echo $option; ?>" <?php if ($res['tipo'] == $option) echo 'selected'; ?>><?php echo $option; ?></option>
  <?php endforeach; ?>
</select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">País</label><input type="text" class="form-control" name="pais" value="<?php echo $res['pais'];?>"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Estado</label><input type="text" class="form-control" name="estado"  value="<?php echo $res['estado'];?>"></div>
                        </div>
                    </div>
                    <div style="text-align:center;">
                         
                        <input type="hidden" name="edit_action" value="1"/>
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


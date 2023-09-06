<?php 

include('../../config.ini.php');

$movimiento = $_POST['movimiento'];

if($movimiento == "listar_equipos"){

    $tipo_equipo = $_POST['tipo_equipo'];

    if($tipo_equipo == "sensores"){

        $array_equipos = array();

        $consultando_sensores = mysqli_prepare($connect,"SELECT id_sensor, nombre, CASE WHEN estado is null or estado = '' THEN 'Sin asignar' ELSE estado END as estado, serie, tipo FROM sensores ORDER BY nombre ASC");
        mysqli_stmt_execute($consultando_sensores);
        mysqli_stmt_store_result($consultando_sensores);
        mysqli_stmt_bind_result($consultando_sensores, $id_sensor, $nombre, $estado, $serie, $tipo);

        while($row = mysqli_stmt_fetch($consultando_sensores)){

            $array_equipos[]=array(
                'id_sensor'=>$id_sensor,
                'nombre'=>$nombre,
                'estado'=>$estado,
                'serie'=>$serie,
                'tipo'=>$tipo
            );
        }

        $convert = json_encode($array_equipos);
        echo $convert;
    }
}


else if($movimiento == "traer_certificados"){

    $id_sensor = $_POST['id_sensor'];
    $array_certifcados = array();

    $consultar_certificado = mysqli_prepare($connect,"SELECT id_certificado, certificado, fecha_emision, fecha_vencimiento, CASE WHEN fecha_vencimiento < DATE_FORMAT(NOW(), '%Y-%m-%d') THEN 'Vencido' ELSE 'Vigente' END AS Estado, pais FROM sensores_certificados WHERE id_sensor = ?");
    mysqli_stmt_bind_param($consultar_certificado, 'i', $id_sensor);
    mysqli_stmt_execute($consultar_certificado);
    mysqli_stmt_store_result($consultar_certificado);
    mysqli_stmt_bind_result($consultar_certificado, $id_certificado, $certificado, $calibracion, $vencimiento, $estado_certificado, $pais);

    while($row = mysqli_stmt_fetch($consultar_certificado)){

        $array_certifcados [] = array(

            'id_certificado'=>$id_certificado,
            'certificado'=>$certificado,
            'calibracion'=>$calibracion,
            'vencimiento'=>$vencimiento,
            'estado_certificado'=>$estado_certificado,
            'pais'=>$pais
        );
    }

    $convert = json_encode($array_certifcados);

    echo $convert;
}


else if($movimiento == "search_sensor"){

    $que_buscar = $_POST['que_buscar'];

    $buscar = mysqli_prepare($connect,"SELECT id_sensor, nombre, CASE WHEN estado is null or estado = '' THEN 'Sin asignar' ELSE estado END as estado FROM sensores WHERE nombre LIKE CONCAT('%',?,'%') ORDER BY nombre ASC");
    mysqli_stmt_bind_param($buscar, 's', $que_buscar);
    mysqli_stmt_execute($buscar);
    mysqli_stmt_store_result($buscar);
    mysqli_stmt_bind_result($buscar, $id_sensor, $nombre, $estado);
    

    while($row = mysqli_stmt_fetch($buscar)){

        $array_equipos[]=array(
            'id_sensor'=>$id_sensor,
            'nombre'=>$nombre,
            'estado'=>$estado
        );
    }

    $convert = json_encode($array_equipos);
    echo $convert;

}

else if($movimiento == "eliminar_sensor"){

    $id_sensor = $_POST['id_sensor'];


    $validacion = mysqli_prepare($connect,"SELECT id_sensor_mapeo FROM mapeo_general_sensor WHERE id_sensor = ?");
    mysqli_stmt_bind_param($validacion, 'i', $id_sensor);
    mysqli_stmt_execute($validacion);
    mysqli_stmt_store_result($validacion);
    mysqli_stmt_bind_result($validacion, $id_sensor_mapeo);
    mysqli_stmt_fetch($validacion);

    if(mysqli_stmt_num_rows($validacion)>0){
        echo "No se puede";
    }else{

        $eliminar_certificado_1 = mysqli_prepare($connect,"SELECT id_certificado FROM sensores_certificados WHERE id_sensor = ?");
        mysqli_stmt_bind_param($eliminar_certificado_1, 'i', $id_sensor);
        mysqli_stmt_execute($eliminar_certificado_1);
        mysqli_stmt_store_result($eliminar_certificado_1);
        mysqli_stmt_bind_result($eliminar_certificado_1, $id_certificado);


        while($row = mysqli_stmt_fetch($eliminar_certificado_1)){

            $borrando_certificados = mysqli_prepare($connect,"DELETE FROM sensores_certificados WHERE id_certificado = ?");
            mysqli_stmt_bind_param($borrando_certificados, 'i', $id_certificado);
            mysqli_stmt_execute($borrando_certificados);
            echo mysqli_stmt_error($borrando_certificados);

        }


      
        $borrando_sensor = mysqli_prepare($connect,"DELETE FROM sensores WHERE id_sensor = ?");
        mysqli_stmt_bind_param($borrando_sensor, 'i', $id_sensor);
        mysqli_stmt_execute($borrando_sensor);
        echo mysqli_stmt_error($borrando_sensor);

    }    
}



else if ($movimiento == "guardar_configuracion"){

    $name_sensor = $_POST['name_sensor'];
    $serie_sensor = $_POST['serie_sensor'];
    $tipo_sensor = $_POST['tipo_sensor'];
   

    $numero_certificado = $_POST['numero_certificado'];
    $fecha_calibracion = $_POST['fecha_calibracion'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $id_sensor = $_POST['id_sensor'];
    $id_sensor_certificado = $_POST['id_sensor_certificado'];
    $pais_certificado = $_POST['pais_certificado'];

        
    $consultar_si_se_utiliza = mysqli_prepare($connect,"SELECT id_sensor_mapeo FROM mapeo_general_sensor WHERE id_sensor = ?");
    mysqli_stmt_bind_param($consultar_si_se_utiliza, 'i', $id_sensor);
    mysqli_stmt_execute($consultar_si_se_utiliza);
    mysqli_stmt_store_result($consultar_si_se_utiliza);
    mysqli_stmt_bind_result($consultar_si_se_utiliza, $id_sensor_mapeo);
    mysqli_stmt_fetch($consultar_si_se_utiliza);

    if(mysqli_stmt_num_rows($consultar_si_se_utiliza)>0){
        echo "No se puede";
    }else{

        for($i=0;$i<=count($consultar_si_se_utiliza);$i++){

            $actualizando_certificados = mysqli_prepare($connect,"UPDATE sensores_certificados SET certificado = ?, fecha_emision=?, fecha_vencimiento = ?, pais = ? WHERE id_certificado = ?");
            mysqli_stmt_bind_param($actualizando_certificados,'ssssi', $numero_certificado[$i], $fecha_calibracion[$i], $fecha_vencimiento[$i], $pais_certificado[$i], $id_sensor_certificado[$i]);
            mysqli_stmt_execute($actualizando_certificados);
            echo mysqli_stmt_error($actualizando_certificados);
        }
        
        $actualizando_sensor = mysqli_prepare($connect,"UPDATE sensores SET nombre = ?, serie = ?, tipo = ? WHERE id_sensor = ?");
        mysqli_stmt_bind_param($actualizando_sensor, 'sssi', $name_sensor, $serie_sensor, $tipo_sensor, $id_sensor);
        mysqli_stmt_execute($actualizando_sensor);
        echo mysqli_stmt_error($actualizando_sensor);
    }

}

else {

    $id_sensor = $_POST['id_sensor'];
    $array_sensor = array();



    $consultar_info_sensor = mysqli_prepare($connect,"SELECT serie, tipo FROM sensores WHERE id_sensor = ?");
    mysqli_stmt_bind_param($consultar_info_sensor, 'i', $id_sensor);
    mysqli_stmt_execute($consultar_info_sensor);
    mysqli_stmt_store_result($consultar_info_sensor);
    mysqli_stmt_bind_result($consultar_info_sensor, $serie, $tipo);

    while($row = mysqli_stmt_fetch($consultar_info_sensor)){

        $array_sensor[]=array(
            'serie'=>$serie,
            'tipo'=>$tipo
        );
    }

    $convert = json_encode($array_sensor);
    echo $convert;
}

?>
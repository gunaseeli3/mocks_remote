<?php
error_reporting(0);
ob_start();
session_start();
 
 require_once dirname(__FILE__) . '/../../../config.ini.php';
 

// Reading values
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows displayed per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc



 
$table_name = $_POST['table_name'];
$pagename = $_POST['pagename'];
$fields = $_POST['fields'];
$baseurl = $_POST['baseurl'];

$whereCondition  = $_POST['whereCondition'];

$isdate  = $_POST['isdate'];
$datename  = $_POST['datename'];
$dateformat  = $_POST['dateformat'];
 

 


// Search
$searchValue = $_POST['search']['value']; // Search value

if($columnName=='Vence el') {$columnName ='`Vence el`';}
 
$searchArray = array();
$searchQuery = "";
 
// Total number of records without filtering
$records = $db_cms->select_query_with_row("SELECT COUNT(DISTINCT s.id_sensor) AS allcount FROM sensores s");
$totalRecords = $records['allcount'];

// Total number of records with filtering
$whereCondition = ""; // Initialize the WHERE condition
if (isset($_POST['whereCondition']) && $_POST['whereCondition'] != '') {
    $whereCondition = " AND " . $_POST['whereCondition'];
}

if ($pagename == 'certifacados') {
    $query = "SELECT 
       sc.id_certificado,
       s.id_sensor,
       s.nombre AS Sensor,
       COALESCE(s.pais, '-') AS pais,
       COALESCE(s.serie, '-') AS 'noSerie',
       COALESCE(sc.estado, '-') AS Estado,
       COALESCE(sc.certificado, '-') AS Certificado,
       COALESCE(DATE_FORMAT(sc.fecha_vencimiento, '%d-%m-%Y'), '-') AS 'Vence el',
       COALESCE(DATEDIFF(sc.fecha_vencimiento, CURDATE()), '-') AS Días,
       COALESCE(DATEDIFF(sc.fecha_vencimiento, CURDATE()), 999) AS dias_order
    FROM 
       sensores s
    LEFT JOIN 
       sensores_certificados sc ON s.id_sensor = sc.id_sensor  
    WHERE 1 ";

    if ($searchValue != '') {
        $query .= " AND (sc.id_certificado LIKE '%" . $searchValue . "%' OR s.id_sensor LIKE '%" . $searchValue . "%' OR s.nombre LIKE '%" . $searchValue . "%' OR s.serie LIKE '%" . $searchValue . "%' OR sc.estado LIKE '%" . $searchValue . "%' OR sc.certificado LIKE '%" . $searchValue . "%' OR sc.fecha_vencimiento LIKE '%" . $searchValue . "%') ";
    }

       $query .= $whereCondition . " group by s.id_sensor ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage;
}

 else {
    $query = "SELECT * FROM $table_name WHERE 1 ";
    if ($searchQuery != '') {
        $query .= $searchQuery . " ";
    }
    $query .= $whereCondition . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT $row,$rowperpage";
}
 
$empRecords = $db_cms->select_query($query);

//$records = $db_cms->select_query_with_row("SELECT COUNT(DISTINCT id_sensor) AS allcount FROM $table_name WHERE 1 " . $whereCondition);
//$totalRecordwithFilter = $records['allcount'];
// Get the total count including filters
$totalRecordwithFilter = $totalRecords; // Use the totalRecords calculated above
  
 

$data = array(); // Initialize the $data array

$actionhtml = 'test';
$getattribute = explode(',', $fields); $n=1;
foreach ($empRecords as $row) {
  $action = '';
  if($_COOKIE['user']=='administrador' || trim($_COOKIE['user'])=='god1') {
   $action = '
   <a class="btn btn-light delete_process" data-toggle="modal" data-target="#modal-default" href="javascript:void(0);" data-delete-id=' . $row['id_certificado'] . ' data-delete-sensorid=' . $row['id_sensor'] . '><i class="fas fa-times text-danger" aria-hidden="true"></i></a>';
  }
   $action .= '
  <a href="index.php?module=13&page=7&amp;s=' . $row['id_sensor'] . '" class="btn btn-secondary"><i class="fa fa-eye" aria-hidden="true"></i></a>
   <a href="index.php?module=13&page=6&amp;s=' . $row['id_sensor'] . '&idcert=' . $row['id_certificado'] . '" class="btn btn-primary"><i class="fa fa-pen" aria-hidden="true"></i></a>
   <a href="index.php?module=13&page=5&amp;s=' . $row['id_sensor'] . '" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>';

  

   if ($fields != '') {
      $rowData = array(); // Initialize the $rowData array

      foreach ($getattribute as $attribute) {
        
			$rowData[$attribute] = $row[$attribute];
		  
         
         if($isdate=="true" && $attribute==$datename) {   
           // echo "<br>".  $attribute  ."==".   $datename;      
             $rowData[$attribute] = date($dateformat, strtotime($row[$attribute]));
            
          }
 
          if ($attribute == 'Estado') {
            if ($rowData[$attribute] == 'vigente' || $rowData[$attribute] == 'Vigente' || $rowData[$attribute] == 'VIGENTE') {
                $rowData[$attribute] = '<span class="btn btn-success">' . $rowData[$attribute] . '</span>';
            } else if ($rowData[$attribute] == 'Vencido' || $rowData[$attribute] == 'Fuera de servivio') {
                $rowData[$attribute] = "<span class='btn btn-danger'>" . $rowData[$attribute] . "</span>";
            }
        }

        
       
        if ($attribute == 'Días') {
            $daysDifference = (int) $rowData[$attribute];
           
           if ($daysDifference >= 90) {
               $rowData[$attribute] = '<span class="btn btn-success">' . $rowData[$attribute] . '</span>';
           } elseif ($daysDifference < 90 && $daysDifference > 30) {
               $rowData[$attribute] = '<span class="btn btn-warning">' . $rowData[$attribute] . '</span>';
           } elseif($rowData[$attribute]=='') {
            $rowData[$attribute] = $rowData[$attribute];
           }
           else{
               $rowData[$attribute] = '<span class="btn btn-danger">' . $rowData[$attribute] . '</span>';
           }
       }
          

       if ($attribute == 'Certificado') {
          
         $certificateName = $row['Certificado'];
         $id_certificado = $row['id_certificado'];
          //  $certificateName = str_replace('/', '_', $certificateName); // Replace slashes with underscores
         
          
          $pdfPath = "templates/certificados/{$row['id_sensor']}/{$certificateName}.pdf";
            $pdfURL = $baseurl. '/' . $pdfPath;

          $sql_pdf_files = "SELECT * FROM sensores_certicados_ficheros WHERE id_certificado = '$id_certificado' ORDER BY
          CASE WHEN tipo = 'Primario' THEN 1
               WHEN tipo = 'Secundario' THEN 2
               WHEN tipo = 'Vencido' THEN 3 
          END,
          nombre_archivo limit 1";
          $result_pdf_files = $db_cms->select_query($sql_pdf_files);
          
          if (!empty($result_pdf_files)) {
            $pdfFileName = $result_pdf_files[0]['nombre_archivo']; 
            $pdfPath = "templates/certificados/{$row['id_sensor']}/{$pdfFileName}";
            $pdfURL = $baseurl. '/' . $pdfPath;
            $rowData[$attribute] = '<a target="_blank" href="' . $pdfPath . '">' . $rowData[$attribute] . '</a>';

          }          
           else{
            $rowData[$attribute] = '<a target="_blank" href="' . $pdfPath . '">' . $rowData[$attribute] . '</a>';

           }
     }
     

        
      }
       
      //$rowData["productid"]= $pr_name;
      $rowData["action"] = $action;
      $rowData["id"] = $n;
      $data[] = $rowData; // Add the row data to the $data array
   } else {
      $data[] = array(
         "id" => $row['id'],
         "title" => $row['title'],
         "action" => $action
      );
   }$n++;
}

// Response
$response = array(
   "draw" => intval($draw),
   "iTotalRecords" => $totalRecords,
   "iTotalDisplayRecords" => $totalRecordwithFilter,
   "aaData" => $data
);

echo json_encode($response);
?>

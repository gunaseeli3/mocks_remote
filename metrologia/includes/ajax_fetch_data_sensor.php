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


$is_edit_enabled = $_POST['is_edit_enabled'];
$is_delete_enabled = $_POST['is_delete_enabled'];
$table_name = $_POST['table_name'];
$pagename = $_POST['pagename'];
$fields = $_POST['fields'];

$whereCondition  = $_POST['whereCondition'];

$isdate  = $_POST['isdate'];
$datename  = $_POST['datename'];
$dateformat  = $_POST['dateformat'];
$isprint  = $_POST['isprint'];

 


// Search
$searchValue = $_POST['search']['value']; // Search value
 
$searchArray = array();
$searchQuery = "";
 

// Total number of records without filtering
$records = $db_cms->select_query_with_row("SELECT COUNT(*) AS allcount FROM $table_name");
$totalRecords = $records['allcount'];

// Total number of records with filtering
$whereCondition = ""; // Initialize the WHERE condition
if (isset($_POST['whereCondition']) && $_POST['whereCondition'] != '') {
    $whereCondition = " and " . $_POST['whereCondition'];
}

if ($pagename == 'sensores') {
   $query = "SELECT 
   s.id_sensor,
   s.nombre AS nombre,
   s.serie AS 'serie',
   s.tipo AS tipo,
   s.estado AS estado,
   s.pais AS pais  
    
FROM 
   sensores s
   
WHERE 1 ";

   if ($searchValue != '') {
       $query .= " AND (s.id_sensor LIKE '%" . $searchValue . "%' OR s.nombre LIKE '%" . $searchValue . "%' OR s.serie LIKE '%" . $searchValue . "%' OR s.tipo LIKE '%" . $searchValue . "%' OR s.estado LIKE '%" . $searchValue . "%' OR s.pais LIKE '%" . $searchValue . "%') ";
   }
   $query .= ' ' . $whereCondition . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage;

  // $query .= $whereCondition . " ORDER BY id_sensor DESC  LIMIT " . $row . "," . $rowperpage;
} else {
   $query = "SELECT * FROM $table_name WHERE 1 ";
   if ($searchQuery != '') {
       $query .= $searchQuery . " ";
   }
   $query .= $whereCondition . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT $row,$rowperpage";
}

  //echo $query;


  $empRecords = $db_cms->select_query($query);

  $records = $db_cms->select_query_with_row("SELECT COUNT(*) AS allcount FROM $table_name WHERE 1   " . $whereCondition);
  $totalRecordwithFilter = $records['allcount'];

  
  
 

$data = array(); // Initialize the $data array

$actionhtml = 'test';
$getattribute = explode(',', $fields); $n=1;
foreach ($empRecords as $row) {
   
   $action = '
   <a class="btn btn-light delete_process_sensor" data-toggle="modal" data-target="#modal-default2" href="javascript:void(0);" data-delete-id=' . $row['id_sensor'] . '><i class="fas fa-times text-danger" aria-hidden="true"></i></a>
   <a href="index.php?module=13&page=10&amp;s=' . $row['id_sensor'] . '" class="btn btn-secondary"><i class="fa fa-eye" aria-hidden="true"></i></a>
   <a href="index.php?module=13&page=9&amp;s=' . $row['id_sensor'] . '" class="btn btn-primary"><i class="fa fa-pen" aria-hidden="true"></i></a>
   <a href="index.php?module=13&page=5&amp;s=' . $row['id_sensor'] . '" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></a>';

  

   if ($fields != '') {
      $rowData = array(); // Initialize the $rowData array

      foreach ($getattribute as $attribute) {
        
			$rowData[$attribute] = $row[$attribute];
		  
         
         if($isdate=="true" && $attribute==$datename) {   
           // echo "<br>".  $attribute  ."==".   $datename;      
             $rowData[$attribute] = date($dateformat, strtotime($row[$attribute]));
            
          }



          if ($attribute == 'estado') {
            if ($rowData[$attribute] == 'vigente' || $rowData[$attribute] == 'Vigente' || $rowData[$attribute] == 'VIGENTE') {
                $rowData[$attribute] = '<span class="btn btn-success">' . $rowData[$attribute] . '</span>';
            } else if ($rowData[$attribute] != '' || $rowData[$attribute] == 'Fuera de servivio ' || $rowData[$attribute] == 'Fuera de servivio') {
                $rowData[$attribute] = "<span class='btn btn-danger'>" . $rowData[$attribute] . "</span>";
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

<?php
error_reporting(0);
ob_start();
session_start();
 
require_once dirname(__FILE__) . '/../../../config.ini.php';

// Retrieve and loop through backtrack data from your database
$entriesPerPage = 5;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startFrom = ($currentPage - 1) * $entriesPerPage;

$sql2 = "SELECT * FROM backtrack WHERE modulo='Metrologia'";
$backtrackData2 = $db_cms->select_query($sql2);



$sql = "SELECT * FROM backtrack WHERE modulo='Metrologia' order by fecha desc LIMIT $startFrom, $entriesPerPage";
$backtrackData = $db_cms->select_query($sql);

$entriesHtml = '';
foreach ($backtrackData as $entry) {

    $sql5 = "SELECT * FROM usuario WHERE id_usuario=".$entry['persona'];
    $usuario = $db_cms->select_query($sql5);

    // Generate HTML markup for each backtrack entry
    $date = $entry['fecha'];
    $persona = $usuario[0]['usuario'];
    $movimiento = $entry['movimiento'];
    $modulo = $entry['modulo'];
    $descripcion = base64_decode($entry['descripcion']); // Decode base64 description

    $entriesHtml .= <<<HTML
    <div class="accordion1">
        <div class="accordion-header" id="heading{$entry['id_backtrack']}">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{$entry['id_backtrack']}" aria-expanded="false" aria-controls="collapse{$entry['id_backtrack']}">
                <strong>Date:</strong> $date - <strong>Persona:</strong> $persona - <strong>Movimiento:</strong> $movimiento - <strong>Módulo:</strong> $modulo
            </button>
        </div>
        <div id="collapse{$entry['id_backtrack']}" class="accordion-collapse collapse" aria-labelledby="heading{$entry['id_backtrack']}" data-bs-parent="#accordion2">
            <div class="accordion-body">
                <strong>Descripción:</strong><br>
                $descripcion
            </div>
        </div>
    </div>
    HTML;
}

// Calculate total pages for pagination
 $totalPages = ceil(count($backtrackData2) / $entriesPerPage);

// Generate pagination links
$paginationHtml = '';
 
for ($i = 1; $i <= $totalPages; $i++) {
    
    if ($i == $currentPage) {
        $isActive = 'active';
    } else {
        $isActive = '';
    }
    $paginationHtml .= "<li class='page-item $isActive'><a class='page-link' href='#' data-page='$i'>$i</a></li>";
     }
 


// Return data as JSON
$data = array(
    'entries' => $entriesHtml,
    'pagination' => $paginationHtml,
    'currentPage' => $currentPage,
);

header('Content-Type: application/json');
echo json_encode($data);
?>

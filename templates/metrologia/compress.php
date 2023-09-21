<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a ZIP file is uploaded
    if (isset($_FILES['pdfFiles']) && $_FILES['pdfFiles']['error'] === UPLOAD_ERR_OK) {
        $zipFile = $_FILES['pdfFiles']['tmp_name'];

        // Check if it's a ZIP file
        $zip = new ZipArchive();
        if ($zip->open($zipFile) === TRUE) {
            $pdfFileNames = array();

            // Loop through the files in the ZIP archive
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileName = $zip->getNameIndex($i);

                // Check if the extracted file is a PDF (you can add more checks here)
                if (pathinfo($fileName, PATHINFO_EXTENSION) === 'pdf') {
                    $pdfFileNames[] = $fileName;
                }
            }

            $zip->close();

            if (!empty($pdfFileNames)) {
                echo "Extracted PDF files:<br>";
                foreach ($pdfFileNames as $pdfFileName) {
                    echo htmlspecialchars($pdfFileName) . "<br>";
                }
            } else {
                echo "No PDF files found in the ZIP archive.";
            }
        } else {
            echo "Failed to open the ZIP file.";
        }
    } else {
        echo "No ZIP file uploaded or an error occurred during upload.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload ZIP and Extract PDFs</title>
</head>
<body>
    <h1>Upload a ZIP File Containing PDFs</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="pdfFiles">Choose a ZIP file:</label>
        <input type="file" name="pdfFiles" id="pdfFiles" accept=".zip" required>
        <br>
        <input type="submit" value="Upload and Extract">
    </form>
</body>
</html>

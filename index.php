<?php require_once('inc/header.php'); ?>

<?php
if (isset($_POST['submit'])) {
    $table_data = upload_file();
}
download_file();
function upload_file()
{
    $file_name = $_FILES['fileToUpload']['name'];
    $file_tmp_name = $_FILES['fileToUpload']['tmp_name'];
    $file_error = $_FILES['fileToUpload']['error'];

    $file_ext = explode('.', $file_name);
    $file_actual_ext = strtolower(end($file_ext));
    if ($file_actual_ext === 'csv') {
        if ($file_error === 0) {
            $file_new_name = uniqid('', true) . '.' . $file_actual_ext;
            $file_destination = 'uploads/' . $file_new_name;
            if (move_uploaded_file($file_tmp_name, $file_destination)) {
                return convert_to_xml($file_destination);
            };
        } else {
            echo "<h3 style='text-align: center; color: red;'>There was a error uploading your file.</h3>";
        }
    } else {
        echo "<h3 style='text-align: center; color: red;'>Only allowed type is <strong>(.csv)</strong></h3>";
    }

}

function convert_to_xml($file_destination)
{
    $file = file($file_destination, FILE_IGNORE_NEW_LINES);

    $rows = array_map('str_getcsv', $file);
    $header = array_shift($rows);
    $data = array();
    foreach ($rows as $row) {
        $data[] = array_combine($header, $row);
    }

    $xml = new DomDocument('1.0', 'UTF-8');

    $root = $xml->createElement('root');
    $xml->appendChild($root);

    foreach ($data as $key => $val) {
        $entry = $xml->createElement('row');
        $root->appendChild($entry);

        foreach ($val as $field_name => $field_value) {
            $field_name = preg_replace("/[^A-Za-z0-9]/", '_', strtolower($field_name));
            $name = $entry->appendChild($xml->createElement($field_name));
            $name->appendChild($xml->createTextNode($field_value));
        }

    }
    $xml->formatOutput = true;

    $file_xml_name = uniqid('', true);
    $xml->save("converted_files/$file_xml_name.xml");
    return ['header' => $header, 'body' => $data, 'download_url' => "converted_files/$file_xml_name.xml"];
}

function download_file() {

    if (isset($_GET['download'])) {
        $file = $_GET['download'];

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }
}
?>


<main class="main-container">
    <div class="conteiner-inner">
        <h1>CSV to XML Converter</h1>

        <!-- File upload container -->
        <div class="upload-wrapper">
            <form action="" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                <div class="select-file-wrap">
                    <h2>Pleases select your csv file</h2>
                    <div class="select-file">

                        <input type="file" id="fileToUpload" name="fileToUpload" accept=".csv"/>
                        <img src="assets/images/svg/svg-cloud-and-upload-icon-1.svg" alt="Upload icon">
                    </div>
                </div>
                <input class="btn upload-btn" type="submit" name="submit" id="csvUploadBtn"
                       value="Upload your selected file"/>
            </form>
        </div>
        <!-- //End file upload container -->

        <!-- Table container -->
        <div class="table-container">
            <?php
            if (!empty($table_data)) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                foreach ($table_data['header'] as $cell) {
                    echo "<th>";
                    echo $cell;
                    echo "</th>";
                }
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($table_data['body'] as $row) {
                    echo "<tr>";
                    foreach ($row as $item) {
                        echo "<td>";
                        echo $item;
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo '<a href="?download='. $table_data['download_url'] .'" id="exportXml" class="download btn">Download as XML</a>';
            }
            ?>
        </div>
    </div>
</main>

<?php require_once('inc/footer.php'); ?>
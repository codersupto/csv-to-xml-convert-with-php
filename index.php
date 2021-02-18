<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<main class="main-container">
    <div class="conteiner-inner">
        <h1>CSV to XML Converter</h1>

        <!-- File upload container -->
        <div class="upload-wrapper">
            <form action="" method="POST" enctype="multipart/form-data" id="csvUploadForm">
                <div class="select-file-wrap">
                    <h2>Pleases select your csv file</h2>
                    <div class="select-file">

                        <input type="file" id="fileToUpload" name="fileToUpload" accept="*/*"/>
                        <img src="assets/images/svg/svg-cloud-and-upload-icon-1.svg" alt="Upload icon">
                    </div>
                </div>
                <input class="btn upload-btn" type="submit" name="submit" id="csvUploadBtn" value="Upload your selected file"/>
            </form>
        </div>
        <!-- //End file upload container -->

        <!-- Table container -->
        <div class="table-container">
            <div id="dvCSV" class="table-wrap"></div>
            <button id="exportXml" class="download btn">Download as XML</button>
        </div>
    </div>
</main>

<script src="assets/js/main.js" defer></script>
</body>
</html>
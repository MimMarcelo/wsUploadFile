<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        print_r($_FILES);
        ?>
        <form method="post" enctype="multipart/form-data">
            <label for="file">Select a file: </label>
            <input type="file" name="pFile" id="file">
            <br>
            <input type="submit" value="Send">
        </form>
    </body>
</html>

<?php
require_once './FileUpload.php';
if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
    $file = new FileUpload($_FILES['pFile']);
//    if ($file->validate(1, true, array("jpg", "png"))) {
    if ($file->validate(true, 0.6, array("jpg", "png"))) {
        if($file->upload()){
            echo "File upload success";
        }
        else{
            echo "File upload failed";
        }
    } else {
        print_r($file->getError());
    }
}
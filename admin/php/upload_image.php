<?php
$targetDir = "../images/";
$targetFile = $targetDir . basename($_FILES["file"]["name"]);
if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    echo json_encode(["success" => true, "file" => basename($_FILES["file"]["name"])]);
} else {
    echo json_encode(["success" => false, "message" => "Error al subir la imagen"]);
}
?>

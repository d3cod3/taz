<?php

  require_once("includes.php");

  \D3COD3\MULEDB::MULEConnect();

?>

<?php
// Check if the form was submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_FILES["mule_content_data"]) && $_FILES["mule_content_data"]["error"] == 0){
      $allowed = array("png" => "image/png");
      $filename = $_FILES["mule_content_data"]["name"];
      $filetype = $_FILES["mule_content_data"]["type"];
      $filesize = $_FILES["mule_content_data"]["size"];

      // Verify file extension
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

      // Verify file size - 20MB maximum
      $maxsize = 20 * 1024 * 1024;
      if($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

      // Verify MIME type of the file
      if(in_array($filetype, $allowed)){
          // Check whether file exists before uploading it
          if(file_exists("data/" . $filename)){
              echo $filename . " is already exists.";
          } else{
              if (isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['altitude']) && isset($_POST['angle'])) {
                $localFile = "data/" . $filename;
                move_uploaded_file($_FILES["mule_content_data"]["tmp_name"], $localFile);
                \D3COD3\MULEDB::MULEADDData($localFile,$_POST['latitude'],floatval($_POST['longitude']),floatval($_POST['altitude']),floatval($_POST['angle']));
                echo "Data was uploaded successfully.";
              }else{
                echo "Error: There was a problem with POST data.";
              }
          }
      } else{
          echo "Error: There was a problem uploading your file. Please try again.";
      }


    }else{
      echo "Error: " . $_FILES["photo"]["error"];
    }
}

?>


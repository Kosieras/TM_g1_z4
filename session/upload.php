<?php
//Pobranie zmiennych
session_start();
$dirSession = $_SESSION['dirSession'];

$target_file = $dirSession. "/". basename($_FILES["fileToUpload"]["name"]); if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file))
{ 
   echo "Uploading...";
  header('Refresh: 2; URL=cloud.php');
} 
else echo "Error uploading file.";
?>


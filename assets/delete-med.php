<?php
include 'config.php';
if (isset($_POST['id'])) {
  $id=$_POST['id'];
  $sql_delete="DELETE FROM dogs_medications WHERE dogMedID='$id'";
  $conn->query($sql_delete);
}
?>

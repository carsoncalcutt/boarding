<?php
include '../assets/config.php';
if (isset($_POST['status']) AND isset($_POST['id']) AND isset($_POST['condo']) AND isset($_POST['catName']) AND isset($_POST['foodType']) AND isset($_POST['feedingInstructions'])) {
  $status=$_POST['status'];
  $id=$_POST['id'];
  $condoID=mysqli_real_escape_string($conn, $_POST['condo']);
  $catName=mysqli_real_escape_string($conn, $_POST['catName']);
  $foodType=mysqli_real_escape_string($conn, $_POST['foodType']);
  $feedingInstructions=mysqli_real_escape_string($conn, $_POST['feedingInstructions']);
  $sql_update="UPDATE cats SET condoID='$condoID', catName='$catName', foodType='$foodType', feedingInstructions='$feedingInstructions' WHERE catID='$id'";
  $conn->query($sql_update);
}
?>

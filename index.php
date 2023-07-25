<?php
include 'assets/config.php';
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>Food & Meds</title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadAddFoodForm(status){
    $.ajax({
      url:'/assets/load-add-food-form.php',
      type:'POST',
      cache:false,
      data:{status:status},
      success:function(data){
        if (data) {
          $('#addFoodModalBody').append(data);
          loadTableCounts();
        }
      }
    });
  }
  function loadaddMedForm(status, id){
    $.ajax({
      url:'/assets/load-add-med-form.php',
      type:'POST',
      cache:false,
      data:{status:status, id:id},
      success:function(data){
        if (data) {
          $('#addMedModalBody').append(data);
        }
      }
    });
  }
  function loadFoodMeds(status){
    $.ajax({
      url:'/assets/load-food-meds.php',
      type:'POST',
      cache:false,
      data:{status:status},
      success:function(data){
        if (data) {
          if (status=='Active') {
            $('#table-currently-boarding').empty();
            $('#table-currently-boarding').append(data);
          } if (status=='Future') {
            $('#table-future-arrivals').empty();
            $('#table-future-arrivals').append(data);
          }
          loadTableCounts();
        }
      }
    });
  }
  function loadTableCounts() {
    $('#table-currently-boarding-count').empty();
    var currentBoardingCount=$('#table-currently-boarding').find('tr').length;
    $('#table-currently-boarding-count').append(currentBoardingCount);
    $('#table-future-arrivals-count').empty();
    var futureArrivalsCount=$('#table-future-arrivals').find('tr').length;
    $('#table-future-arrivals-count').append(futureArrivalsCount);
  }
  $(document).ready(function(){
    $('#food-meds').addClass('active');
    loadFoodMeds('Active');
    loadFoodMeds('Future');
    $('#addFood').click(function (e) {
      e.preventDefault();
      var status=document.getElementById('newStatus').value;
      var room=document.getElementById('newRoom').value;
      var name=document.getElementById('newDogName').value;
      var foodType=document.getElementById('newFoodType').value;
      var feedingInstructions=document.getElementById('newFeedingInstructions').value;
      $.ajax({
        url:'assets/add-food.php',
        type:'POST',
        cache:false,
        data:{status:status, room:room, name:name, foodType:foodType, feedingInstructions:feedingInstructions},
        success:function(response){
          loadFoodMeds(status);
          $('#addFoodModal').modal('hide');
          document.getElementById('addFoodForm').reset();
        }
      });
    });
    $('#addMed').click(function (e) {
      e.preventDefault();
      var status=document.getElementById('newStatus').value;
      var id=document.getElementById('newID').value;
      var medName=document.getElementById('newMedName').value;
      var strength=document.getElementById('newStrength').value;
      var dosage=document.getElementById('newDosage').value;
      var frequency=document.getElementById('newFrequency').value;
      $.ajax({
        url:'assets/add-med.php',
        type:'POST',
        cache:false,
        data:{status:status, id:id, medName:medName, strength:strength, dosage:dosage, frequency:frequency},
        success:function(response){
          loadFoodMeds(status);
          $('#addMedModal').modal('hide');
          document.getElementById('addMedForm').reset();
        }
      });
    });
    $('#addCurrentlyBoardingButton').click(function (e) {
      loadAddFoodForm('Active');
    });
    $('#addFutureArrivalsButton').click(function (e) {
      loadAddFoodForm('Future');
    });
    $(document).on('click', '#add-med-button', function() {
      var status=$(this).data('status');
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-add-med-form.php',
        type:'POST',
        cache:false,
        data:{status:status, id:id},
        success:function(response){
          $('#addMedModalBody').append(response);
        }
      });
    });
    $(document).on('click', '.button-check', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/check-in-dog.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#row-dog-'+id).remove();
          loadFoodMeds('Active');
        }
      });
    });
    $(document).on('click', '#delete-dog-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-delete-dog-form.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#deleteDogModalBody').append(response);
        }
      });
    });
    $('#deleteDog').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('deleteID').value;
      $.ajax({
        url:'assets/delete-dog.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#row-dog-'+id).remove();
          $('#deleteDogModal').modal('hide');
          $('#deleteDogModalBody').empty();
          loadTableCounts();
        }
      });
    });
    $(document).on('click', '#delete-med-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'assets/load-delete-med-form.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#deleteMedModalBody').append(response);
        }
      });
    });
    $('#deleteMed').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('deleteID').value;
      $.ajax({
        url:'assets/delete-med.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#med-label-'+id).remove();
          $('#deleteMedModal').modal('hide');
          $('#deleteMedModalBody').empty();
        }
      });
    });
    $(document).on('click', '#edit-dog-button', function() {
      var id=$(this).data('id');
      var status=$(this).data('status');
      $.ajax({
        url:'assets/load-edit-food-form.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status},
        success:function(response){
          $('#editDogModalBody').append(response);
        }
      });
    });
    $('#editDog').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('editID').value;
      var status=document.getElementById('editStatus').value;
      var room=document.getElementById('editRoom').value;
      var dogName=document.getElementById('editDogName').value;
      var foodType=document.getElementById('editFoodType').value;
      var feedingInstructions=document.getElementById('editFeedingInstructions').value;
      $.ajax({
        url:'assets/edit-food.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status, room:room, dogName:dogName, foodType:foodType, feedingInstructions:feedingInstructions},
        success:function(response){
          $('#editDogModal').modal('hide');
          $('#editDogModalBody').empty();
          loadFoodMeds(status);
        }
      });
    });
    $(document).on('click', '#edit-med-button', function() {
      var id=$(this).data('id');
      var status=$(this).data('status');
      $.ajax({
        url:'assets/load-edit-med-form.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status},
        success:function(response){
          $('#editMedModalBody').append(response);
        }
      });
    });
    $('#editMed').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('editID').value;
      var status=document.getElementById('editStatus').value;
      var medName=document.getElementById('editMedName').value;
      var strength=document.getElementById('editStrength').value;
      var dosage=document.getElementById('editDosage').value;
      var frequency=document.getElementById('editFrequency').value;
      $.ajax({
        url:'assets/edit-med.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status, medName:medName, strength:strength, dosage:dosage, frequency:frequency},
        success:function(response){
          $('#editMedModal').modal('hide');
          $('#editMedModalBody').empty();
          loadFoodMeds(status);
        }
      });
    });
    $('.modal').on('hidden.bs.modal', function(){
      $('#addFoodModalBody').empty();
      $('#addMedModalBody').empty();
      $('#deleteDogModalBody').empty();
      $('#deleteMedModalBody').empty();
      $('#editDogModalBody').empty();
      $('#editMedModalBody').empty();
    });
  });
  </script>
</head>
<body>
  <?php include 'assets/navbar.php'; ?>
  <form action='' method='post' spellcheck='false' id='addFoodForm'>
    <div class='modal fade' id='addFoodModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Add Food</h4>
          </div>
          <div class='modal-body' id='addFoodModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='addFood'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <div class='container-fluid'>
    <div class='table-outer'>
      <div class='table-header'>
        <span class='table-heading'>Currently Boarding</span>
        <span class='table-count' id='table-currently-boarding-count'></span>
        <a>
          <button type='button' class='pull-right button-add' id='addCurrentlyBoardingButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'></button>
        </a>
      </div>
      <div class='table-container'>
        <table class='table table-hover table-condensed'>
          <thead>
            <tr>
              <th>Room</th>
              <th>Name</th>
              <th>Food Type</th>
              <th>Feeding Instructions</th>
              <th>Medications</th>
              <th></th>
            </tr>
          </thead>
          <tbody id='table-currently-boarding'></tbody>
        </table>
      </div>
    </div>
    <div class='table-outer'>
      <div class='table-header'>
        <span class='table-heading'>Future Arrivals</span>
        <span class='table-count' id='table-future-arrivals-count'></span>
        <a>
          <button type='button' class='pull-right button-add' id='addFutureArrivalsButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'></button>
        </a>
      </div>
      <div class='table-container'>
        <table class='table table-hover table-condensed'>
          <thead>
            <tr>
              <th>Room</th>
              <th>Name</th>
              <th>Food Type</th>
              <th>Feeding Instructions</th>
              <th>Medications</th>
              <th></th>
            </tr>
          </thead>
          <tbody id='table-future-arrivals'></tbody>
        </table>
      </div>
    </div>
  </div>
  <form action='' method='post' spellcheck='false' id='addMedForm'>
    <div class='modal fade' id='addMedModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Add Medication</h4>
          </div>
          <div class='modal-body' id='addMedModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='addMed'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form action='' method='post' id='editDogForm'>
    <div class='modal fade' id='editDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Edit Food</h4>
          </div>
          <div class='modal-body' id='editDogModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='editDog'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form action='' method='post' id='editMedForm'>
    <div class='modal fade' id='editMedModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Edit Medication</h4>
          </div>
          <div class='modal-body' id='editMedModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='editMed'>Submit</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form action='' method='post' id='deleteDogForm'>
    <div class='modal fade' id='deleteDogModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Delete Dog</h4>
          </div>
          <div class='modal-body' id='deleteDogModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-danger' id='deleteDog'>Delete</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <form action='' method='post' id='deleteMedForm'>
    <div class='modal fade' id='deleteMedModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Delete Medication</h4>
          </div>
          <div class='modal-body' id='deleteMedModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-danger' id='deleteMed'>Delete</button>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</body>
</html>

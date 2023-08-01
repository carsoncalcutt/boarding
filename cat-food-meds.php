<?php
include 'assets/config.php';
if (isset($_GET['meds']) AND $_GET['meds']!='') {
  $sortMeds=$_GET['meds'];
} else {
  $sortMeds='all';
}
?>
<!DOCTYPE html>
<html lang='en'>
<head>
  <title>
    Cat Food & Meds
    <?php
    if ($sortMeds=='am') {
      echo " | AM Meds";
    } elseif ($sortMeds=='noon') {
      echo " | Noon Meds";
    } elseif ($sortMeds=='pm') {
      echo " | PM Meds";
    }
    ?>
  </title>
  <?php include 'assets/header.php'; ?>
  <script type='text/javascript'>
  function loadAddFoodForm(status){
    $.ajax({
      url:'/ajax/load-add-cat-food-form.php',
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
      url:'/ajax/load-add-cat-med-form.php',
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
  function loadFoodMeds(status, sortMeds){
    $.ajax({
      url:'/ajax/load-cat-food-meds.php',
      type:'POST',
      cache:false,
      data:{status:status, sortMeds:sortMeds},
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
    $('#cat-food-meds').addClass('active');
    <?php
    if ($sortMeds=='am') {
      echo "$('#amMedsButton').addClass('active');";
    } elseif ($sortMeds=='noon') {
      echo "$('#noonMedsButton').addClass('active');";
    } elseif ($sortMeds=='pm') {
      echo "$('#pmMedsButton').addClass('active');";
    }
    ?>
    loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
    loadFoodMeds('Future', <?php echo "'$sortMeds'"; ?>);
    $('#addFood').click(function (e) {
      e.preventDefault();
      var status=document.getElementById('newStatus').value;
      var condo=document.getElementById('newCondo').value;
      var name=document.getElementById('newCatName').value;
      var foodType=document.getElementById('newFoodType').value;
      var feedingInstructions=document.getElementById('newFeedingInstructions').value;
      $.ajax({
        url:'/ajax/add-cat-food.php',
        type:'POST',
        cache:false,
        data:{status:status, condo:condo, name:name, foodType:foodType, feedingInstructions:feedingInstructions},
        success:function(response){
          loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
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
      var notes=document.getElementById('newNotes').value;
      $.ajax({
        url:'/ajax/add-cat-med.php',
        type:'POST',
        cache:false,
        data:{status:status, id:id, medName:medName, strength:strength, dosage:dosage, frequency:frequency, notes:notes},
        success:function(response){
          loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
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
        url:'/ajax/load-add-cat-med-form.php',
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
        url:'/ajax/check-in-cat.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#row-cat-'+id).remove();
          loadFoodMeds('Active', <?php echo "'$sortMeds'"; ?>);
        }
      });
    });
    $(document).on('click', '#delete-cat-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'/ajax/load-delete-cat-form.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#deleteCatModalBody').append(response);
        }
      });
    });
    $('#deleteCat').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('deleteID').value;
      $.ajax({
        url:'/ajax/delete-cat.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#row-cat-'+id).remove();
          $('#deleteCatModal').modal('hide');
          $('#deleteCatModalBody').empty();
          loadTableCounts();
        }
      });
    });
    $(document).on('click', '#delete-med-button', function() {
      var id=$(this).data('id');
      $.ajax({
        url:'/ajax/load-delete-cat-med-form.php',
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
        url:'/ajax/delete-cat-med.php',
        type:'POST',
        cache:false,
        data:{id:id},
        success:function(response){
          $('#med-label-'+id).remove();
          $('#deleteMedModal').modal('hide');
          $('#deleteMedModalBody').empty();
          $('#table-currently-boarding').empty();
          $('#table-future-arrivals').empty();
          loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
          loadTableCounts();
        }
      });
    });
    $(document).on('click', '#edit-cat-button', function() {
      var id=$(this).data('id');
      var status=$(this).data('status');
      $.ajax({
        url:'/ajax/load-edit-cat-food-form.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status},
        success:function(response){
          $('#editCatModalBody').append(response);
        }
      });
    });
    $('#editCat').click(function (e) {
      e.preventDefault();
      var id=document.getElementById('editID').value;
      var status=document.getElementById('editStatus').value;
      var condo=document.getElementById('editCondo').value;
      var catName=document.getElementById('editCatName').value;
      var foodType=document.getElementById('editFoodType').value;
      var feedingInstructions=document.getElementById('editFeedingInstructions').value;
      $.ajax({
        url:'/ajax/edit-cat-food.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status, condo:condo, catName:catName, foodType:foodType, feedingInstructions:feedingInstructions},
        success:function(response){
          $('#editCatModal').modal('hide');
          $('#editCatModalBody').empty();
          loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
        }
      });
    });
    $(document).on('click', '#edit-med-button', function() {
      var id=$(this).data('id');
      var status=$(this).data('status');
      $.ajax({
        url:'/ajax/load-edit-cat-med-form.php',
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
      var notes=document.getElementById('editNotes').value;
      $.ajax({
        url:'/ajax/edit-cat-med.php',
        type:'POST',
        cache:false,
        data:{id:id, status:status, medName:medName, strength:strength, dosage:dosage, frequency:frequency, notes:notes},
        success:function(response){
          $('#editMedModal').modal('hide');
          $('#editMedModalBody').empty();
          $('#table-currently-boarding').empty();
          $('#table-future-arrivals').empty();
          loadFoodMeds(status, <?php echo "'$sortMeds'"; ?>);
          loadTableCounts();
        }
      });
    });
    $('.modal').on('hidden.bs.modal', function(){
      $('#addFoodModalBody').empty();
      $('#addMedModalBody').empty();
      $('#deleteCatModalBody').empty();
      $('#deleteMedModalBody').empty();
      $('#editCatModalBody').empty();
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
  <div class='nav-footer'>
    <a href='/cats/medications/am'>
      <button type='button' class='btn btn-default nav-button' id='amMedsButton' title='AM Medications'>AM Medications</button>
    </a>
    <a href='/cats/medications/noon'>
      <button type='button' class='btn btn-default nav-button' id='noonMedsButton' title='Noon Medications'>Noon Medications</button>
    </a>
    <a href='/cats/medications/pm'>
      <button type='button' class='btn btn-default nav-button' id='pmMedsButton' title='PM Medications'>PM Medications</button>
    </a>
  </div>
  <div class='container-fluid'>
    <div class='table-outer'>
      <div class='table-header'>
        <span class='table-heading'>Currently Boarding</span>
        <span class='table-count' id='table-currently-boarding-count'>0</span>
        <a>
          <button type='button' class='pull-right button-add' id='addCurrentlyBoardingButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'></button>
        </a>
      </div>
      <div class='table-container'>
        <table class='table table-hover table-condensed'>
          <thead>
            <tr>
              <th>Condo</th>
              <th>Name</th>
              <th>Food</th>
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
        <span class='table-count' id='table-future-arrivals-count'>0</span>
        <a>
          <button type='button' class='pull-right button-add' id='addFutureArrivalsButton' data-toggle='modal' data-target='#addFoodModal' data-backdrop='static' title='Add Food'></button>
        </a>
      </div>
      <div class='table-container'>
        <table class='table table-hover table-condensed'>
          <thead>
            <tr>
              <th>Condo</th>
              <th>Name</th>
              <th>Food</th>
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
  <form action='' method='post' id='editCatForm'>
    <div class='modal fade' id='editCatModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Edit Food</h4>
          </div>
          <div class='modal-body' id='editCatModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-primary' id='editCat'>Submit</button>
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
  <form action='' method='post' id='deleteCatForm'>
    <div class='modal fade' id='deleteCatModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h4 class='modal-title'>Delete Cat</h4>
          </div>
          <div class='modal-body' id='deleteCatModalBody'></div>
          <div class='modal-footer'>
            <button type='submit' class='btn btn-danger' id='deleteCat'>Delete</button>
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
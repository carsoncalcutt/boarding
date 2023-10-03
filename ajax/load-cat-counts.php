<?php
include '../assets/config.php';
if (isset($_POST['startDate']) AND isset($_POST['endDate'])) {
  $startDate=$_POST['startDate'];
  $endDate=$_POST['endDate'];
  $sql_condo_count="SELECT COUNT(condoID) AS availableCondos FROM condos WHERE status!='Disabled' AND condoID NOT IN (SELECT condoID FROM cats_reservations WHERE checkIn<='$endDate' AND checkOut>='$startDate')";
  $result_condo_count=$conn->query($sql_condo_count);
  $row_condo_count=$result_condo_count->fetch_assoc();
  $condoCount=$row_condo_count['availableCondos'];
  $endDateDayOfWeek=date('N', strtotime($endDate));
  $endDateAdd=7-$endDateDayOfWeek;
  $endDateFormat=date_create(date('Y-m-d', strtotime($endDate)));
  date_add($endDateFormat,date_interval_create_from_date_string("$endDateAdd days"));
  $weekendCountEndDate=date_format($endDateFormat,'Y-m-d');
  $sql_weekend_count="SELECT COUNT(catReservationID) AS weekendCheckOuts FROM cats_reservations WHERE checkIn<='$endDate' AND checkOut>='$startDate' AND checkOut<='$weekendCountEndDate' AND WEEKDAY(checkOut) IN (4, 5, 6)";
  $result_weekend_count=$conn->query($sql_weekend_count);
  $row_weekend_count=$result_weekend_count->fetch_assoc();
  $weekendCount=$row_weekend_count['weekendCheckOuts'];
  echo "<div class='nav-condo-count'><span class='nav-count'>$condoCount</span> condo";
  if ($condoCount!=1) {
    echo "s";
  }
  echo " available</div>
  <div class='nav-weekend-count'><span class='nav-count'>$weekendCount</span> weekend check-out";
  if ($weekendCount!=1) {
    echo "s";
  }
  echo "</div>";
}
?>

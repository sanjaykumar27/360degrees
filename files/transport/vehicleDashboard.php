<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Sanjay Chaurasia (schaurasia@ebizneeds.com) | www.ebizneeds.com
   * Page details here: Dashboard page for Vehile Mileage, reports etc. 
   * Updates here:
   */
  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
  if (isset($_POST) && !empty($_POST)) {
      $qryString = '&' . http_build_query(cleanvar($_POST));
  } else {
      $qryString = '';
  }
?>

<div class="container">
    <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform" >
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon">Vehicle Name</span>
                <select id="busid" class="form-control" name="busid" required="true">
                    <?php
                      if (isset($routeDetail['busid'])) {
                          echo populateSelect("busid", $routeDetail['busid']);
                      } else {
                          echo populateSelect("busid", submitFailFieldValue("busid"));
                      }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-lg-4 ">
            <div class="input-group">
                <span class="input-group-addon">Date From</span>
                <input type="date" name="monthstart" id="monthstart" class="form-control">
            </div>
        </div>  
        <div class="col-lg-4">
            <div class="input-group">
                <span class="input-group-addon">Date To</span>
                <input type="date" name="monthend" id="monthend" class="form-control"> 
            </div>
        </div> 
        <span class="clearfix">&nbsp;<br></span>
        <div class='control' align="center">
            <button type='reset' nae="cancel"  value="Reset" class="btn ">Cancel</button>
            <button name='search'  value="Search" class="btn btn-success ">Search</button>
        </div>
    </form>
    <span class="clearfix">&nbsp;<br></span>
</div>

<?php
  if (isset($_POST['search'])) {
      $milageArray = totalTravel();
      $vehicleDetails = getVehicleDetails();
      $fuelArray = totalFuel();
      foreach ($fuelArray[2] as $key => $value){
          if($key === 'date_liters'){
              $fuelArray[2]['yData'] =  $fuelArray[2]['date_liters'];
          }
          if($key === 'mileage'){
              $fuelArray[2]['xData'] = $fuelArray[2]['mileage'];
          }
          if($key === 'busname'){
              $fuelArray[2]['entity'] = $fuelArray[2]['busname'];
          }
         
      }
       unset($fuelArray[2]['date_liters']);
       unset($fuelArray[2]['mileage']);
        unset($fuelArray[2]['busname']);
      
      $average = 0;
      if ($fuelArray != 0) {
          $average = @($milageArray[1] / $fuelArray[0]);
          $average = number_format($average, 2, '.', '') . ' Kmpl';
      } else {
          $average = "Fuel Details not specified";
      }

      /* This section displays the graph, by calling function 
       *   and passing associative array
       * Made By; Sanjay Kumar
       */
      
       echo displayGoogleGraph($fuelArray[2]);
      ?>
      <!-- *** container for vehicle details ***** -->
      <div class="container">
          <h3>Vehicle Details</h3>
          <table class="table table-hover">
              <th>Vehicle Name</th>
              <th>Vehicle Driver</th>
              <th>Vehicle Number</th>
              <th>No of Pick Up Points</th>
              <tr>
                  <td><?php echo $vehicleDetails['vehicletitle']; ?></td>
                  <td><?php echo $vehicleDetails['driverfirstname'] . ' ' . $vehicleDetails['driverlastname']; ?></td>
                  <td><?php echo $vehicleDetails['platenumber']; ?></td>
                  <td><?php echo $vehicleDetails['totalstops']; ?></td>
              </tr>
          </table>
      </div>
      <!-- *** Container for graph display** -->
      <div class="container">
          <div id="container"></div>
      </div>
      <span class="clearfix"><br></span>
      <script type="text/javascript">
          /* script to get the pdf output of the graph */
          function printDiv()
          {
              var divToPrint = document.getElementById('container');
              var newWin = window.open('', 'Print-Window');
              newWin.document.open();
              newWin.document.write('<html><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
              newWin.document.close();
              setTimeout(function () {
                  newWin.close();
              }, 10);

          }
      </script>
      <!-- ****container for table**** -->
        
      <div class="container"> 
          <input type='button' class="btn btn-success" id="btn" value='Print Graph' onclick='printDiv();'>
          <span class="clearfix"><br></span>
          <div class="col-lg-12">
              <h3>Vehicle Performance Summary 
                  <?php echo '[ ' . $milageArray[2] . ' ]' ?>

              </h3>
              <table class="table table-hover">
                  <tr>
                      <td>Total Distance Traveled [KM]</td>
                      <td><?php echo $milageArray[1]; ?></td>
                  </tr>
                  <tr>
                      <td>Total Fuel Consumption [Liters]</td>
                      <td><?php echo $fuelArray[0]; ?></td>
                  </tr>
                  <tr>
                      <td>Fuel Amount</td>
                      <td><?php echo formatCurrency($fuelArray[1]); ?></td>
                  </tr>
                  <tr>
                      <td>Vehicle Average</td>
                      <td><?php echo $average; ?></td>
                  </tr>
                  <tr>
                      <td>Daily Distance Travel [KM]</td>
                      <td><?php echo number_format($milageArray[0], 2, '.', '') ?></td>
                  </tr>
              </table>
          </div>
          <div class="row"> 
              <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
                  <a href="<?php echo DIR_REPORTS ?>/vehicleDashboardPDF.php?action=pdf<?php echo $qryString; ?>"> 
                      <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
                  <a href="<?php echo DIR_REPORTS ?>/vehicleDashboardPDF.php?action=xls<?php echo $qryString; ?>"> 
                      <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
              </div>
          </div>
      </div>


      <?php
  }
  require_once VIEW_FOOTER;
  /* function calculated total distance travelled
   * between the given dates.
   * Made by: Sanjay Kumar 15 Sept 2016
   */

  function totalTravel() {
      $total_travelled;
      $sql = "SELECT travel_date, vehicleid, start_meter, end_meter
            FROM tblvehiclemileage
            where vehicleid = $_POST[busid] ";
      if (isset($_POST['monthstart']) && !empty($_POST['monthstart'])) {
          $sql .= " AND travel_date >= '$_POST[monthstart] 00:00:00' ";
      }
      if (isset($_POST['monthend']) && !empty($_POST['monthend'])) {
          $sql .= " AND travel_date <= '$_POST[monthend] 23:59:59' ";
      }
      /*
        if (!empty($_POST['monthstart']) OR ( !empty($_POST['monthend']))) {
        $sql .= "AND travel_date BETWEEN '$_POST[monthstart] 00:00:00' AND '$_POST[monthend] 23:59:59'";
        } */

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $total_travelled[] = ($rows['end_meter'] - $rows['start_meter']);
              $date[] = $rows['travel_date'];
          }
          $date = date(('d-m-Y'), strtotime(reset($date))) . ' - ' . date(('d-m-Y'), strtotime(end($date)));
          $total_travel = array_sum($total_travelled);
          $daily_distance_covered = $total_travel / count($total_travelled);
          return array($daily_distance_covered, $total_travel, $date);
      }
  }

  /* this function calculates total fuel 
   * consumption and amount.
   * Made by: Sanjay Kumar 15 Sept 2016
   */

  function totalFuel() {
      $count = 0;
      $total_fuel = 0;
      $total_fuel_price = 0;
      $sql = "SELECT `vehicleid`,`date_filled`,`liters`,`amount`
            from tblvehiclefuel
            where vehicleid = $_POST[busid] ";

      if (isset($_POST['monthstart']) && !empty($_POST['monthstart'])) {
          $sql .= " AND date_filled >= '$_POST[monthstart] 00:00:00' ";
      }
      if (isset($_POST['monthend']) && !empty($_POST['monthend'])) {
          $sql .= " AND date_filled <= '$_POST[monthend] 23:59:59' ";
      }
      /*
        if (!empty($_POST['monthstart']) OR ( !empty($_POST['monthend']))) {
        $sql .= "AND date_filled BETWEEN '$_POST[monthstart] 00:00:00' AND '$_POST[monthend] 23:59:59'";
        } */

      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $fuel_intake[] = $rows;
              $fuel_date_array[] = date(('Y-m-d'), strtotime($rows['date_filled']));
              $fuel_filled[] = $rows['liters'];
              $count += count($rows['date_filled']);
              $total_fuel_price += $rows['liters'] * $rows['amount'];
          }

          $total_fuel += array_sum($fuel_filled);
          $distance = getAverage($fuel_intake, $count);

          /* Required Graph Data in form of associative array */
          foreach ($fuel_filled as $key => $value) {
              $mergeArray[] = "'" . $fuel_date_array[$key] . ' , ' . $value . ' L' . "'";
          }
          $milage_array = implode(',', $distance[1]);
          $driver  = getVehicleDetails();
          $name_of_yAxis_value = "Distance";
          $graphArray = array(
              "date_liters" =>$mergeArray, 
              'width' => '1200',
              'height' => '400',
              "mileage" => $distance[1],
              "xAxis" => "Date Range",
              "yAxis" => "Average [KM]",
              "title" => "Vehicle Mileage Performance",
              "busname" => "$driver[vehicletitle]");

          return array($total_fuel, $total_fuel_price, $graphArray);
      }
  }

  /* this function provides the vehicle
   * details, and driver information
   * Made by: Sanjay Kumar 15 Sept 2016
   */

  function getVehicleDetails() {
      $sql = "SELECT t1.vehicletitle,t1.platenumber,
       t3.driverfirstname, t3.driverlastname,
       COUNT(t5.pickuppointname) as totalstops
      
       FROM
       tblvehicle as t1,
       tblvehicledriverassoc as t2,
       tbldrivers as t3,
       tblrouteassoc as t4,
       tblpickuppoint as t5
       
       WHERE 
       t1.vehicleid = t2.vehicleid AND
       t2.driverid = t3.driverid AND
       t2.routeid = t4.routeid AND
       t4.pickuppointid = t5.pickuppointid AND
       t1.vehicleid = $_POST[busid] 
       ";
      $result = dbSelect($sql);
      if (mysqli_num_rows($result) > 0) {
          while ($rows = mysqli_fetch_assoc($result)) {
              $busDetails = $rows;
          }
          return $busDetails;
      }
  }

  /* this function average of the vehicle
   * between diff time range according to the fuel dates
   * Made by: Sanjay Kumar 15 Sept 2016
   */

  function getAverage($fuel_intake, $count) {
      $cnt = 0;
      $average_slab = array();
      for ($cnt = 0; $cnt < $count - 1; $cnt++) {
          $sql = "SELECT (SUM(end_meter)-SUM(start_meter))as distance, vehicleid
            FROM tblvehiclemileage
            where travel_date BETWEEN '" . $fuel_intake[$cnt]['date_filled'] . "' AND '" . $fuel_intake[$cnt + 1]['date_filled'] . "'
            AND vehicleid = $_POST[busid] 
            ";

          $result = dbSelect($sql);
          if (mysqli_num_rows($result) > 0) {
              while ($rows = mysqli_fetch_assoc($result)) {
                  $mileageArray[] = $rows;
              }
          }
      }

      for ($cnt = 0; $cnt < $count - 1; $cnt++) {
          $mileage[] = number_format(($mileageArray[$cnt]['distance'] / $fuel_intake[$cnt]['liters']), 2);
          $distance[] = $mileageArray[$cnt]['distance'];
      }
      return array($distance, $mileage);
      ;
  }
  
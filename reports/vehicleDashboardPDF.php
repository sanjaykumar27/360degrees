
<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Vehicle Dashboard PDF
 * Updates here:
 */

require_once "../config/config.php";
require_once '../lib/reportfunctions.php';
require_once DIR_FUNCTIONS;



$searchTerm = cleanVar($_REQUEST);

if (isset($searchTerm['action']) && $searchTerm['action'] == 'pdf') {
    require_once('../html2pdf/html2pdf.class.php');
    $content = ob_get_clean();
    $content = vehicledashboardReport();
    $html2pdf = new HTML2PDF('P', 'A4', 'en');
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf
    $html2pdf->Output('vehicledashboardReport.pdf');
    $html2pdf->Output('pdf/vehicleDashboard.pdf', 'F');
}

if (isset($searchTerm['action']) && $searchTerm['action'] == 'xls') {
    require_once'../PHPExcel-1.8/Classes/PHPExcel.php';
    $instsessassocid = $_SESSION['instsessassocid'];
    $content = studentFeeDetails('report');
    $branchDetails = getInstdetails();
    $filename = $branchDetails['instituteabbrevation'] . "-Vehicle Summary Report" . date('d/m/Y H:i:s') . ".xls";
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Central Academy")
            ->setTitle("Vehicle Summary Report");
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SNO')
            ->setCellValue('B1', 'SCHOLAR NO.')
            ->setCellValue('C1', 'STUDENT NAME')
            ->setCellValue('D1', 'CLASS')
            ->setCellValue('E1', 'DUE INSTALLMENTS')
            ->setCellValue('F1', 'FEE AMOUNT');

    $cellNo = 2;
    $sno = 1;
    $totalFeeAmount = 0;

    foreach ($content['records'] as $key => $value) {
        $studentname = $value['firstname'] . '' . $value['middlename'] . ' ' . $value['lastname'];
        $classname = $value['classdisplayname'] . '-' . $value['sectionname'];
        $studentname = strtoupper($studentname);
        if ($value['feedetails'] == 0) {
            continue;
        }

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $cellNo, $sno)
                ->setCellValue('B' . $cellNo, $value['scholarnumber'])
                ->setCellValue('C' . $cellNo, $studentname)
                ->setCellValue('D' . $cellNo, $classname)
                ->setCellValue('E' . $cellNo, $value['dueinstallments'])
                ->setCellValue('F' . $cellNo, formatcurrencypdf($value['feedetails']));

        $totalFeeAmount+=$value['feedetails'];
        $cellNo++;
        $sno++;
    }
    $cellNo++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('E' . $cellNo, 'Total Amount')
            ->setCellValue('F' . $cellNo, formatcurrencypdf($totalFeeAmount));

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
?>
<?php

function vehicledashboardReport() {
    $branchDetails = getInstdetails();
    $distance = totalTravel();
    $fuel_detail = totalFuel();
    $vehicle_detail = getVehicleDetails();
    $total_travel = $total_liters = $average = $count = $average_distance = $total_amount = 0;
    
    foreach ($distance as $key => $value){
        $distance[$key]['liters']= '-';
        $distance[$key]['amount']= '-';
        $distance[$key]['final_amount']= '-';
        $count += count($value['travel_date']);
        $total_travel += ($value['end_meter'] - $value['start_meter']);
    }
    foreach ($fuel_detail as $key => $value){
           $total_liters += $value['liters'];    
    }
    
    foreach($distance as $key =>$value){
        foreach ($fuel_detail as $k => $val){
            
            if($val['date_filled'] == $value['travel_date'])
            {
                
                $distance[$key]['liters']= $val['liters'];
                $distance[$key]['amount']= $val['amount'];
                $distance[$key]['final_amount']= formatcurrencypdf($val['amount'] * $val['liters']) ;
                $total_amount += ($val['amount'] * $val['liters']);
                }
        }
    }
    $average_distance = number_format(($total_travel / $count),2,'.','') ;
    $average = number_format(($total_travel / $total_liters),2,'.','').' Kmpl';
    //echoThis($total_travel.' '.$total_liters.' '.$average.' '.$average_distance.' '.$total_amount);die;
   
    if ($distance) {
        $htmlContent = "<page>
                            <html>
                                <head>
                                    <style>
                                        table
                                            {   
                                                width:900px;
                                                border: solid 1px #000000; 
                                                font-size:12px;
                                                cellpadding:0px;
                                                cellspacing:1px;
                                                border-collapse: collapse;
                                                font-family:arial;
                                                align:center;
                                            }
                                         td { 
                                                border:1px solid #000000;
                                                padding:05px; 
                                                text-align:left;
                                                background-color : #F6F6F6;
                                            }
                                            h1
                                            {
                                                text-align:center;
                                                font-family:shree;
                                                font-size:24px;
                                                margin:5px;
                                                padding:0px;
                                            }
                                            
                                            h3
                                            {
                                                text-align:center;
                                                font-size:16px;
                                                margin:5px;
                                                padding:0px;
                                            }
                                            h4
                                            {
                                                text-align:center;
                                                font-size:14px;
                                                margin:5px;
                                                padding:0px;
                                            }
                                            h5
                                            {
                                                text-align:center;
                                                margin:5px;
                                                padding:0px;
                                            }
                                    </style>
                                </head>
                                <body>";
        $htmlContent.= " <h1>$branchDetails[institutename]</h1>
                        <h5> Address : $branchDetails[instituteaddress1],$branchDetails[instituteaddress2]</h5>
                        ";
        $htmlContent.="<h5>Bus Name: $vehicle_detail[vehicletitle], 
                       Driver Name: $vehicle_detail[driverfirstname] $vehicle_detail[driverlastname], 
                       Plate Number: $vehicle_detail[platenumber], 
                       Total Stops: $vehicle_detail[totalstops]
                     </h5>";
        $htmlContent.="<br><h3>Vehicle Summary</h3>
            <table align=\"center\" width=\"100%\">
                         <tr>
                             <td><strong>Total Distance [KM]</strong></td>
                             <td><strong>Fuel Consumption<br> [literes]</strong></td>
                             <td><strong>Average</strong></td>
                             <td><strong>Daily Average Distance [KM]</strong></td>
                             <td><strong>Total Fuel Price</strong></td>
                         </tr>
                         <tr>
                            <td>$total_travel</td>
                            <td>$total_liters</td>
                            <td>$average</td>
                            <td>$average_distance</td>
                            <td>".formatcurrencypdf($total_amount)."</td>
                         </tr>
                </table><br>";
        
        
        $htmlContent.= " <h3>Vehicle Summary Report </h3>
                            <table align=\"center\" width=\"100%\">
                                <tr>
                                    <td ><strong>SNO</strong></td>
                                    <td ><strong>Travel Date</strong></td>
                                    <td ><strong>Distance [ KM ]</strong></td>
                                    <td ><strong>Fuel [ Liters ]</strong></td>
                                    <td ><strong>Fuel Price </strong></td>
                                    <td ><strong>AMOUNT</strong></td>
                                </tr>";
        $sno = 1;
        foreach ($distance as $key => $value) {
            $htmlContent.="<tr>
                               <td>$sno</td>
                               <td width=\"80\">" . date(('d-m-Y'), strtotime($value['travel_date'])) . "</td>
                               <td align=\"center\">" . ($value['end_meter'] - $value['start_meter']) . "</td>
                               <td>$value[liters]</td>
                               <td>$value[amount]</td>
                               <td>$value[final_amount]</td>
                               
                            </tr>";
            $sno++;
        }
       
        $htmlContent.="<tr>
                        <td>&nbsp;</td>
                        <td  colspan=4 style=text-align:center><strong>TOTAL AMOUNT</strong></td>
                        <td><strong></strong></td>
                    </tr>
                </table></body></html></page>";
        return $htmlContent;
    }
}

function getInstdetails() {
    $instsessassocid = $_SESSION['instsessassocid'];

    $sqlBranchDetail = " SELECT UPPER(institutename) as institutename,institutelogo, 
                            TRIM(instituteaddress1) as instituteaddress1 ,TRIM(instituteaddress2) as instituteaddress2,
                            institutephone1,instituteemail1 , instituteabbrevation
                            FROM tblinstitute as t1, 
                            tblinstsessassoc as t2 
                            WHERE t1.instituteid=t2.instituteid 
                            AND t2.instsessassocid=$instsessassocid ";

    $resBranch = dbSelect($sqlBranchDetail);
    $branchDetails = mysqli_fetch_assoc($resBranch);
    return $branchDetails;
}

function totalTravel() {

    $sql = "SELECT travel_date, vehicleid, start_meter, end_meter
            FROM tblvehiclemileage
            where vehicleid = $_GET[busid] ";
    if (isset($_GET['monthstart']) && !empty($_GET['monthstart'])) {
        $sql .= " AND travel_date >= '$_GET[monthstart] 00:00:00' ";
    }
    if (isset($_GET['monthend']) && !empty($_GET['monthend'])) {
        $sql .= " AND travel_date <= '$_GET[monthend] 23:59:59' ";
    }
    /*
      if (!empty($_GET['monthstart']) OR ( !empty($_GET['monthend']))) {
      $sql .= "AND travel_date BETWEEN '$_GET[monthstart] 00:00:00' AND '$_GET[monthend] 23:59:59'";
      } */

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $travel[] = $rows;
        }
        return $travel;
    }
}

/* this function calculates total fuel 
 * consumption and amount.
 * Made by: Sanjay Kumar 15 Sept 2016
 */

function totalFuel() {
    $sql = "SELECT `vehicleid`,`date_filled`,`liters`,`amount`
            from tblvehiclefuel
            where vehicleid = $_GET[busid] ";

    if (isset($_GET['monthstart']) && !empty($_GET['monthstart'])) {
        $sql .= " AND date_filled >= '$_GET[monthstart] 00:00:00' ";
    }
    if (isset($_GET['monthend']) && !empty($_GET['monthend'])) {
        $sql .= " AND date_filled <= '$_GET[monthend] 23:59:59' ";
    }
    /*
      if (!empty($_GET['monthstart']) OR ( !empty($_GET['monthend']))) {
      $sql .= "AND date_filled BETWEEN '$_GET[monthstart] 00:00:00' AND '$_GET[monthend] 23:59:59'";
      } */

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $fuel_intake[] = $rows;
        }

        return $fuel_intake;
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
       t1.vehicleid = $_GET[busid] 
       ";
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $busDetails = $rows;
        }
        return $busDetails;
    }
}

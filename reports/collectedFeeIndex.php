
<?php

    /*
    * 360 - School Empowerment System.
    * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
    * Page details here:
    * Updates here:
    */

    require_once "../config/config.php";
    require_once '../lib/reportfunctions.php';
    require_once DIR_FUNCTIONS;
    require_once VIEW_HEADER;
    
    if (isset($_GET) && !empty($_GET)) {
        $qryString= '&'.http_build_query(cleanvar($_GET));
    } else {
        $qryString='';
    }
?>

<script type='text/javascript' src='../asset/js/gs_sortable.js'></script>
<script type="text/javascript">
    function PrintElem(elem){ Popup($(elem).html()); }
    function Popup(data) 
    {
        var mywindow = window.open('', 'Collected Fee Report', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Collected Fee Report</title>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10
        mywindow.print();
        mywindow.close();
        return true;
    }
</script>

<div class="container">
    <div class="btn-group btn-group-justified">
        <a href="collectedFeeIndex.php?date=today&search=search" class="btn btn-success btn-lg">Today's Collection</a>
        <a href="collectedFeeIndex.php?date=start&search=search" class="btn btn-primary btn-lg">Collection From Start</a>
        <a href="collectedFeeIndex.php?date=prevmonth&search=search" class="btn btn-info btn-lg">Last Month Collection</a>
    </div>  
    
    <span class="clearfix">&nbsp;</span> 
     
    <div id="searchfrm" name="searchfrm">
        <?php include_once "searchstudentreportHTML.php" ?>
    </div>
    <span class="clearfix">&nbsp;</span>
    <span class="clearfix">&nbsp;</span>
    <?php 
    if (isset($_GET['search']) && $_GET['search']=='search') {
        global $netFeeCollection ;
        $searchTerm = cleanVar($_GET);
        $feeCollection = feeCollectionReport($searchTerm, 'due');
        
        $sno=1;
        $netFeeCollection=0;
        $feeAmountTotal = 0;
        $otherFeeTotal = 0;
        $refundAmt = 0; ?>
    <div id="showreport" name="showreport" >
        <?php if ($feeCollection!=0) {
            ?>
        <table class="table table-hover table-bordered ">
            <thead>
                <tr >
                    <th>Sno.</th>
                    <th>Scholar Number</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Date</th>
                    <th>Fee Receipt</th>
                    <th>Fee Amount</th>
                    <th>Other Fee</th>
                    <th>Refund Amount</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                foreach ($feeCollection as $key=>$value) {
                    $studentname = $value['firstname'].' '.$value['middlename'].' '.$value['lastname'];
                    $classname = $value['classname'].'-'.$value['sectionname'];
                    $refund = "<td>-</td>";
                    $dateCollected = strstr($value['remarks'], '2');
                    if($dateCollected != ''){
                        $dateCollected= date("d/m/Y", strtotime($dateCollected));
                    }
                    else{
                        $dateCollected = date("d/m/Y", strtotime($value['dated']));
                    }
                    
                    if (isset($value['refunded'])) {
                        $studentid = $value['studentid'];
                        $refund ="<td> <a href=\"../files/student/studentFeeDetails.php?sid=$studentid&mode=edit\">".formatCurrency($value['refunded'])."</td>" ;
                    } ?>
                <tr>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $sno; ?></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $value['scholarnumber']; ?></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo ucwords($studentname); ?></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $classname; ?></td>                    
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $dateCollected ; ?></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo $value['receiptid']; ?></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo(formatCurrency($value['feeamount'])); ?></td>
                    <td> <a href="../files/student/studentFeeDetails.php?sid=<?php echo $value['studentid']; ?>&mode=edit"> <?php echo(formatCurrency($value['otherfeeamount'])); ?></td>
                    <?php echo $refund ?>

                </tr>
            <?php $sno++;
                    $feeAmountTotal+= $value['feeamount'];
                    $otherFeeTotal += $value['otherfeeamount'];
                    $netFeeCollection += $value['feeamount']+  $value['otherfeeamount'];
                    if (isset($value['refunded'])) {
                        $refundAmt  += $value['refunded'];
                    }
                }
            $netFeeCollection -= $refundAmt ; ?>
                <tr class="info">
                    
                   
                    <td colspan="6" align="right"><strong>Gross Total</strong></td>
                    <td><strong><?php echo "Rs."."&nbsp;".formatCurrency($feeAmountTotal) ?></strong></td>
                    <td><strong><?php echo "Rs."."&nbsp;".formatCurrency($otherFeeTotal) ?></strong></td>
                    <td><strong><?php echo "Rs."."&nbsp;".formatCurrency($refundAmt) ?></strong></td>
                </tr>
                <tr>
                     <td colspan="8 " align="right"><strong>Net Total</strong></td>
                     <td ><strong><?php echo "Rs."."&nbsp;".formatCurrency($netFeeCollection) ?></strong></td>
                </tr>
            </tbody>
            
        </table>
        
        <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
            <a href="collectedFeePDF.php?action=pdf<?php echo $qryString; ?>"> 
            <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
            <a href="collectedFeePDF.php?action=xls<?php echo $qryString; ?>"> 
            <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
        </div>
        <div class="col-lg-6" style="text-align: right; padding-right: 0px;">
            <?php getPagination($totalrows, ROW_PER_PAGE); ?>
        </div>
        <?php 
        } else {
            ?> 
        <div class="alert alert-danger"><p> No record(s) found for your selected crieteria. Please change the search criteria and try again ! </p></div>    
        <?php 
        } ?> 
    </div>
    <?php 
    } ?>
</div>

<?php

 

require VIEW_FOOTER;

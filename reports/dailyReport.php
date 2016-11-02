<?php
/*

 * Prepare the daily report of the Collection
 * Made By: Sanjay kumar Chaurasia
 * Date: 01 Aug 2016
 */

require_once '../config/config.php';
require_once '../lib/reportfunctions.php';
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;



if (isset($_GET) && !empty($_GET)) {
    $qryString = '&' . http_build_query(cleanvar($_GET));
} else {
    $qryString = '';
}

?>

<div class="container">
    <?php renderMsg(); ?>

    <div class="col-lg-12"> 
        <form action="" method="GET" >
            <div class="col-lg-1"></div>
            <div  class="col-lg-3">
                <div class="input-group">
                    <span class="input-group-addon">Select Date</span>
                    <input type="date" name="monthstart" id="monthstart" class="form-control" tabindex="7" >
                </div>
            </div>

            <div class="controls" align="right">
                <div class='col-lg-3'>
                    <button type='reset' value="Reset" class="btn " >Cancel</button>
                    <button name='search' value="search" class="btn btn-success" >Search</button>
                </div>
            </div>

        </form><?php
        if (isset($_GET['monthstart'])) {
            $datecreated = $_GET['monthstart'];
        } else {
            $datecreated = date('Y-m-d');
        }
        ?>
    </div><span class='clearfix'>&nbsp;&nbsp;<br></span>
    <p align="center" class="h3">Daily Transaction Report
        <?php echo '[ ' . $datecreated . ' ]' ?></p>
    <?php
    $searchTerm = cleanVar($_GET);
    $report = feeCollectionReport($searchTerm, '');
    $collect = getAddition($datecreated);
    $student = getStudent($datecreated);
    $tc = getTc($datecreated);
    $cheque = getCheque($datecreated);
    $other = otherFee($datecreated);
    $refunddetails = getstudentDetails($datecreated);
    $cheqBounce = getchequeBounce($datecreated);
    
    foreach ($cheque as $value) {
        $chequeCount = $value;
    }
    ?>
    <div class="col-lg-1"></div>
    <div class="col-lg-10">
        <span class="clearfix"><br></span>
        <!-- table for total collection component wise -->
        <?php
        $grandTotal = 0;
        if (isset($collect)) {
            foreach ($collect as $value) {
                $grandTotal += $value['total'];
            }
        }
        if (isset($tc)) {
            foreach ($tc as $value) {
                $grandTotal +=$value['total'];
            }
        }
        if (isset($cheqBounce)) {
            foreach ($cheqBounce as $value) {
                $grandTotal +=$value;
            }
        }
        ?>
        <!-- table for grand total -->
        <table class="table table-striped table-hover" border="3">
            <tr>
                <td colspan="2"><strong>Total Collection</strong></td>
                <td><strong><?php echo formatCurrency($grandTotal); ?></strong></td>
            </tr>
            <tr>
                <?php
                if (isset($collect)) {
                    foreach ($collect as $value) {
                        ?>
                        <td>
                            <?php
                            if ($value['collectionname'] === 'CHEQUE') {
                                $collect = $value['collectionname'] . ' [' . $chequeCount . ']';
                            } else {
                                $collect = $value['collectionname'];
                            }
                        echo '<i class="fa fa-arrow-right" aria-hidden="true"></i> TOTAL ' . $collect; ?>
                        </td> 
                        <td><?php echo formatCurrency($value['total']); ?></td>
                        <td></td>
                    </tr>
                    <?php

                    }
                }
            ?>
            <tr>
                <?php
                if (isset($student)) {
                    foreach ($student as $value) {
                        ?>
                        <td><i class="fa fa-arrow-right" aria-hidden="true"></i> New Admission<?php echo ' [' . $student['totalstudent'] . ']'; ?></td>

                    <?php 
                    }
                }
                ?><td></td><td></td>
            </tr>
            <!-- TC Issued Section ---------------------------->            
            <tr>
                <?php
                if (isset($tc)) {
                    foreach ($tc as $value) {
                        ?>
                        <td> <i class="fa fa-arrow-right" aria-hidden="true"></i> TC Issued <?php echo ' [' . $value['TC'] . ']'; ?></td>
                        <td><?php echo formatCurrency($value['total']); ?> </td>
                        <td></td>
                        <?php

                    }
                }
                ?>
            </tr>
            <?php
            if (isset($other)) {
                foreach ($other as $value) {
                    ?> <tr>
                        <td ><?php echo '<i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $value['otherfeehead'] ?></td>
                        <td><?php echo formatCurrency($value['total']); ?></td>

                        <td></td> </tr>
                    <?php

                }
            }
            ?>
                    <tr>
                    <?php
            if (isset($cheqBounce)) {
                foreach ($cheqBounce as $value) {
                    ?> 
                        <td ><?php echo '<i class="fa fa-arrow-right" aria-hidden="true"></i> Cheque Bounce'  ?></td>
                        <td><?php echo formatCurrency($value); ?></td>

                        <td></td> 
            <?php 
                }
            } ?>
                
                        </tr>

            <?php
            if (isset($refunddetails)) {
                echo "<tr><td colspan=\"2\"><strong>Refund Amount : </strong></td>
                <td><strong>" . '- ' . formatCurrency($refunddetails);
                "</strong></td>
                     <tr>";
            }
            ?>
            <tr>
                <td colspan="2"><strong>Grand total</strong></td>
                <td><strong><?php echo formatCurrency($grandTotal - $refunddetails); ?></strong></td>
            </tr>
        </table>

        <span class="clearfix"><br></span>
        <div class="col-lg-6" style="text-align: left; padding-left: 0px;">
            <a href="dailyReportPDF.php?action=pdf<?php echo $qryString; ?>"> 
                <input type="button" id="pdfreport"  name="pdfreport" class="btn btn-success"  value=" View PDF"></a>
            <a href="dailyReportPDF.php?action=xls<?php echo $qryString; ?>"> 
                <input type="button" id="excelreport"  name="excelreport" class="btn btn-info"  value=" View EXCEL"></a>
        </div>
        <!-- table for Refund amount -->
    </div>
</div>

<?php
require_once VIEW_FOOTER;
?>
<?php
/* funtion to calculate other fee charges */

function getchequeBounce($seachterm)
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = "SELECT SUM(amount) as amount from tblotherfeepenalties WHERE
    datecreated BETWEEN '$seachterm 00:00:00' AND '$seachterm 23:59:59'
    AND instsessassocid = $instsessassocid";
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $chqbounce = $row;
        }
        return $chqbounce;
    }
}

function otherFee($seachterm)
{
    $sql = "SELECT SUM(t1.feeinstallmentamount) as total, t1.collectiontype, t1.datecreated, 
	   t2.feeotherchargesid, t2.otherfeehead
       
       from tblfeecollectiondetail as t1,
       tblfeeothercharges as t2
       
       where t1.datecreated BETWEEN '$seachterm 00:00:00' AND '$seachterm 23:55:55' AND
       t1.collectiontype = t2.feeotherchargesid 
       GROUP BY t2.otherfeehead";
    
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $others[] = $row;
        }
        return $others;
    }
}

/* funtion to get total no of cheque on that day */

function getCheque($searchterm)
{
    $sql = "SELECT COUNT(feechecqueid) as cheque from tblfeecheque
            where datecreated BETWEEN 
            '$searchterm 00:00:00' AND '$searchterm 23:59:59'";
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cheque = $row;
        }
        return $cheque;
    }
}

/* funtion to get the tc details of the date */

function getTc($searchterm)
{
    $sql = "SELECT COUNT(studentid) as TC , SUM(amount) as total from tblstudtc where dateofissue = '$searchterm'";
    
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tc[] = $row;
        }
        return $tc;
    }
}

/* funtion to the addtion of the collected fee by cheque and cash */

function getAddition($searchterm)
{
    $amount = null;
    $sql = "SELECT SUM(t1.feeinstallmentamount) as total, t1.feemodeid, t1.datecreated, 
                t2.collectionname
                from tblfeecollectiondetail as t1, tblmastercollection as t2
                WHERE t1.datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'
                AND t2.mastercollectionid = t1.feemodeid
                AND t1.feestatus = 1
                GROUP BY t1.feemodeid";
    
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $amount[] = $row;
        }
    }
    return $amount;
}

/* funtion to get the total no of student took admission */

function getStudent($searchterm)
{
    $sql = "SELECT COUNT(studentid) as totalstudent from tblstudent
	where datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'";
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $student = $row;
        }
        return $student;
    }
}

function getstudentDetails($searchterm)
{
    $totalrefamount = 0;
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = " SELECT t3.classid, t4.sectionid, t8.studentid , t9.datecreated
          
        FROM `tblstudent` AS t1,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS  t5,
        `tblstudentacademichistory` AS t6,
        `tblparent` AS t7,
        `tblfeecollection` AS t8,
        `tblfeecollectiondetail` AS t9
        
          
        WHERE t1.instsessassocid = $instsessassocid
        AND t9.datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'
        AND t1.studentid = t6.studentid
        AND t6.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t8.studentid
        AND t8.feecollectionid = t9.feecollectionid
         
        GROUP BY t1.studentid
        
        ";
    
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $studentdetails[] = $rows;
        }
        foreach ($studentdetails as $value) {
            $refundamt[] = getRefDetails($value['studentid'], $searchterm);
        }
        if (isset($refundamt)) {
            foreach ($refundamt as $value) {
                $totalrefamount += $value['total'];
            }
        }
        return $totalrefamount;
    }
}

function getRefDetails($studentid, $searchterm)
{
    $sql = "SELECT t1.feecollectionid, t1.studentid, t1.receiptid,
            t2.feecollectiondetailid, SUM(t2.feeinstallmentamount) as total,
            t3.feerefundrecieptno, t3.datecreated

            FROM `tblfeecollection` AS t1,
            `tblfeecollectiondetail` AS t2,
            `tblfeerefund` AS t3

            WHERE t1.studentid = '$studentid'
            AND t1.feecollectionid = t2.feecollectionid
            AND t2.feecollectiondetailid = t3.feecollectiondetailid
            AND t3.datecreated BETWEEN '$searchterm 00:00:00' AND '$searchterm 23:59:59'
";
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $studentRefund = $rows;
        }
        return $studentRefund;
    }
}
?>
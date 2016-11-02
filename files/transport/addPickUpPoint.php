<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new subjects
 * Updates here:
 */


//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;

if (isset($_GET['mode']) && $_GET['mode']=='delete') {
    if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {
        $sqlUpdate = " UPDATE tblpickuppoint SET deleted=1 WHERE pickuppointid='".cleanVar($_GET['delid'])."'";
        dbUpdate($sqlUpdate);
        echo "<script>window.location='addpickuppoint.php?s=1'</script>";
    }
}

if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
    if (isset($_GET['status']) && is_numeric($_GET['status'])) {
        $result=  statusUpdate('tblpickuppoint', cleanVar($_GET['status']), " pickuppointid='".cleanVar($_GET['pid'])."'");
        if ($result) {
            echo "<script>window.location='addPickUpPoint.php'</script>";
        }
    }
}

$pickupDetails = getPickPointList();
$sno=1;
$page=(isset($_GET['page'])) ? cleanVar($_GET['page']) :1;

$mode=(isset($_GET['mode']) && !empty($_GET['mode'])) ? 'edit' : 'add';


?>
<script lang="javascript">
$(document).ready(function() 
{

    $(function()
    {
        <?php if (isset($_GET['edid']) && !(isset($_GET['mode']) === 'edit')) {
    ?>
        $( "#addpickuppoint" ).show();
        $("#showlist").hide();
        $('#addpickup, #showpickup').click(function()
        {
            $('#addpickuppoint').toggle(200);
            $('#showlist').toggle(200); 
        });
        <?php 
} else {
    ?>        
        $("#showlist").show();
        $( "#addpickuppoint" ).hide();
            
        $('#addpickup, #showpickup').click(function()
        {
                $('#addpickuppoint').toggle(200);
                $('#showlist').toggle(200); 
        });
            
        <?php 
} ?>
                  
    });
});
</script>
<div class="container" >
    <?php renderMsg(); ?>
    <div id="showlist">
        <?php if ($pickupDetails!=0) {
    ?>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th >SNo.</th>
                    <th> Pickup Point Name</th>
                    <th> Suburb Name</th>
                    <th> Pickup Time</th>
                    <th> Drop Time</th>
                    <th> Amount </th>

                    <th> Update </th>
                    <th> Delete </th>
                    <th> Status </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pickupDetails['records'] as $key=>$value) {
        if ($value['status']==1) {
            $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
        } else {
            $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
        } ?>
                <tr>

                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><?php echo $sno ?> </a></td>
                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><?php echo  ucwords($value['pickuppointname'])?> </a> </td>
                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><?php echo  ucwords($value['suburbname'])?>   </a>   </td>
                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><?php echo  $value['pickuptime']?>  </a>    </td>
                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><?php echo  $value['droptime']?>  </a>   </td>
                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><?php echo "Rs. " . formatCurrency($value['amount'])?>     </a>     </td>

                    <td> <a href="addPickUpPoint.php?edid=<?php echo $value['pickuppointid']; ?>&mode=edit"><span class="glyphicon glyphicon-edit"></span></a></td>
                    <td> <a href="addPickUpPoint.php?delid=<?php echo $value['pickuppointid']; ?>&mode=delete"><span class="glyphicon glyphicon-trash"></span></a></td>
                    <td> <a href="addPickUpPoint.php?pid=<?=$value['pickuppointid']?>&status=<?=$value['status']?>&page=<?=$page?>" class=""><span <?=$statusStyle?>></span></a> </td>
                </tr>
                 <?php $sno++;
    } ?>
            </tbody>
        </table>
        
            <div class="col-lg-12">
                <div class="col-lg-6" style="padding-left: 0px; text-align: left"> <button class="btn btn-success" type="button" name="addpickup" id="addpickup">Add Pickup Point</button></div>
                <div class="col-lg-6" style="padding-right: 0px; text-align: right"> <?php getPagination($totalrows, ROW_PER_PAGE); ?></div>
           </div>
        <?php 
} else {
    ?>
            <div class="alert alert-danger">
        <p> No record(s) found. </p>
         </div>
        <?php 
} ?>
        
    </div>
    
    <div id="addpickuppoint">
        <form action="<?php echo PROCESS_FORM; ?>" method="post" enctype="multipart/form-data" >
            <?php if (isset($mode) && $mode=='edit') {
    $PickupEditDetails = getPickPointDetails(); ?>
            <input type="hidden" name="edid" value="<?php echo cleanVar($_GET['edid']); ?>" id="edid">
            <?php 
} ?>
            <input type="hidden" name="mode" value="<?php echo $mode?>" id="mode"> 
             
            <h1> Add Pick-Up Point</h1>
            
             
            <div class="col-lg-4">
                <label for="pickuppointname" >Pick-up Point Name</label>
                <input type="text" name="pickuppointname" id="pickuppointname" 
                       value="<?php if (isset($PickupEditDetails['pickuppointname']) && !empty($PickupEditDetails['pickuppointname'])) {
    echo $PickupEditDetails['pickuppointname'];
} else {
    submitFailFieldValue("pickuppointname");
}?>" class="form-control" required>
            </div>
            <div class="col-lg-4">
                <label for="suburbid"> Suburb Name </label>
                <select name="suburbid" id="suburbid"  class="form-control" required>
                    <?php 
                    if (isset($PickupEditDetails['suburbs']) && !empty($PickupEditDetails['suburbs'])) {
                        echo populateSelect('currentsuburb', $PickupEditDetails['suburbs']);
                    } else {
                        echo populateSelect('currentsuburb', submitFailFieldValue("suburbid"));
                    }
                    ?>
                    
                </select>
            </div>
            <div class="col-lg-4">
                <label for="amount">Amount </label>
                <input type="text" name="amount" id="amount" 
                       value="<?php if (isset($PickupEditDetails['amount']) && !empty($PickupEditDetails['amount'])) {
                        echo $PickupEditDetails['amount'];
                    } else {
                        submitFailFieldValue("amount");
                    }?>" 
                       class="form-control" required>
                       
            </div>
            <div class="clearfix">&nbsp;</div>
            
            <div class="col-lg-4">
                <label for="pickuptime">Pick Up Time</label>
                <input type="text" name="pickuptime" id="pickuptime" class="form-control" placeholder="HH : MM"
                value="<?php if (isset($PickupEditDetails['pickuptime']) && !empty($PickupEditDetails['pickuptime'])) {
                        echo $PickupEditDetails['pickuptime'];
                    } else {
                        submitFailFieldValue("pickuptime");
                    }?>" 
                >
                
            </div>
            <div class="col-lg-4">
                <label for="droptime">Drop  Time</label>
                <input type="text" name="droptime" id="droptime" class="form-control" placeholder="HH : MM" 
                 value="<?php if (isset($PickupEditDetails['droptime']) && !empty($PickupEditDetails['droptime'])) {
                        echo $PickupEditDetails['droptime'];
                    } else {
                        submitFailFieldValue("droptime");
                    }?>"        
                >
                
            </div>
            
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-lg-4">
                <input type="button" name="showpickup" id="showpickup" value="Show Pickup Points" class="btn btn-success">
                <input type="reset" name="cancel" id="cancel" value="Cancel" class="btn btn-default">
                       
                <input type="submit" name="save" id="save" value="Save" class="btn btn-success">
            </div>  
        </form>
    </div>
</div>
<?php
require VIEW_FOOTER;

function getPickPointList()
{
    global $totalrows;
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
    $limit="LIMIT ".$startPage.','.ROW_PER_PAGE;

    $sqlPickUpPoint = " SELECT t1.pickuppointid,LOWER(t1.pickuppointname) as pickuppointname,t1.amount, LOWER(t2.collectionname) as suburbname ,t1.status,
                        TIME_FORMAT(t1.pickuptime,'%H:%i') as pickuptime,TIME_FORMAT(t1.droptime,'%H:%i') as droptime
                        FROM tblpickuppoint as t1, tblmastercollection as t2 WHERE 
                        t1.suburbs=t2.mastercollectionid AND t1.deleted=0 ORDER BY t1.pickuppointname ";
    
    $resPickUpPoint = dbSelect($sqlPickUpPoint.$limit);
    $resRowCount=  dbSelect($sqlPickUpPoint);
    
    
    if (mysqli_num_rows($resPickUpPoint)>0) {
        while ($row=mysqli_fetch_assoc($resPickUpPoint)) {
            $pickArray['records'][]=$row;
        }
               
        
        $totalrows=mysqli_num_rows($resRowCount);
        return $pickArray;
    } else {
        return 0;
    }
}

function getPickPointDetails()
{
    if (isset($_GET['mode']) && $_GET['mode']=='edit' && isset($_GET['edid'])) {
        $pickupid= cleanVar($_GET['edid']);
        $sqlPickupDetail = " SELECT * FROM tblpickuppoint WHERE pickuppointid= $pickupid AND deleted=0  ";
        $resPickupDetail = dbSelect($sqlPickupDetail);
        if (mysqli_num_rows($resPickupDetail)>0) {
            $row=mysqli_fetch_assoc($resPickupDetail);
            return $row;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

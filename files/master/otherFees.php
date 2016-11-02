<?php
    /*
     * 360 - School Empowerment System.
     * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
     * Page details here: Page to add new subjects
     * Updates here:
     */

    /* assign if selectize needs to be loaded for this page */
    $loadSelectize = rtrim(basename($_SERVER['PHP_SELF']), '.php');
    /* Selectize load bool ends */

    //call the main config file, functions file and header
    require_once "../../config/config.php";
    require_once DIR_FUNCTIONS;
    
    $response = actionDelete();
    
    require_once VIEW_HEADER;
    
    
     
    $sno = (int)(isset($_GET['page']) ?  (($_GET['page']-1) * ROW_PER_PAGE) + 1 : 1);
    $page = (int)(isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1);
    
    if (isset($_GET['status']) && is_numeric($_GET['status'])) {
        $result=  statusUpdate('tblfeeothercharges', $_GET['status'], "feeotherchargesid=$_GET[sid]");
        if ($result) {
            header('Location:'.$_SERVER['PHP_SELF'].'?s=35');
        }
    }
?>
<script type="text/javascript">
    $(document).ready(function()
    {
        $("input[name='isperiodic']").click(function()
        {
           if(this.value=='1')
               $('#frequency').prop("disabled",false);
           else
               $('#frequency').prop("disabled",true);
        });
    
        <?php if (!isset($_GET['edid'])) {
    ?>
            $("#showOtherFees").show();
            $( "#addOtherFees" ).hide();

            $('#addotherfees, #showotherfees').click(function()
            {
                $('#addOtherFees').toggle(200);
                $('#showOtherFees').toggle(200); 
            });
  <?php 
} else {
    ?>        
            $( "#addOtherFees" ).show();
            $("#showOtherFees").hide();

            $('#addotherfees, #showotherfees').click(function()
            {
                $('#addOtherFees').toggle(200);
                $('#showOtherFees').toggle(200); 
            });
    <?php 
} ?>
    });


$(document).ready(function() 
{
    // Instance the tour
    $("#tourhelp").click(function()
    {
        var tour = new Tour({
	steps: [
		{
		element: "#forotherfeeheadTour",
		title: " Enter other fee head name here",
		content: "Enter other fee head name here",
		placement: "right"
		},
		{
		element: "#forAmountTour",
		title: "Enter the amount for the fee head",
		content: "Enter the amount for the fee head",
		placement: "right"
		},
		
		{
		element: "#forfrequencyTour",
		title: "Select the frequency for fee head",
		content: "Select the frequency for fee head",
		placement: "right"
		},
		
		{
		element: "#forchargemodeTour",
		title: "Select charging mode for fee head created",
		content: "Select charging mode for fee head created",
		placement: "left"
		},
		
		
		
		{
		element: "#fordescriptionTour",
		title: "Description for fee head",
		content: "Please enter a small description for breifing the fee head created ",
		placement: "right"
		}
		
	]
	}); 
    
	tour.init();
	tour.restart();
 });
});

function displayErrorJS(err){ 
	var errMsg = [];
	errMsg[0] = "About <?php echo $response ?>  student are affected by this other fees. Deleting this record will remove this rule from these student. click Yes to continue.";
	errMsg[1] = "Do you want to delete this particular fee  ?  click Yes to confirm ...!";
	
	var strModal = '<div id="jsErrorAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'.concat(
	'<div class="modal-dialog"><div class="modal-content"><div class="modal-header"></strong>Attention..!</strong></div>',
	'<div class="modal-body"><div class="alert alert-danger alert-dismissible fade in" role="alert">',
	 errMsg[err]+'</div></div>',
	 '<div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal" onClick="Javascript: confirmDelete();">Yes</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div></div></div></div>');
	
	$(strModal).appendTo('body');
	$('#jsErrorAlert').modal('toggle');
}


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function confirmDelete(){
    var id = getParameterByName('delid');
    var url = "otherFees.php?c=" + id;
        window.location.replace(url);
}
 </script>
<?php
    if (!empty($response)) {
        echo('<script type="text/javascript"> displayErrorJS(0);</script>');
    } elseif (!empty($_GET['delid'])) {
        echo('<script type="text/javascript"> displayErrorJS(1);</script>');
    }

    if (wasFormSubmit() && !empty($errorArray)) {
        echo "<script>$(function(){ $( '#showOtherFees' ).hide(); $( '#addOtherFees' ).show(); });</script>";
    }
?>
 
<div class="container" id='showOtherFees'>
    <?php 
        renderMsg();
        $otherFeeDetails =  showOtherFees();
        if (!empty($countRows) && $countRows>0) {
            ?>    
    <table class="table table-bordered table-hover" > 
        <thead>
        <tr>
            <th>SNo</th>
            <th>Other Fee Name</th>
            <th>Description</th>
            <th>Amount</th>
            <th  style="text-align: center">More Options</th>
        </tr>
        </thead>		
        <?php 
        $i = 1;
            foreach ($otherFeeDetails as $key => $value) {
                if ($value['status']==1) {
                    $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
                } else {
                    $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
                } ?>
        <tr>
            <td> <a href="otherFees.php?edid=<?php echo $value['feeotherchargesid']; ?>"><?php echo $i ?> </a></td>
            <td> <a href="otherFees.php?edid=<?php echo $value['feeotherchargesid']; ?>"><?php echo ucwords(strtolower($value['otherfeehead'])); ?> </a></td>
            <td> <a href="otherFees.php?edid=<?php echo $value['feeotherchargesid']; ?>"><?php echo ucwords(strtolower($value['description'])); ?> </a></td>
            <td> <a href="otherFees.php?edid=<?php echo $value['feeotherchargesid']; ?>"><?php echo(formatCurrency($value['amount'])); ?> </a></td>
            <td width="130"><?php echo hoverList($value['feeotherchargesid'], $value['status'], '')?></td>
        </tr> 
        <?php $i++;
            } ?>
    </table>
    <?php 
        } else {
            ?>
        <div class="alert alert-warning">
            <span class="text-info"> 
                There is no Other Fee Rule / Charges added yet. Please add Other Fee Charges by clicking "Add Other Fees " button below.
            </span> 
        </div>
     <?php 
        }
    ?>
    
    <div class="col-lg-6 col-md-6" style="text-align: left; padding: 0px">
        <button type="button" id='addotherfees' class="btn btn-success" >Add Other Fees</button>
    </div>
    <div class="col-lg-6 col-md-6" style="text-align: right; padding: 0px">
        <?php getPagination($countRows, ROW_PER_PAGE); ?>
    </div>
</div>	

<div class="container" id='addOtherFees'>   
    
    <?php renderMsg(); ?>
    <div class="alert alert-dismissable alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Enter</strong>Other Fee Head , Amount and Description . (<a href="Javascript:void(0);" id="tourhelp">show me!</a>)
    </div>
    
    <form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">   
        <input type="hidden" name="otherfeetype" id="otherfeetype" value="262">
        
        <?php if (isset($_GET['edid'])) {
        $feeOtherDetails = showOtherFeesDetails(); ?>
        <input type="hidden" name="edid" id="edid" value="<?php echo(cleanVar($_GET['edid'])); ?>">
        <?php 
    } ?>
        
        <div class="btn-group btn-group-justified">
            <a href="feeMaster.php" class="btn btn-primary btn-lg">Create Fee Structure</a>
            <a href="feeRule.php" class="btn btn-success btn-lg">Create Fee Rules</a>
            <a href="otherFees.php" class="btn btn-info btn-lg">Create Other Fees</a>
        </div>   

        <h1>Create Other Fees</h1>
        <div class="col-lg-4 col-md-4">
            <label for="otherfeehead">Other Fee Head</label><div id="forotherfeeheadTour"></div>
            <input type="text" name="otherfeehead" id="otherfeehead" maxlength="50" class="form-control" 
                    value="<?php if (!empty($feeOtherDetails)) {
        echo $feeOtherDetails['otherfeehead'];
    } else {
                                    echo submitFailFieldValue("otherfeehead");
                                } ?>"   required="true">
            <small>For example, Late Fee, Penalties, Misc amounts etc. </small>
            <div class="hidden" name="divotherfeehead" id="divotherfeehead"><p class="text-danger">A valid, textual Other Fee Head is required!</p></div>
        </div>
        
        <div class="col-lg-4 col-md-4">
            <label for="amount"> Amount</label><div id="forAmountTour"></div>
            <input type="text" name="amount" id="amount" class="form-control" 
                    value ="<?php if (!empty($feeOtherDetails)) {
                                    echo $feeOtherDetails['amount'];
                                } else {
                                     echo submitFailFieldValue("amount");
                                 }?>">
            <div class="hidden" name="divamount" id="divamount">
                <p class="text-danger">Amount/Percentage is required!</p>
            </div>
        </div>

        <div class="col-lg-4 col-md-4">
            <label for="frequency">Frequency</label><div id="forfrequencyTour"></div>
            <select name="frequency" id="frequency" class="form-control" disabled="true" > 
            <?php 
            if (!empty($feeOtherDetails)) {
                echo populateSelect('feedepositeperiod', $feeOtherDetails['frequency']);
            } else {
                echo populateSelect('feedepositeperiod', submitFailFieldValue('frequency'));
            }
            ?>
            </select>
        </div>
        
        <span class="clearfix">&nbsp;<br></span>
        
        <div class="col-lg-4 col-md-4">
            <label for="periodic">Periodic</label><div id="forperiodicTour"></div>
            <div class="input-group">
                <span class="input-group-addon"> 
                    <input type="radio" name="isperiodic" id="isperiodic" value="1" > Yes
                </span>
                <span class="input-group-addon"> <input type="radio" name="isperiodic" id="isperiodic" value="0" > No</span>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4">
            <label for="status">Status</label><br>
            <div class="input-group">
                <span class="input-group-addon">
                    <input type="radio" name="status" id="status" value="1" checked="checked" > Active &nbsp; &nbsp;
                </span>
                <span class="input-group-addon">
                    <input type="radio" name="status" id="status" value="0"> In-Active    
                </span>
            </div> 
        </div>
                
        <div class="col-lg-4 col-md-4">
            <label for="chargemode">Format</label><div id="forchargemodeTour"></div>
            <div id="chargemode" class="input-group">
                <span class="input-group-addon">
                    <input type="radio" name="chargemode" id="chargemode" 
                            value ="<?php if (!empty($feeOtherDetails)) {
                echo $feeOtherDetails['chargemode'];
            } else {
                                            $modeid = feerulemode('Percent');
                                            echo $modeid ;
                                        }?>" /> Percent &nbsp;
                </span>
                <span class="input-group-addon">
                    <input type="radio" name="chargemode" id="chargemode" 
                    value ="<?php if (!empty($feeOtherDetails)) {
                                            echo $feeOtherDetails['chargemode'];
                                        } else {
                                    $modeid = feerulemode('Flat');
                                    echo $modeid ;
                                } ?>" /> Flat &nbsp; &nbsp; &nbsp; 
                </span>
            </div>             
        </div>
        <span class="clearfix">&nbsp;<br></span>
        <div class="col-lg-4 col-md-4">
            <label for="description"> Rule Description </label><div id="fordescriptionTour"></div>
            <textarea class="form-control" rows="1" name="description" id="description" required="true">
            <?php 
                if (!empty($feeOtherDetails)) {
                    echo $feeOtherDetails['description'];
                } else {
                    echo submitFailFieldValue("description");
                }
            ?>
            </textarea>
            <div class="hidden" name="divdescription" id="divdescription">
                <p class="text-danger">Remarks about the fee description is required!</p>
            </div>
        </div> 
        
        <span class="clearfix">&nbsp;<br/><br/></span>
        
        <div class="controls" align="center">
            <input type="button" id="showotherfees" name="showotherfees" value="Show Other Fees" class="btn btn-success">
            <input id="cancel" type="button" value="Cancel" class="btn">
            <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success">
        </div>
        
        
        
    </form>
    
 </div>
<?php
require VIEW_FOOTER;

function actionDelete()
{
    if (isset($_GET['delid'])) {
        $feeruleid = cleanVar($_GET['delid']);
        $sql = "SELECT LOWER(t1.otherfeehead) as otherfeehead    FROM `tblfeeothercharges` AS t1,`tblfeeotherchargesdetails`  AS t2
                WHERE t1.feeotherchargesid = $_GET[delid] AND t1.feeotherchargesid = t2.feeotherchargesid ";
            
        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);
        $otherfeehead =  $row['otherfeehead'];
        return $otherfeehead;
    } elseif (isset($_GET['c'])) {
        $feeruleid = cleanVar($_GET['c']);
        $sql = "UPDATE `tblfeeothercharges` SET `tblfeeothercharges`.status= 0 ,`tblfeeothercharges`.deleted = 1
                WHERE `tblfeeothercharges`.feeotherchargesid= $_GET[c]";
        if ($result = dbUpdate($sql)) {
            header("Location: otherFees.php?s=18");
            exit;
        }
    }
}

function showOtherFeesDetails()
{
    $feeotherchargesid = cleanVar($_GET['edid']);
    $instsessassocid = $_SESSION['instsessassocid'];
    
    $sql = "SELECT *  FROM `tblfeeothercharges` AS t1, `tblfeeotherchargesdetails` AS t2
            WHERE t1.instsessassocid = $instsessassocid AND t1.feeotherchargesid = $feeotherchargesid
            AND t1.feeotherchargesid = t2.feeotherchargesid AND t1.deleted = 0";
    
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $otherFeeDetails = $row;
    }
    return $otherFeeDetails;
}

function showOtherFees()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    $otherFeeDetails =  array();
    if (!isset($_GET['page'])) {
        $startpage = 0;
    } else {
        $startpage = ($_GET['page'] - 1) * 10;
    }
    
    global  $countRows;
    $countRows = mysqli_num_rows(dbSelect("SELECT *  FROM `tblfeeothercharges` AS t1, `tblfeeotherchargesdetails` AS t2 "
            . "WHERE t1.instsessassocid = $instsessassocid AND t1.feeotherchargesid = t2.feeotherchargesid AND t1.deleted !=1 "));
    
    $sql = "SELECT *  FROM `tblfeeothercharges` AS t1,`tblfeeotherchargesdetails` AS t2 "
        . " WHERE t1.instsessassocid = $instsessassocid AND t1.feeotherchargesid = t2.feeotherchargesid AND t1.deleted != 1
            LIMIT $startpage ,".ROW_PER_PAGE;
        
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $otherFeeDetails[] = $row;
    }
    return $otherFeeDetails;
}

function feerulemode($modename)
{
    $sql = "SELECT t1.mastercollectionid FROM `tblmastercollection` AS t1, `tblmastercollectiontype` AS t2
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid AND t2.mastercollectiontype = 'Feerulemode'
            AND t1.collectionname = '$modename'";
    $result = dbSelect($sql);
    $row =  mysqli_fetch_assoc($result);
    return $row['mastercollectionid'] ;
}

function feeruletype($type)
{
    $sql = "SELECT t1.mastercollectionid FROM `tblmastercollection` AS t1, `tblmastercollectiontype` AS t2 
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid AND t2.mastercollectiontype = 'Feeruletype'
            AND t1.collectionname = '$type'";
   
    $result = dbSelect($sql);
    $row =  mysqli_fetch_assoc($result);
   
    return $row['mastercollectionid'] ;
}
?>
<!-- 
     <td> <a href="otherFees.php?edid=<?php //echo $value['feeotherchargesid'];?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td> <a href="otherFees.php?delid=<?php //echo $value['feeotherchargesid'];?>"><span class="glyphicon glyphicon-trash"></span></a></td>
            <td> <a href="otherFees.php?status=<?php //echo $value['status'];?>&sid=<?php //echo $value['feeotherchargesid'];?>&page=<?=$page?>" <?php echo $statusStyle;?> ></a></td>
        -->
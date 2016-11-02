<?php
    /*
     * 360 - School Empowerment System.
     * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
     * Page details here: Master for fees head and related processing
     * Updates here:
     */
    //call the main config file, functions file and header
    require_once "../../config/config.php";
    require_once DIR_FUNCTIONS;

    $response = actionDelete();
    
    if (isset($_GET['status']) && is_numeric($_GET['status'])) {
        $setStatus=($_GET['status']==1 ? 0 : 1);
        $sql="UPDATE tblfeerule SET feerulestatus=".$setStatus." WHERE feeruleid=".cleanVar($_GET['sid']);
        $result=  dbUpdate($sql);
        if ($result) {
            header('Location:'.$_SERVER['PHP_SELF'].'?s=32');
        }
    }
    
    require_once VIEW_HEADER;
    
?>

<script>
if( <?php if (isset($_GET['edid'])) {
    echo '1';
} else {
    echo '0';
}?>)
{
    $(document).ready(function() 
    {       
        $( "#addFeeRule" ).show();
        $("#showFeeRuleList").hide();
        $('#addrule, #showrule').click(function()
        {
            $('#addFeeRule').toggle(200);
            $('#showFeeRuleList').toggle(200); 
        });
        
    });
}
else
{
    $(document).ready(function() 
    {       
        $( "#showFeeRuleList").show();
        $( "#addFeeRule" ).hide();
        $( "#addrule, #showrule").click(function()
        {
            $('#addFeeRule').toggle(200);
            $('#showFeeRuleList').toggle(200); 
        });
    });
}

$(document).ready(function() 
{
    // Instance the tour
    $("#tourhelp").click(function()
    {
        var tour = new Tour({
	steps: [
		{
		element: "#forFeeruleTour",
		title: " Fee Rule",
		content: "Please enter a rule name to be created",
		placement: "right"
		},
		{
		element: "#forFeeComponentTour",
		title: "Select fee component",
		content: "Select fee component on which rule to be applied  ",
		placement: "right"
		},
		
		{
		element: "#forAmountTour",
		title: "Enter amount",
		content: "Enter amount for deduction from fee component",
		placement: "right"
		},
		
		{
		element: "#forRuleModeTour",
		title: "Select mode for rule created",
		content: "Select mode for rule created",
		placement: "left"
		},
		
		{
		element: "#forTypeTour",
		title: "Select rule for rule created",
		content: "Select fee rule type from dropdown list ",
		placement: "right"
		},
		
		{
		element: "#forDescriptionTour",
		title: "Description for rule",
		content: "Please enter a small description for breifing the rule created ",
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
	errMsg[0] = "About <?php echo $response ?>  student are affected by this rule. Deleting this record will remove this rule from these student. click Yes to continue.";
	errMsg[1] = "Do you want to delete this particular fee rule ?  click Yes to confirm ...!";
	
	
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
    var url = "feeRule.php?c=" + id;
        window.location.replace(url);
}

$(document).ready(function() {
        $('#feecomponentid').multiselect();
        includeSelectAllOption: true
   

$("#feecomponentid.multiselect").on("click", function () {
                    var opened = $(this).parent().hasClass("open"); 
                    $('.dataTables_scrollHead').css("overflow", !opened?'':'hidden'); 
                });
 });
</script>
<?php
    if (!empty($response)) {
        echo('<script type="text/javascript"> displayErrorJS(0);</script>');
    } elseif (!empty($_GET['delid'])) {
        echo('<script type="text/javascript"> displayErrorJS(1);</script>');
    }
?>
<?php if (wasFormSubmit() && !empty($errorArray)) {
    echo "<script>$(function(){ $( '#showFeeRuleList' ).hide(); $( '#addFeeRule' ).show(); });</script>";
} ?>

<div class="container" id='showFeeRuleList'>
    <?php 
    renderMsg();
    $feeruleArray =  feeRuleShowSelect();
    if ($feeruleArray > 0) {
        ?>
    <table class="table table-bordered table-hover" > 
        <thead> 
        <tr >
            <th>No</th>
            <th>Fee Rule Name</th>
            <th>Fees Component</th>
            <th style="text-align: center">More Options</th>
        </tr>
        </thead>
        <?php 
        $sno=(int)(isset($_GET['page']) ?  (($_GET['page']-1)*ROW_PER_PAGE)+1 : 1);
        $page=(int)(isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1);
        
        foreach ($feeruleArray as $key => $value) {
            if ($value['feerulestatus'] == 1) {
                $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
            } else {
                $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
            } ?>
        <tr>
            <td> <a href="feeRule.php?edid=<?php echo $value['feeruleid']; ?>"><?php echo $sno ?> </a></td>
            <td> <a href="feeRule.php?edid=<?php echo $value['feeruleid']; ?>"><?php echo ucwords($value['feerulename']) ?></a></td>
            <td> <a href="feeRule.php?edid=<?php echo $value['feeruleid']; ?>"><?php echo ucwords($value['feecomponent']) ?></a></td>
            <td width="130"><?php echo hoverList($value['feeruleid'], $value['feerulestatus'], $page)?></td>
         <!--<td> <a href="feeRule.php?edid=<?php echo $value['feeruleid']; ?>"><span class="glyphicon glyphicon-edit"></span></a></td>
            <td> <a href="feeRule.php?delid=<?php echo $value['feeruleid']; ?>"><span class="glyphicon glyphicon-trash"></span></a></td>
            <td> <a href="feeRule.php?sid=<?=$value['feeruleid']?>&status=<?=$value['feerulestatus']?>&page=<?=$page?>" class=""><span <?=$statusStyle?>></span></a> </td>
        -->
          </tr> 
     
            <?php $sno++;
        } ?>
    </table>
    <?php 
    } else {
        ?>
    <div class="alert alert-warning">
        <p>No record(s) found for this section or no fee rule added. Please add a FEE RULE by clicking "ADD RULE" button here below</p>
    </div>
    <?php 
    }
    ?>
    <div class="col-lg-6" style="text-align: left; padding: 0px">
        <button type="button" id='addrule' class="btn btn-success" >Add Fee Rule</button>
    </div>
    <div class="col-lg-6" style="text-align: right; padding: 0px">
        <?php getPagination($countRows, ROW_PER_PAGE); ?>
    </div>
</div>	

<div class="container" id="addFeeRule">
    <?php renderMsg(); ?>
    
    <div class="alert alert-dismissable alert-info">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>Enter</strong>
        Fee Rule Name, Fee Component Name and Amount respectively. (<a href="Javascript:void(0);" id="tourhelp">show me!</a>)
    </div>  
    <div class="btn-group btn-group-justified">
        <a href="feeMaster.php" class="btn btn-primary btn-lg">Create Fee Structure</a>
        <a href="feeRule.php" class="btn btn-success btn-lg">Create Fee Rules</a>
        <a href="otherFees.php" class="btn btn-info btn-lg">Create Other Fees</a>
    </div>
   
    <h1>Create Fee Rules</h1>
    <form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">
    <?php if (isset($_GET['edid'])) {
        $feeRuleDetails = ShowFeeRuleDetails(); 
      
        ?>
    <input type="hidden" name="edid" id="edid" value="<?php echo(cleanVar($_GET['edid'])); ?>">
    <?php 
    } ?>
    
    <div class="col-lg-4">
        <label for="feerulename">Fee Rule Name<div id="forFeeruleTour"></div></label>
        <input type="text" name="feerulename" id="feerulename" maxlength="50" class="form-control" required = "true"
                value ="<?php   if (!empty($feeRuleDetails)) {
        echo $feeRuleDetails[0]['feerulename'];
    } else {
                                    echo submitFailFieldValue("feerulename");
                                }?>">
    </div>
            
    <div class="col-lg-4">
        <label for="feecomponentid"> Fees Component<div id="forFeeComponentTour"></div></label><br>
           <!-- <select  class="form-control" id="feecomponentid" name="feecomponentid[]" multiple="multiple" required="true" > -->
                <?php 
       if (!empty($feeRuleDetails)) {
                                    echo populateCheckBox("feecomponent", "feecomponentid[]", array_column($feeRuleDetails, "feecomponentid"));
                                } else {
                            echo populateCheckBox("feecomponent","feecomponentid[]", submitFailFieldValue("feecomponentid"));
                        }
                ?>
           <!-- </select> -->
    </div>
            
    <div class="col-xs-4">
        <label for="feeruleamount"> Amount<div id="forAmountTour"></div> </label>
        <input type="text" name="feeruleamount" id="feeruleamount" class="form-control" 
                value ="<?php   if (!empty($feeRuleDetails)) {
                    echo $feeRuleDetails[0]['feeruleamount'];
                } else {
                                    echo submitFailFieldValue("feeruleamount");
                                } ?>"  >
                    
    </div>
    
    <span class="clearfix">&nbsp;<br></span>
    <div class="col-lg-4">
        <label for="format"> Format</label>
        <div class="input-group">
            <span class="input-group-addon">
            <?php if (isset($feeRuleDetails[0]['feerulemodeid']) && $feeRuleDetails[0]['feerulemodeid']=='263') {
                                    $modeChecked1='Checked=checked';
                                } else {
                                    $modeChecked1='';
                                } ?>
            <input type="radio" name="feerulemodeid" id="feerulemodeid1" 
                    value ="<?php  $modeid = feerulemode('Percent'); echo $modeid ; ?>"  <?php echo $modeChecked1?> required> Percent &nbsp; 
            </span>
            <span class="input-group-addon">
                 <?php if (isset($feeRuleDetails[0]['feerulemodeid']) && $feeRuleDetails[0]['feerulemodeid']=='264') {
                                    $modeChecked2='Checked=checked';
                                } else {
                                    $modeChecked2='';
                                } ?>
            <input  type="radio" name="feerulemodeid" id="feerulemodeid2"
                    value ="<?php  $modeid = feerulemode('Flat'); echo $modeid ;  ?>" <?php echo $modeChecked2?> required> Fixed &nbsp; &nbsp; &nbsp; 
            </span>
        </div>
        <div id="forRuleModeTour"></div>   
    </div>
    
    <div class="col-lg-4" >
        <label for="type"><strong>Type</strong> </label>
        <div class="input-group" >
            <span class="input-group-addon">
            <?php if (isset($feeRuleDetails[0]['feeruletype']) && $feeRuleDetails[0]['feeruletype']=='261') {
                                    $typeChecked1='Checked=checked';
                                } else {
                                    $typeChecked1='';
                                }?>
                <input type="radio" name="feeruletype" id="feeruletype1" 
                        value ="<?php  $typeid = feeruletype('Discount'); echo $typeid ; ?>" <?php echo $typeChecked1?> required="true"> Discount
            </span>
            <span class="input-group-addon">
            <?php if (isset($feeRuleDetails[0]['feeruletype']) && $feeRuleDetails[0]['feeruletype']=='262') {
                                    $typeChecked2='Checked=checked';
                                } else {
                                    $typeChecked2='';
                                }?>
            <input type="radio" name="feeruletype" id="feeruletype2" 
                    value ="<?php  $typeid = feeruletype('Addition'); echo $typeid ;  ?>" <?php echo $typeChecked2?>  required="true"> Addition
            </span>
            <div id="forTypeTour"></div> 
        </div>
    </div>
            
    <div class="col-lg-4">
        <label><strong>Status </strong> &nbsp;</label>
        <div class="input-group">
            <span class="input-group-addon">
                <?php if (isset($feeRuleDetails[0]['feerulestatus']) && $feeRuleDetails[0]['feerulestatus']=='1') {
                                    $activeStatus='Checked=checked';
                                } else {
                                    $activeStatus='';
                                } ?>
                <input type="radio" name="feerulestatus" id="feerulestatus" 
                        value ="1" <?php echo $activeStatus?> required="true"> Active &nbsp; &nbsp;
            </span>
            <span class="input-group-addon">
                 <?php if (isset($feeRuleDetails[0]['feerulestatus']) && $feeRuleDetails[0]['feerulestatus']=='0') {
                                    $inactiveStatus='Checked=checked';
                                } else {
                                    $inactiveStatus='';
                                } ?>
            <input type="radio" name="feerulestatus" id="feerulestatus" 
                    value ="0" <?php echo $inactiveStatus ?>required="true">
            In-Active 
            </span>
            <div id="forStatusTour"></div>
        </div>
    </div>    
    
    <span class="clearfix">&nbsp;<br></span>
     
    <div class="col-lg-4">
        <label for="feeruleremarks"> Rule Description<div id="forDescriptionTour"></div> </label>
        <textarea class="form-control"  name="feeruleremarks" id="feeruleremarks" required="true">
         <?php if (!empty($feeRuleDetails)) {
                                    echo trim($feeRuleDetails[0]['feeruleremarks']);
                                } else {
                                    echo submitFailFieldValue("feeruleremarks");
                                }?>
        </textarea>     
    </div>
    
    <span class="clearfix"><p>&nbsp;</p></span>
    
    <div class="controls" align="center">
        <button type="button" class="btn btn-success" align="center" id="showrule" >Show Fee Rule(s)</button>
        <input id="cancel" type="button" value="Cancel" class="btn">
        <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success" >
        <span class="clearfix"><p>&nbsp;</p></span> 
    </div>
           </form>

</div>
    
<?php
require_once VIEW_FOOTER;

function feeRuleShowSelect()
{
    $instSessAssocId = $_SESSION['instsessassocid'];
    if (!isset($_GET['page'])) {
        $startpage = 0;
    } else {
        $startpage = ($_GET['page'] - 1) * 10;
    }
    global  $countRows;
    
    $sql="SELECT t1.feeruleid,LOWER(t1.feerulename) as feerulename,t2.feecomponentid,
        trim(t1.feeruleremarks) as feeruleremarks,t1.feerulestatus, GROUP_CONCAT(UPPER(t3.feecomponent)) as feecomponent
          FROM tblfeerule as t1 
          LEFT JOIN tblfeeruledetail as t2 ON t1.feeruleid=t2.feeruleid
          LEFT JOIN tblfeecomponent as t3 ON t2.feecomponentid=t3.feecomponentid 
          WHERE t1.deleted!=1
          AND t1.feerulestatus = 1 
          AND t1.instsessassocid = $_SESSION[instsessassocid]
          GROUP BY t2.feeruleid";
    
    
    $countRows = mysqli_num_rows(dbSelect($sql));
    $finalSql= $sql ." LIMIT $startpage ,".ROW_PER_PAGE;
    $result = dbSelect($finalSql);
    if (mysqli_num_rows($result)>0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feeruleArray[] = $row;
        }
    } else {
        $feeruleArray=0;
    }
    return $feeruleArray;
}

function ShowFeeRuleDetails()
{
    $feeruleid = cleanVar($_GET['edid']);
    $sql = "SELECT * 
            FROM `tblfeerule` AS t1, 
            `tblfeeruledetail` AS t2, 
            `tblfeecomponent` AS t3
            WHERE t1.feeruleid = $feeruleid
            AND t1.feeruleid = t2.feeruleid
            AND t2.feecomponentid = t3.feecomponentid 
            AND t1.deleted != 1
            AND t1.feerulestatus = 1
            AND t1.instsessassocid = $_SESSION[instsessassocid]
            ";
    
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $feeRuleDetails[] = $row;
    }
    return $feeRuleDetails;
}

function actionDelete()
{
    if (isset($_GET['delid'])) {
        $feeruleid = cleanVar($_GET['delid']);
        $sql = "SELECT COUNT(DISTINCT(t1.studentid)) AS cnt FROM `tblstudent` AS t1,`tblstudfeeruleassoc`  AS t2, `tblfeerule` AS t3"
                . " WHERE t2.feeruleid = $feeruleid AND t1.studentid = t2.studentid AND t2.feeruleid = t3.feeruleid";
        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);
        $count =  $row['cnt'];
        return $count;
    } elseif (isset($_GET['c'])) {
        $feeruleid = cleanVar($_GET['c']);
        $sql = "UPDATE `tblfeerule` SET `feerulestatus`= 0 , `deleted` = 1 WHERE `feeruleid`= $feeruleid ";
        if ($result = dbUpdate($sql)) {
            header("Location: feeRule.php?s=15&feeruleid={$feeruleid}");
            exit;
        }
    }
}

function feerulemode($modename)
{
    $sql = "SELECT t1.mastercollectionid FROM `tblmastercollection` AS t1,`tblmastercollectiontype` AS t2
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid AND t2.mastercollectiontype = 'Feerulemode'
            AND t1.collectionname = '$modename'";

    $result = dbSelect($sql);
    $row =  mysqli_fetch_assoc($result);
    return $row['mastercollectionid'] ;
}

function feeruletype($type)
{
    $sql = "SELECT t1.mastercollectionid FROM `tblmastercollection` AS t1,`tblmastercollectiontype` AS t2
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid AND t2.mastercollectiontype = 'Feeruletype' 
            AND t1.collectionname = '$type'";
   
    $result = dbSelect($sql);
    $row =  mysqli_fetch_assoc($result);
   
    return $row['mastercollectionid'] ;
}
     

function print_nested_array($parent) {
    foreach($parent[count($parent)] as $value) {        
        if($level > 0) {
            foreach(range(0,$level-1) as $j) {
                echo $value["feecomponentid"];
            }

            echo ' ';
        }            

        echo $value . "\n"; //change to "<br>" for html

        if($level < count($parent)-1)
            print_nested_array($parent,($level+1));
    }
}


function returnValueArray($arr){
    $storeID = array(); 
    
    $keys = array_keys($arr);
     
    for($i = 0; $i < count($arr); $i++) {
        echo $keys[$i] . "{<br>";
        echoThis($arr[$keys[$i]]); 
        foreach($arr[$keys[$i]] as $key => $value) {
            echo $key . " : " . $value . "<br>";
    }
    echo "}<br>";
}die;

}

?>  
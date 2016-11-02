<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to manage all the collection elements across the application
 * Updates here:
 */
//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;

if (isset($_GET['status'])) {
    if ($_GET['type']=='item') {
        statusUpdate('tblmastercollection', $_GET['status'], 'mastercollectionid='.$_GET['sid']);
    } else {
        statusUpdate('tblmastercollectiontype', $_GET['status'], 'mastercollectiontypeid='.$_GET['sid']);
    }
}

if (isset($_GET['delid']) && !empty($_GET['delid'])) {
    if ($_GET['type']=='head') {
        $delQry="UPDATE tblmastercollectiontype SET deleted=1 WHERE mastercollectiontypeid=".$_GET['delid'];
    } else {
        $delQry="UPDATE tblmastercollection SET deleted=1 WHERE mastercollectionid=".$_GET['delid'];
    }
    
    $result=  dbUpdate($delQry);
    if ($result) {
        header('Location:'.$_SERVER['PHP_SELF'].'?s=7');
    }
}

require_once VIEW_HEADER;



$page=(int)(isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1);

?>

<script type="text/javascript">
    var rowNum = 0;
    function addRow(frm) {
        rowNum++;
var row = '<div id="rowNum' + rowNum + '"><span class="clearfix">&nbsp;</span><div class="col-md-6">'.concat(
'<input type="text" name="collectionname[]" id="collectionname[]" class="form-control" required="true">',
'</div><div class="col-md-1"> <button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
'<span class="glyphicon glyphicon-minus"></span></button></div></div>');
      
        jQuery('#itemRows').append(row);
      
    }

    function removeRow(rnum) {
        jQuery('#rowNum' + rnum).remove();
    }
    
    if(<?php if (isset($_GET['edid'])) {
    echo $_GET['edid'];
} else {
    echo 0;
} ?>)
    {
        $(function(){
            $( "#showcollection" ).hide();
            $('#add').click(function(){
       
            $('#addcollection').toggle(500);
            $('#showcollection').toggle(500);    
            });
        });
    }
    else
    {
        $(function(){
            $( "#addcollection" ).hide();
            $('#add').click(function(){
       
            $('#addcollection').toggle(200);
            $('#showcollection').toggle(200);    
            });
        });
        
    }
    
    // AJAX call for autocomplete 
    $(document).ready(function(){
            $("#mastercollectiontype").keyup(function(){
                    $.ajax({
                    type: "GET",
                    url: "readmastercollection.php",
                    data:'keyword='+$(this).val(),
                   
                    success: function(data){ 
                            $("#suggesstion-box").show();
                            $("#suggesstion-box").html(data);
                    }
                    });
            });
    });
    //To select country name
    function selectCountry(val) {
        $("#mastercollectiontype").val(val);
        $("#suggesstion-box").hide();
    }

</script>
<?php if (!empty($errorArray)) {
    echo "<script>  $(function(){
            $( \"#showcollection\" ).hide();
            $( \"#addcollection\" ).show();
   
        });</script>";
} ?>
<div class="container" id="showcollection">
    <?php 
        renderMsg();
        $collectionTypeArray=getAllCollectionTypeDetails();
        if ($collectionTypeArray>0) {
            ?>
   
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="70">SNo.</th>
                <th width="250">Collection Type Head</th>
                <th>Collection Items</th>
                <th style="text-align: center">More Options</th>
            </tr>
        </thead>
        <tbody>
            <div class="panel-group" id="accordion">
            <?php
                        
                $sno=(int)(isset($_GET['page']) ?  (($_GET['page']-1)*ROW_PER_PAGE)+1 : 1);
            if ($collectionTypeArray) {
                foreach ($collectionTypeArray['records'] as $key) {
                    $collectionItemsArray=  getAllCollectionItemDetails($key['mastercollectiontypeid']);
                    if ($key['status']==1) {
                        $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
                    } else {
                        $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
                    } ?>
            <tr>
                <td><a href="collectionType.php?edid=<?php echo $key['mastercollectiontypeid']; ?>&type=head"><?php echo $sno?></a></td>
                <td><a href="collectionType.php?edid=<?php echo $key['mastercollectiontypeid']; ?>&type=head"><?php echo ucwords($key['mastercollectiontype']); ?></a></td>
                <td>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne<?=$sno?>"  class="fa fa-chevron-circle-down fa-lg"></a>
                    <div id="collapseOne<?=$sno?>" class="panel-collapse collapse">
                        <div class="panel-body scrollable-menu" >
                        <?php if ($collectionItemsArray>0) {
                        ?>
                            <table class="table table-bordered table-hover" >
                                <thead>
                                    <tr>
                                        <th >Item Name</th>
                                        <th >Item Description</th>
                                        <th >Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                foreach ($collectionItemsArray['items'] as $key1) {
                                    if ($key1['status']==1) {
                                        $statusStyle1='class="glyphicon glyphicon-ok-circle" style="color:green"';
                                    } else {
                                        $statusStyle1='class="glyphicon glyphicon-ban-circle" style="color:red"';
                                    } ?>
                                    <tr>
                                        <td><?php echo ucwords($key1['collectionname']); ?></td>
                                        <td><?php echo ucwords($key1['description'])?></td>
                                        <td align="center">
                                        <a href="collectionType.php?delid=<?php echo $key1['mastercollectionid']; ?>&type=item&page=<?=$page?>"><span class="glyphicon glyphicon-trash"></span></a>&nbsp
                                        <a href="collectionType.php?status=<?php echo $key1['status']?>&sid=<?php echo $key1['mastercollectionid']?>&page=<?=$page?>&type=item" <?php echo $statusStyle1; ?> ></a></td>
                                    </tr>
                                <?php 
                                } ?>
                                </tbody>
                            </table>
                        <?php 
                    } else {
                        ?>
                            <div class="alert alert-warning">No record(s) Found for collection Items.</div>
                        <?php 
                    } ?>
                        </div>
                    </div>
                </td>
                <td width="130"><?php echo hoverList($key['mastercollectiontypeid'], $key['status'], $page)?></td>
            </tr>
        <?php  $sno++;
                }
            } ?>
    </div></tbody>
</table>
    <?php 
        } else {
            ?>
    <div class="alert alert-warning">
        <p> No record(s) found for the Collection Master. 
            Please try to add an new Collection Item by clicking "ADD COLLECTION" button below :
        </p>
    </div>
    <?php 
        } ?>
    <div class="clearfix"><br></div>
    <div class="col-lg-6" style="text-align: left; padding:0px;">
        <button type="button" id="add" class="btn btn-success" >Add Collection</button>
    </div>
    <?php  if ($collectionTypeArray) {
            ?>
    <div class="col-lg-6" style="text-align: right; padding:0px;">
         <?php getPagination($collectionTypeArray['totalrows'], ROW_PER_PAGE); ?>
    </div>
    <?php 
        }?>
</div>

<?php 
if (isset($_GET['edid'])) {
    if ($_GET['type']=='head') {
        $itemDetails=getAllCollectionItemDetails($_GET['edid']);
        $collectionHeadDetail=getAllCollectionTypeDetails();
    }
}
?>
<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">
    <div class="container" id="addcollection">
<div class="span10">
<?php renderMsg(); ?>
 
                <h1>Collection Type</h1>
                <div class="row">           
                    <div class="col-md-6">
                        <label for="mastercollectiontype">Collection Type Head</label>
                        <input type="text" class="form-control" placeholder="Collection Type Head" 
                            id="mastercollectiontype" 
                            name="mastercollectiontype" 
                            value ="<?php if (isset($_GET['edid'])) {
    echo $collectionHeadDetail['records'][0]['mastercollectiontype'];
} else {
    echo submitFailFieldValue("mastercollectiontype");
} ?>"
                        required="true" >
                       <span id="suggesstion-box"></span>
                    </div>
                </div>       
                
                <span class="clearfix">&nbsp;<br></span>
                
                <div class="row" id="itemRows">
                      <?php if (isset($_GET['edid']) && !empty($itemDetails['items'])) {
    foreach ($itemDetails['items'] as $key3 => $value) {
        ?>
                    <div class="col-md-6">
                        <label for="collectionname">Collection Item</label>
                        <input type="text" name="collectionname[<?php echo $value['mastercollectionid']  ?>]" id="collectionname[]" placeholder="Collection Item(s)" 
                               class="form-control" required="true" value="<?php if (isset($_GET['edid'])) {
            echo $value['collectionname'];
        } else {
            echo submitFailFieldValue("collectionname");
        } ?>" >
                    </div>
                    
                <div class="col-md-2">
                        <label for=""> Add</label><br>
                        <button type="button" class="btn btn-success" id="add" onclick="addRow(this.form);">
                        <span class="glyphicon glyphicon-plus"></span></button> 
                </div>
                <?php 
    }
} else {
    ?> 
                    
                    <div class="col-md-6">
                        <label for="collectionname">Collection Item</label>
                        <input type="text" name="collectionname[]" id="collectionname[]" placeholder="Collection Item(s)" 
                               class="form-control" required="true" value="<?php if (isset($_GET['edid'])) {
        echo $key3['collectionname'];
    } ?>" >
                    </div>
                    
                <div class="col-md-2">
                        <label for=""> Add</label><br>
                        <button type="button" class="btn btn-success" id="add" onclick="addRow(this.form);">
                        <span class="glyphicon glyphicon-plus"></span></button> 
                </div>
                    
                    <?php 
}?>		
				</div>
				<span class="clearfix">&nbsp;<br></span>
				
                 <div class="row">
                 <div class="col-md-6">
                    <label for="description">Collection Description</label>
                    <input type="text" name="description" id="description" class="form-control" 
                    value ="<?php echo submitFailFieldValue("description"); ?>" />
                        <div class="small">Brief description of the collection.</div>                       
                </div>
                </div>
                
                <div class="row">
                <div class="col-md-6">
                <label for="status">Active
                    <input type="checkbox" name="status" id="status" value="1" required> </label>
                </div>
                </div>
      
              <span class="clearfix"><p>&nbsp;</p></span>
              
             <div class="controls" align="center">
                <input id="clearDiv" type="button"  value="Cancel" class="btn">
                <!-- Button trigger modal -->
                <input type="submit" id="save"  name="save" value="SAVE" class="btn btn-success">
            </div> 
       
    </div> <!--span class closed-->   
	</div> <!--container closed-->
 </form>   

<?php
require VIEW_FOOTER;

function getAllCollectionTypeDetails()
{
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 :($_REQUEST['page']-1)*ROW_PER_PAGE);
    $orderby= " ORDER BY mastercollectiontypeid limit ".$startPage.','.ROW_PER_PAGE;
    if (isset($_SESSION['instsessassocid']) && !empty($_SESSION['instsessassocid'])) {
        if (isset($_GET['edid'])) {
            $sql = "SELECT mastercollectiontypeid, LOWER(mastercollectiontype) as mastercollectiontype,status 
                FROM tblmastercollectiontype WHERE tblmastercollectiontype.deleted!=1"
                ." AND mastercollectiontypeid=".$_GET['edid'];
        } else {
            $sql = "SELECT mastercollectiontypeid,  LOWER(mastercollectiontype) as mastercollectiontype,status 
                FROM tblmastercollectiontype  WHERE tblmastercollectiontype.deleted!=1 " ;
        }
        $finalSql=$sql.$orderby;
    } else {
        $finalSql="SELECT mastercollectiontypeid,  LOWER(mastercollectiontype) as mastercollectiontype,status 
                FROM tblmastercollectiontype WHERE deleted!=1 " . $orderby;
    }
  
    $result = dbSelect($finalSql);

    if (mysqli_num_rows($result)>0) {
        while ($row= mysqli_fetch_assoc($result)) {
            $collectionDetails['records'][]=$row;
        }
    
        $collectionDetails['totalrows']=mysqli_num_rows(dbSelect($sql));
    
        return $collectionDetails;
    } else {
        return 0;
    }
}
function getAllCollectionItemDetails($collectionTypeHeadId)
{
    $sqlItem="SELECT * FROM tblmastercollection WHERE mastercollectiontypeid=".$collectionTypeHeadId." AND deleted!=1";
    $resultItem=  dbSelect($sqlItem);
    if (mysqli_num_rows($resultItem)>0) {
        while ($rowItem=  mysqli_fetch_assoc($resultItem)) {
            $itemDetails['items'][]=$rowItem;
        }
        return $itemDetails;
    }
    return 0;
}
?>
<!--     <td> <a href="collectionType.php?edid=<?php //echo $key['mastercollectiontypeid'];?>&type=head"><span class="glyphicon glyphicon-pencil" ></span></a></td>
                <td> <a href="collectionType.php?delid=<?php// echo $key['mastercollectiontypeid'];?>&type=head&page=<?=$page?>"><span class="glyphicon glyphicon-trash"></span></a></td>
                <td> <a href="collectionType.php?status=<?php //echo $key['status']?>&sid=<?php// echo $key['mastercollectiontypeid']?>&page=<?=$page?>&type=head" <?php echo $statusStyle;?> ></a></td>
           -->
<?php
    /*
     * 360 - School Empowerment System.
     * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
     * Page details here: Page to add new institute/branches
     * Updates here:
     */
   
    require_once "../../config/config.php";
    require_once DIR_FUNCTIONS;
    require_once VIEW_HEADER;

    if (isset($_GET['page']) && !empty($_GET['page']) && $_GET['page']>1) {
        $page=$_GET['page'];
    } else {
        $page=1;
    }

    if (isset($_GET['status'])) {
        statusUpdate('tblacademicsession', $_GET['status'], 'academicsessionid='.$_GET['sid']);
    }

    if (isset($_GET['delid'])) {
        $result=  dbUpdate("UPDATE tblacademicsession SET deleted=1 WHERE academicsessionid=".$_GET['delid']);
    }
    
    if (isset($_GET['mode']) && $_GET['mode']=='edit') {
        $academicYear=  getAcademicYearDetail();
        
    }
    
?>
<script>
    $(function(){
    <?php if(isset($_GET['mode']) == 'edit') { ?>
        displayHideDiv('addyear','selectyear');
    <?php } ?>  
        });
</script>  

<?php if (!isset($_GET['mode'])) {
    ?>
 <div class="container" id="selectyear">
     <?php 
     $academicYear=  getAcademicYear();
    if ($academicYear>0) {
        ?>
    
    <table class="table table-bordered table-hover " border="1">
        <thead>
            <tr>
                <th>S No.</th>
                <th>Session Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th style="text-align: center">More Options</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                 
                if ($academicYear['totalrows']>0) {
                   
                    $sno=1;
                    foreach ($academicYear['records'] as $yearKey) {
                        
                        if ($yearKey['status']==1) {
                            $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
                        } else {
                            $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
                        } ?>
        
            <tr>
                <td><a href="addAcademicYear.php?edid=<?php echo $yearKey['academicsessionid']; ?>&mode=edit"><?php echo $sno?></a></td>
                <td><a href="addAcademicYear.php?edid=<?php echo $yearKey['academicsessionid']; ?>&mode=edit"><?php echo $yearKey['sessionname']; ?></a></td>
                <td><a href="addAcademicYear.php?edid=<?php echo $yearKey['academicsessionid']; ?>&mode=edit"><?php echo $yearKey['sessionstartdate']; ?></a></td>
                <td><a href="addAcademicYear.php?edid=<?php echo $yearKey['academicsessionid']; ?>&mode=edit"><?php echo $yearKey['sessionenddate']; ?></a></td>
                <td width="130"><?php echo hoverList($yearKey['academicsessionid'], $yearKey['status'], ''); ?></td>
           
                </tr>
            <?php  $sno++;
                    }
                } ?>
           
    </table>
     <?php 
    } else {
        ?>
     <div class="col-lg-11">
     <div class="alert alert-warning"><p> No record(s) found for Academic Year. 
             Please try to add a new Academic Session by clicking "ADD SESSION" button below :
         </p>
     </div>
     </div>
     
     <?php 
    } ?>
    
     <div class="clearfix"></div><button type="button" id="add" class="btn btn-success" onclick="displayHideDiv('addyear','selectyear')" >Add Session</button></div>
    <?php 
}  ?>
   
<div class="container" id="addyear" style="display: none">
        <div class="row">
            <div class="span8">
            <?php renderMsg(); ?>
                
            <form action="<?php echo PROCESS_FORM; ?>" method="post" name="imForm">
                <input type="hidden" name="mode" id="mode" value="<?php if (isset($_GET['mode']) && $_GET['mode']=='edit') {
    echo 'edit';
} else {
    echo 'add';
}?>">
                <input type="hidden" name="edid" name="edid" value="<?php if (isset($academicYear[0]['academicsessionid'])) {
    echo $academicYear[0]['academicsessionid'];
} ?>">
            
                <div class="col-md-6">
                    <label for="sessionname">Title Year</label>
                    <input type="text" class="form-control" placeholder="Title year"  id="sessionname" name="sessionname" required="true"
                    value="<?php if (isset($academicYear[0]['sessionname'])) {
    echo $academicYear[0]['sessionname'];
} ?>">
                </div>	
                
                <div class="col-md-6">
                    <label for="status">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" <?php if (isset($academicYear[0]['status']) && $academicYear[0]['status']==1) {
    echo "selected='selected'";
}?>> Active </option>
                        <option value="0" <?php if (isset($academicYear[0]['status']) && $academicYear[0]['status']==0) {
    echo "selected='selected'";
}?>> In-Active </option>			  
                    </select>
                </div>
                
                <span class="clearfix"> <p>&nbsp;</p></span> 
	
                <div class="col-md-6">
                    <label for="sessionstartdate">Session Start Date</label>
                    <input type="date" required="true" placeholder="Session Start Date" id="sessionstartdate" name="sessionstartdate" 
                     class="form-control" required="true" value="<?php if (isset($academicYear[0]['sessionname'])) {
    echo $academicYear[0]['sessionstartdate'];
} ?>">
                </div>

                <div class="col-md-6">
                    <label for="sessionenddate">Session End Date</label>
                    <input type="date" required="true" placeholder="Session End Date" id="sessionenddate" name="sessionenddate" class="form-control" 
                    required="true" value="<?php if (isset($academicYear[0]['sessionenddate'])) {
    echo $academicYear[0]['sessionenddate'];
} ?>">
                </div>
	
        </div> <!--row closed-->
 
        <span class="clearfix"> <p>&nbsp;</p></span> 
        <span class="clearfix"> <p>&nbsp;</p></span> 

        <div class="row">
            <div class="controls" align="center">
                <button type="button" id="show" class="btn btn-success" onclick="displayHideDiv('selectyear','addyear')" >Show Academic Year</button>
                 <input id="clearDiv" type="reset" value="Cancel" class="btn">
                <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success">
            </div>
        </div>
        </form>
	 
</div><!--span 8 div closed-->	
</div> <!--container closed-->

<?php
require VIEW_FOOTER;

function getAcademicYearDetail()
{
    if (isset($_GET['edid']) && is_numeric($_GET['edid'])) {
        $sqlStr=" SELECT * FROM tblacademicsession WHERE academicsessionid='".cleanVar($_GET['edid'])."'";
        $result=  dbSelect($sqlStr);
        if (mysqli_num_rows($result)>0) {
            while ($row=  mysqli_fetch_assoc($result)) {
                $yearArray[]=$row;
            }
            
            return $yearArray;
        } else {
            return false;
        }
    }
}

function getAcademicYear()
{
    if (isset($_REQUEST['page'])!=0 && !$_REQUEST['page']<=0) {
        $startPage =($_REQUEST['page']-1)*ROW_PER_PAGE;
    } else {
        $startPage=1;
    }
    $sqlTotalRow=mysqli_num_rows(dbSelect("SELECT academicsessionid  from tblacademicsession WHERE deleted!=1"));
    $orderby="ORDER BY academicsessionid limit ".$startPage.','.ROW_PER_PAGE;
    if (isset($_SESSION['instsessassocid']) && !empty($_SESSION['instsessassocid'])) {
        $sql="SELECT t1.academicsessionid,t1.sessionname,t1.sessionstartdate,t1.sessionenddate, t1.status 
             
             FROM tblacademicsession as t1 
             LEFT JOIN tblinstsessassoc as t2
             ON t1.academicsessionid=t2.academicsessionid
             
             WHERE t1.deleted!=1  AND t2.instsessassocid =".$_SESSION['instsessassocid']
           ;
    } else {
        $sql='SELECT academicsessionid,sessionname,sessionstartdate,sessionenddate,status '
            . 'FROM tblacademicsession WHERE deleted!=1 ';
    }

    $result = dbSelect($sql);
    if (mysqli_num_rows($result)>0) {
        while ($row=mysqli_fetch_assoc($result)) {
            $academicYearArray['records'][]=$row;
        }
        $academicYearArray['totalrows']=mysqli_num_rows($result);
        return $academicYearArray;
    } else {
        return 0;
    }
}
?>
 

<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;

?>
<script type="text/javascript">
    function showHideDiv(divName) {
        $('#' + divName).modal('show');
    }
    
   $(document).ready(function ($) {
        $('#checkall').on('click', function(){ 
            var childClass = $(this).attr('data-child');
            $('.'+childClass+'').prop('checked', this.checked);
        });
 }); 
 
 function popUp(url, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var sw = (screen.width * .60);
        var sh = (screen.height * .60);

        window.open(url, 'pop-up', 'width=' + sw + ', height=' + sh + ', top=' + top + ', left=' + left);
    }
</script>
<?php
if (isset($_GET['status']) && $_GET['status'] != '' && isset($_GET['sid'])) {
    echo "<script type='text/javascript'>swal('Here's a message!')</script>";
    statusUpdate('tblstudent', $_GET['status'], 'studentid=' . $_GET['sid']);
}

if (isset($_GET['delid']) && !empty($_GET['delid'])) {
    echo "<script type='text/javascript'>swal('Here's a message!')</script>";
    $result = dbUpdate("UPDATE tblstudent SET deleted=1 WHERE studentid=" . cleanVar($_GET['delid']));
}

require_once VIEW_HEADER;
$sno = (int) (isset($_GET['page']) ? (($_GET['page'] - 1) * ROW_PER_PAGE) + 1 : 1);
$page = (int) (isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1);

$totalRows = 0;
?>

<div class="container">
    <?php renderMsg() ?>
    <div class="searchfrm">
        <form action="" method="GET" id="imform" name="myForm" onsubmit="return validateForm()"> 
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon">Scholar No</span> 
                    <input  type="text" class="form-control" name="scholarnumber" id="scholarnumber" tabindex="1" 
                            value ="<?php echo submitFailFieldValue("scholarnumber"); ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-default"  name="search" id="Search">
                            <span class="glyphicon glyphicon-search" name="search" value='Search' > </span></button> 
                    </span>   
                </div><!-- /input-group -->
            </div><!-- /.col-lg-4 -->

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon">Student Name</span> 
                    <input onkeypress="return isAlpha(event)"type="text" class="form-control" name="studentname" id="studentname" tabindex="2" 
                           value ="<?php echo submitFailFieldValue("studentname"); ?>">

                </div><!-- /input-group -->
            </div><!-- /.col-lg-4 -->

            <span class="clearfix">&nbsp;<br></span>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon">Parent Name</span> 
                    <input onkeypress="return isAlpha(event)" type="text" class="form-control" name="parentname" id="parentname" tabindex="3" 
                           value ="<?php echo submitFailFieldValue("parentname"); ?>">

                </div><!-- /input-group -->
            </div><!-- /.col-lg-4 -->

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <div class="input-group">
                    <span class="input-group-addon">Class</span>
                    <select name="classid" id="classid" tabindex="2" class="form-control" tabindex="4" >
                        <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
                    </select>
                </div>
            </div> 

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <div class="input-group">
                    <span class="input-group-addon">Section</span>
                    <select name="sectionid" id="sectionid" tabindex="3" class="form-control" tabindex="5">
                        <?php echo populateSelect("sectionname", submitFailFieldValue("sectionid")); ?>
                    </select>
                </div>
            </div>

            <span class="clearfix">&nbsp;<br></span>
            <div class='control' align="center">
                <button type='reset' name="cancel"  value="Reset" class="btn">Cancel</button>
                <button name='search'  value="Search" class="btn btn-success">Search</button>
            </div>
        </form>
        <span class="clearfix">&nbsp;<br></span>
    </div>
    <div class="showlist">
        <?php
        if ((isset($_GET['search'])) || isset($_GET['status']) || (isset($_GET['delid']))) {
            $studentArray = getStudentsDetails();
            if (isset($studentArray) && $studentArray != 0) {
                ?>
                <table class="table table-bordered table-hover" id="displaytable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Scholar No.</th>
                            <th>Student Name</th>
                            <th>Parent Name </th>
                            <th>Class</th>
                            <th>More Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($studentArray['records'] as $key) {
                            if ($key['status'] == 1) {
                                $statusStyle = 'class="glyphicon glyphicon-ok-circle" style="color:green"';
                            } else {
                                $statusStyle = 'class="glyphicon glyphicon-ban-circle" style="color:red"';
                            }
                            $parentName = strtoupper(getParentDetails($key['studentid']));
                            if (isset($_REQUEST['fathername']) && ($_REQUEST['fathername'] != '')) {
                                $parentName = strtoupper(getParentInfo(cleanVar($_REQUEST['fathername'])));
                            }

                            if ($parentName == '0') {
                                $sid = $key['studentid'];
                                $parentName = "<a href=\"studentParent.php?mode=edit&sid=$sid\" > Add Parent </a> ";
                            } ?> 
                            <tr>
                                <td> <a href="studentPersonal.php?sid=<?= $key['studentid']; ?>&mode=<?= urlencode('edit');?>" class=""><?= $sno ?></a></td>
                                <td> <a href="studentPersonal.php?sid=<?= $key['studentid']; ?>&mode=edit" class=""><?php echo $key['scholarnumber']; ?> </a></td>
                                <td> <a href="studentPersonal.php?sid=<?= $key['studentid']; ?>&mode=edit" class=""><?php echo strtoupper($key['firstname'] . " " . $key['middlename'] . " " . $key['lastname']); ?></a></td>
                                <td> <a href="studentParent.php?sid=<?= $key['studentid']; ?>&mode=edit" class=""><?php echo($parentName); ?></a></td>
                                <td> <a href="studentPersonal.php?sid=<?= $key['studentid']; ?>&mode=edit" class=""><?php echo $key['classname'] . " - " . $key['sectionname'] ?> </a></td>
                                <td width="220"><?php echo hoverList($key['studentid'], $key['status'], $page)?></td>
                            </tr>
                            <?php
                            $sno++;
                        } ?> 
                    </tbody>
                </table> 
            <?php 
            } else {
                ?>
                <div class="alert alert-danger"> No record(s) found. Please change the search criteria and try again.</div>
            <?php

            }
        }
        ?>
    </div>
    <div class="col-lg-6" style="text-align: left; padding: 0px">
        <a href="studentPersonal.php?mode=complete"><button type="button" id="add" class="btn btn-info" href="#">Add Student (Complete)</button></a>
    </div>
    <div class="col-sm-6" style="text-align: right; padding: 0px">
<?= getPagination($totalRows, ROW_PER_PAGE); ?>
    </div>
</div>

<?php
require_once VIEW_FOOTER;

function getStudentsDetails()
{
    global $totalRows;
    $searchTerms = cleanVar($_REQUEST);
    $where = " ";
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    $orderby = " ORDER BY  T6.classid, T7.sectionid, T1.firstname ASC ";
    $groupBy = " GROUP BY T1.studentid ";
    $limit = "LIMIT " . $startPage . ',' . ROW_PER_PAGE;

    $sql = " SELECT T1.studentid, T1.scholarnumber,
            T1.firstname, T1.middlename, T1.lastname,  T1.status,
            T6.classname, T7.sectionname
               
                FROM `tblstudent` AS T1 ,
                `tblstudentacademichistory` AS T4, 
                `tblclsecassoc` AS T5,
                `tblclassmaster` as T6,
                `tblsection` AS T7 

                WHERE   T1.studentid = T4.studentid  
                AND     T4.clsecassocid = T5.clsecassocid  
                AND     T5.classid = T6.classid  
                AND     T5.sectionid = T7.sectionid 
                AND     T1.instsessassocid = " . $_SESSION['instsessassocid'] . "
                AND     T1.status = 1
                AND     T1.deleted != 1            
            ";

    if (!empty($searchTerms['scholarnumber'])) {
        $where = " AND T1.scholarnumber LIKE'%" . $searchTerms['scholarnumber'] . "%'";
    }
    if (!empty($searchTerms['fathername'])) {
        $where.=" AND T3.parentfirstname LIKE '" . $searchTerms['fathername'] . "%' ";
    }
    if (!empty($searchTerms['classid'])) {
        $where.= " AND T6.classid=" . $searchTerms['classid'];
    }
    if (!empty($searchTerms['sectionid'])) {
        $where.=" AND T7.sectionid=" . $searchTerms['sectionid'];
    }
    if (!empty($searchTerms['studentname'])) {
        $where.=" AND T1.firstname LIKE '" . $searchTerms['studentname'] . "%'";
    }
    
    $finalSql = $sql . $where . $groupBy . $orderby . $limit;
    //echoThis($finalSql);die;
    $resultStudent = dbSelect($finalSql);

    if (!empty($resultStudent) && mysqli_num_rows(dbSelect($finalSql)) > 0) {
        while ($rowStudent = mysqli_fetch_assoc($resultStudent)) {
            $studentDetails['records'][] = $rowStudent;
        }
        $totalRows = mysqli_num_rows(dbSelect($sql . $where . $groupBy . $orderby));
        return $studentDetails;
    } else {
        $totalRows = 0;
        return 0;
    }
}

function getParentDetails($studentid)
{
    $sql = " SELECT  t1.parentfirstname, t1.parentmiddlename, t1.parentlastname
                FROM `tblparent` AS t1,
                `tbluserparentassociation` AS t2
                
                WHERE t2.studentid = '$studentid'
                AND t1.parentid = t2.parentid
                GROUP BY t2.studentid
             ";
    
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $parentName = $row['parentfirstname'] . " " . $row['parentmiddlename'] . " " . $row['parentlastname'];
        }
        return $parentName;
    } else {
        return 0;
    }
}

function getParentInfo($parentname)
{
    $sql = " SELECT  t1.parentfirstname, t1.parentmiddlename, t1.parentlastname
                FROM `tblparent` AS t1,
                `tbluserparentassociation` AS t2
                
                WHERE t1.parentfirstname LIKE  '%$parentname%'
                AND t1.parentid = t2.parentid
                GROUP BY t2.studentid
             ";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $parentName = $row['parentfirstname'] . " " . $row['parentmiddlename'] . " " . $row['parentlastname'];
        }
        return $parentName;
    } else {
        return 0;
    }
}

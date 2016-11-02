<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Master for fees head and related processing
 * Updates here:
 */
//call the main config file, functions file and header
require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<script type="text/javascript">
    function popUp(url, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        var top = (screen.height / 2) - (h / 2);
        var sw = (screen.width * .60);
        var sh = (screen.height * .60);
        window.open(url, 'pop-up', 'width=' + sw + ', height=' + sh + ', top=' + top + ', left=' + left);
    }
</script>
<?php $studentdetails = studentDetailsSql(); ?>
<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">

    <!-- all hidden fields are here -->
    <input type="hidden" name="studentid" id="studentid" value="<?php echo($studentdetails['studentid']) ?>" >
    <input type="hidden" name="classid" id="classid" value="<?php echo($studentdetails['classid']) ?>" >
    <input type="hidden" name="sectionid" id="sectionid" value="<?php echo($studentdetails['sectionid']) ?>" >
    <input type='hidden' name='instituteabbrevation' value='<?php echo $studentdetails['instituteabbrevation'] ?>' />
    <input type='hidden' name='sessionname' value='<?php echo $studentdetails['sessionname'] ?>' />

    <table class="table table-bordered table-hover table-condensed">
        <tr class="info">
            <th> Scholar no </th>
            <th> Student Name </th>
            <th> Class </th>
            <th> Parent Name </th>
            <th> Date Of Joining </th>
        </tr>
        <tr>
            <td><?php echo($studentdetails['scholarnumber']); ?></td>
            <td><?php echo($studentdetails['firstname'] . "&nbsp " . $studentdetails['middlename'] . $studentdetails['lastname']); ?></td>
            <td><?php echo($studentdetails['classdisplayname'] . "&nbsp - " . strtoupper($studentdetails['sectionname'])); ?></td>
            <td><?php echo($studentdetails['parentfirstname'] . "&nbsp" . $studentdetails['parentmiddlename'] . " " . $studentdetails['parentlastname']); ?></td>
            <td><?php echo date("j-F,Y", strtotime($studentdetails['datecreated'])); ?></td>
        </tr>
        <tr>
            <td colspan='2'></td>
            <th>Amount</th>
            <td><input type="text" class="form-control" name="amount" id="amount" placeholder="Amount"></td>
            <td><button type="submit" class="btn btn-success" name="payfees">Pay Fees</button></td>
        </tr>
    </table>    

</form>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Siblings Details</h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-hover" > 
                    <tr class="info">
                        <th align="center">S.No</th>
                        <th align="center">Scholar No.</th>
                        <th align="center">First/Last Name</th>
                    </tr> 

                    <?php
                    $details = siblingDetails();
                    $i = 1;
                    if (!empty($details)) {
                        foreach ($details as $key => $value) { //echoThis($details); die;
                            ?>
                            <tr>
                                <td><a href="feeDue.php?studentid=<?php echo $value['studentid'] ?>"><?php echo $i ?></td></a>
                                <td><a href="feeDue.php?studentid=<?php echo $value['studentid'] ?>"><?php echo $value['scholarnumber'] ?></td></a>
                                <td><a href="feeDue.php?studentid=<?php echo $value['studentid'] ?>"><?php echo($value['firstname'] . " " . $value['middlename'] . " " . $value['lastname']) ?></td></a>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    ?> 
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once VIEW_FOOTER;

function studentDetailsSql()
{
    $instsessassocid = $_SESSION['instsessassocid'];
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);
        $sql = "SELECT *
          
           FROM `tblstudent` AS t1, 
		  `tblstudentacademichistory` AS t2, 
		  `tblclassmaster` AS t3, 
		  `tblsection` AS t4,
		  `tbluserparentassociation` AS t5,
		  `tblparent` AS t6,
		  `tblinstsessassoc` AS t7,
		  `tblinstitute` AS t8,
		  `tblacademicsession` AS t9,
                  `tblclsecassoc` AS t10
		  
          WHERE t1.studentid = $studentid
          AND t7.instsessassocid = $instsessassocid
          AND t1.studentid = t2.studentid
          AND t2.clsecassocid = t10.clsecassocid
          AND t3.classid = t10.classid 
          AND t10.sectionid = t4.sectionid
          AND t1.studentid = t5.studentid 
          AND t5.parentid = t6.parentid 
          AND t7.instituteid = t8.instituteid
          AND t7.academicsessionid = t9.academicsessionid 
		  ";

        if ($result = dbSelect($sql)) {
            $row = mysqli_fetch_assoc($result);
            return $row;
        } else {
            return 0;
        }
    }
    return 0;
}

function siblingDetails()
{
    if (isset($_GET['studentid'])) {
        $studentid = cleanVar($_GET['studentid']);

        $sql = "SELECT  t1.scholarnumber, t1.studentid,t2.parentid, t2.userid
                FROM `tblstudent` AS t1, 
		`tbluserparentassociation` AS t2,
		`tblparent` AS t3
		  
                WHERE t1.studentid = $studentid
                AND t1.studentid = t2.studentid
                AND t2.parentid = t3.parentid
		  
		  ";
        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);

        $parentid = $row['parentid'];
        $userid = $row['userid'];
        unset($sql);
        unset($row);
        unset($result);

        $sql = "SELECT t3.studentid, t3.scholarnumber,  t3.firstname, t3.middlename, t3.lastname, t5.classid, t6.sectionid,
            t5.classdisplayname, t6.sectionname
          
		  FROM `tbluserparentassociation` AS t1,
		  `tblparent` AS t2,
		  `tblstudent` AS t3,
		  `tblstudentacademichistory` AS t4,
		  `tblclassmaster` AS t5,
		  `tblsection` AS t6,
                  `tblclsecassoc` AS t7
		  
		  WHERE NOT t1.userid = $userid
		  AND t1.parentid = $parentid
		  AND t1.parentid = t2.parentid
		  AND t1.studentid = t3.studentid
		  AND t3.studentid = t4.studentid
		  AND t4.clsecassocid = t7.clsecassocid
                  AND t7.classid = t5.classid
		  AND t7.sectionid = t6.sectionid
		  
		  ";
        $siblingdetail = array();
        if ($result = dbSelect($sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $siblingdetail[] = $row;
            }
            return $siblingdetail;
        }
        return 0;
    }
}

function collectedfeeSql()
{
    $collectedfeedetails = array();
    $sql = "SELECT  ";
    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $collectedfeedetails[] = $row;
    }
    return $collectedfeedetails;
}

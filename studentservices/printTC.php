<?php
require_once "../config/config.php";
require_once DIR_FUNCTIONS;

$studentDetails = studentdetails(); //echoThis($studentDetails); die;
$dob = date("d/m/Y", strtotime($studentDetails[0]['dob']));
$dobwords = date("d, F , Y", strtotime($studentDetails[0]['dob']));
if (empty($studentDetails[0]['dob'])) {
    $dob = "NA";
    $dobwords = "NA";
}
$intsessassocid = $_SESSION['instsessassocid'];

$generalcategory = "No";
if ($studentDetails[0]['category'] == 255) {
    $generalcategory = "Yes";
}
if ($intsessassocid != 2 && $intsessassocid != 7) {
    $interval = date_diff(date_create(), date_create($studentDetails[0]['dob']));
    $studentAge = $interval->format("  %Y Year %M Months %d Days "); ?>

<div style="height: 220px;"></div>
    <div style="padding-left: 30px;">
        <div style="width: 240px;  display: inline">No 6.</div>
        <div style="width: 240px; text-align: center; font-size: 15px; display: inline"><b>RECORD - A</b></div>
        <div style="width: 250px; text-align: center; font-size: 15px; display: inline"><b>SCHOLAR NO.   &nbsp; : &nbsp;<?php echo $studentDetails[0]['scholarnumber'] ?></b></div>
        <br>
        <div style=" width: 900px;">
            <div style="width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline"><b>DATE OF ADMISSION</b></div>
            <div style="width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline"><b>DATE OF REMOVAL</b></div>
            <div style="width: 250px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline"><b>CAUSE OF REMOVAL</b></div>

        </div>
        <div style=" width: 900px;">
            <div style="width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline"><b><?php echo date("d/m/Y", strtotime($studentDetails[0]['datecreated'])) ?></b></div>
            <div style="width: 230px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline"><b><?php echo date("d/m/Y") ?></b></div>
            <div style="width: 250px; height: 15px; padding-top: 5px; border: thin 1px; text-align: center;display: inline">Change of school</div>
        </div>
    </div>

    <div>&nbsp;<br>&nbsp;<br></div>
    <div  style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  1. Name of Scholar</b> &nbsp;  <?php echo($studentDetails[0]['firstname'] . " " . $studentDetails[0]['middlename'] . " " . $studentDetails[0]['lastname']) ?>
    </div>
    <div>&nbsp;<br>&nbsp;<br></div>

    <div style="padding-left: 30px;" style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  2. Father's Name</b> &nbsp;  <?php echo("MR." . $studentDetails[0]['parentfirstname'] . " " . $studentDetails[0]['parentmiddlename'] . " " . $studentDetails[0]['parentlastname']) ?>
    </div> 

    <div>&nbsp;<br>&nbsp;<br></div>
    <?php
    $motherName = "__________________________";
    if (isset($studentDetails[1])) {
        $motherName = "MRS." . $studentDetails[1]['parentfirstname'] . " " . $studentDetails[1]['parentmiddlename'] . " " . $studentDetails[1]['parentlastname'];
    } ?>
    <div style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  3. Mother's Name</b> &nbsp;  <?php echo($motherName) ?>
    </div>

    <div>&nbsp;<br>&nbsp;<br></div>

    <div style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  4. Date of Birth in Words</b> &nbsp;  <?php echo(date("d, F , Y", strtotime($studentDetails[0]['dob']))) ?>
            &nbsp;  (<?php echo(date("d/m/Y", strtotime($studentDetails[0]['dob']))) ?>)
    </div>

    <div>&nbsp;<br>&nbsp;<br></div>

    <div style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  5. Age & date of first admission to this school</b> &nbsp; <?php echo $studentAge ?>
            ,&nbsp;<?php echo(date("d/m/Y", strtotime($studentDetails[0]['datecreated']))) ?>
    </div>

    <div>&nbsp;<br>&nbsp;<br></div>

    <div style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  6. Religion/Category</b> &nbsp; <b>Hindu/Muslim/Sikh/Christian &nbsp;  GEN/SC/ST/OBC</b>
    </div>

    <div>&nbsp;<br>&nbsp;<br></div>

    <div style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  7. Name occupation and address of parent or guardian</b>&nbsp;&nbsp;<?php echo('___________________________________') ?> 
    </div>

    <div>&nbsp;<br>&nbsp;<br></div>

    <div style=" font-size: 14px; padding-left: 30px; display: inline">
        <b>  8. The last School if any which the scholar attendend before joining the school</b>&nbsp;&nbsp;&nbsp;&nbsp;<u><?php echo($studentDetails[0]['previousschool']) ?></u>
    </div>


    <div>&nbsp;<br>&nbsp;<br></div>

    <div style=" font-size: 14px; padding-left: 30px;  display: inline">
        <b>  9. The highest class from which the scholar was promoted or was fit for promotion on leaving his last school<br><br></b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo('_______________________________________') ?>
    </div>

    <div>&nbsp;<br>&nbsp;<br>&nbsp;<br><br>&nbsp;<br>&nbsp;<br></div>
    <div  style='font-size: 15px; width:700px;  text-align:center'><b>RECORD-C</b></div>
    <style>
        table {
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}
        </style>
        <table style="padding-left: 30px;">
        <tr >
            <td colspan="2" align="center" >ADMISSION  OR <br>PROMOTION</td>
            <td width="9%" rowspan="2" align="center">Date of <br>passing
                <br> standard <br>or class
                from <br>this school</td>
            <td colspan="2" align="center">ATTENDANCE</td>
            <td colspan="2" align="center">RANK IN CLASS</td>
            <td  rowspan="2" align="center" style="width: 85px;">Subject Taken</td>
            <td  rowspan="2" align="center">RECORD - D</td>
        </tr>
        <tr>
            <td align="center" style="width: 45px;" >Class</td>
            <td align="center" style="width: 75px;" >Date</td>
            <td align="center" style="width: 65px;">No. of school
                meeting</td>
            <td  align="center" style="width: 65px;">No. of meetings at <br> which<br>
                presents</td>
            <td  align="center" style="width: 75px;">No. of <br> Scholar in<br>
                Class</td>
            <td  align="center" style="width: 120px;">Place <br>shown 
                in final<br> examination <br>
                of class</td>
        </tr>
        <tr >
            <td style="height: 15px;">PG</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr> 
            <td style="height: 15px;">NR</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">KG</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">PREP</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">I</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">II</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">III</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">IV</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">V</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">VI</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">VII</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td style="height: 15px;">VIII</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>


    </table>
    <br>
    <div style="padding-left: 30px;">1. Certified the above Scholar's Register has been posted upto date on Scholar's leaving as required by the rules</div>
    <div style="padding-left: 30px;">2. Certified that no School fee, etc is due</div>
    <div style="padding-left: 30px;"><b>Date................</b></div>
    <div style="padding-right: 30px;" align='right'><b>CENTRAL ACADEMY</b></div>

    <?php

} else {
    ?>
    <div>   
        <div style="width: 290px;  display: inline;"><b> Book No <u>38</u></b></div>
        <div style="width: 200px; display: inline;"><b>SI No <u>74</u></b></div>
        <div style="width: 200px;display: inline;"><b>Admission No <u>9902</u></b></div>
        <table>
            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>1. Name of Pupil&nbsp;</b> <u><?php echo($studentDetails[0]['firstname'] . " " . $studentDetails[0]['middlename'] . " " . $studentDetails[0]['lastname']) ?></u></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>2. Father's Name&nbsp;</b><?php echo("MR." . $studentDetails[0]['parentfirstname'] . " " . $studentDetails[0]['parentmiddlename'] . " " . $studentDetails[0]['parentlastname']) ?></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <?php
            $motherName = "-";
    if (isset($studentDetails[1])) {
        $motherName = "MRS." . $studentDetails[1]['parentfirstname'] . " " . $studentDetails[1]['parentmiddlename'] . " " . $studentDetails[1]['parentlastname'];
    } ?>

            <tr>
                <td><b>3. Mother's Name&nbsp;</b><?php echo($motherName) ?></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>4. Nationality&nbsp;&nbsp;</b> <u>Indian</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>5. Whether the Candidate belongs to scheduled Caste or Scheduled Tribe&nbsp;&nbsp;</b><u><?php echo $generalcategory ?></u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>6. Date of First admission in the school with class&nbsp;&nbsp;</b><u><?php echo(date("d/m/Y", strtotime($studentDetails[0]['datecreated'])) . "   & " . $studentDetails[0]['classdisplayname']) ?></u></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>7. Date of Birth (in Christian Era) according to Admission Register (In Figures)&nbsp;&nbsp;</b><?php echo($dob) ?></td>
            </tr>
            <tr>
                <td><b>(In Words)&nbsp;&nbsp;</b><u><?php echo($dobwords) ?></u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>8. Class in which the pupil last studied (in figures)&nbsp;&nbsp;</b><u><?php echo($studentDetails[0]['classdisplayname']) ?></u>
                    <b> (In Words)&nbsp;&nbsp;</b><u><?php echo(convertNum2Words($studentDetails[0]['classdisplayname'])) ?></u>
                </td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>9. School/Board Annual Examination last taken with result &nbsp;&nbsp;</b><u>PASS/FAIL</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>10. Whether failed, if so once/twice in the same class &nbsp;&nbsp;</b><u><?php echo('___________________________') ?></u></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>11. Subjects Studied &nbsp;&nbsp;</b><u>All Subjects</u></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td>
                    <b>12. Whether qualified for promotion to the higher class &nbsp;&nbsp;</b><u><?php echo('___________________________') ?></u><br>
                    <br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If so, to which class (In figures)&nbsp;&nbsp;</b><?php echo('________________') ?>
                    <b> (In words)&nbsp;&nbsp;</b><?php echo('___________________________') ?>
                </td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>13. Month upto which the (pupil has paid) School dues/paid &nbsp;&nbsp;</b><?php echo('___________________________') ?></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>14. Any fee concession availed of : if so, the nature of such concession &nbsp;&nbsp;</b><?php echo('___________________________') ?></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>
            <tr>
                <td><b>15. Total No. of working days &nbsp;&nbsp;</b><u>178</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>16. Total No. of working days present &nbsp;&nbsp;</b><u>200</u></td>
            </tr>
            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>17. Whether NCC cadet/Boy Scout/Girl Guide (details may be given) &nbsp;&nbsp;</b><u>N/A</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>18. Games played or extra-curricular activities in which the pupil usually took part (mention achievement level therein)<br>
                        <br>&nbsp;&nbsp;</b><?php echo('________________________________________________') ?>
                </td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>19. General Conduct &nbsp;&nbsp;</b><u>Good</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>20. Date of application for certificate &nbsp;&nbsp;</b><u><?php echo(date('d/m/Y')) ?></u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>21. Date of issue of certificate &nbsp;&nbsp;</b><u><?php echo(date('d/m/Y')) ?></u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>22. Reason for leaving the school &nbsp;&nbsp;</b><u>Transfer</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td><b>23. Any other remarks &nbsp;&nbsp;</b><u>________________</u></td>
            </tr>

            <tr>
                <td> &nbsp;<br></td>
            </tr>

            <tr>
                <td> &nbsp;<br>&nbsp;<br>&nbsp;<br></td>
            </tr>

        </table>

        <div style="width: 240px;  display: inline;"><b>Signature<br> Class teacher</b></div>
        <div style="width: 180px;  display: inline;"><b>Checked by (State full Name and Designation)</b></div>
        <div style="width: 120px;display: inline;  margin-left:90px; padding-left: 70px;"><b>Principal</b></div>
    </center>
    <?php

}

function studentdetails()
{
    $studentid = cleanVar($_GET['studentid']);
    $sql = "SELECT *
        FROM `tblstudent` AS t1,
        `tblstudentacademichistory` AS t2,
        `tblclassmaster` AS t3,
        `tblsection` AS t4,
        `tblclsecassoc` AS t5,
        `tblparent` AS t6,
        `tbluserparentassociation` AS t7,
        `tblstudentdetails` AS t8,
        `tblstudentcontact` AS t9
		
        WHERE t1.studentid = $studentid
        AND t1.studentid = t2.studentid
        AND t1.studentid = t8.studentid
        AND t1.studentid = t9.studentid
	AND t2.clsecassocid = t5.clsecassocid
        AND t5.classid = t3.classid
        AND t5.sectionid = t4.sectionid
        AND t1.studentid = t7.studentid
        AND t7.parentid = t6.parentid
	";

    $result = dbSelect($sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $studentdetails[] = $row;
    }
    return $studentdetails;
}

<?php
 /*
* 360-Parent, part of 360 degree app, created to filter and clean the data.
* Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
* Page details here:
* Updates here: Return ajax response called from quickStudent.php to display student info registered through add quick student form
*/

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once '../../lib/processFunctions.php';

    if ($_REQUEST['call'] == 'createStudentQuick') {
        if (!empty($_REQUEST["scholarnumber"])) {
            $sql = " SELECT t1.firstname, t1.middlename, t1.lastname, t1.scholarnumber, t2.email,
                t2.mobile, t5.classname, t6.sectionname, t8.parentfirstname, t8.parentmiddlename,
                t8.parentlastname, t9.username

                FROM `tblstudent` AS t1,
                `tblstudentcontact` AS t2,
                `tblstudentacademichistory` AS t3,
                `tblclsecassoc` AS t4,
                `tblclassmaster` AS t5,
                `tblsection` AS t6,
                `tbluserparentassociation` AS t7,
                `tblparent` AS t8,
                `tbluser` AS t9

                WHERE t1.scholarnumber = '$_REQUEST[scholarnumber]'
                AND t1.instsessassocid = '$_SESSION[instsessassocid]'
                AND t1.studentid = t2.studentid
                AND t1.studentid = t3.studentid
                AND t3.clsecassocid = t4.clsecassocid
                AND t4.classid = t5.classid
                AND t4.sectionid = t6.sectionid
                AND t1.studentid = t7.studentid
                AND t7.parentid = t8.parentid
                AND t7.userid = t9.userid";
          
            $result = dbSelect($sql);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                echo "
                    <table class=\"table table-bordered table-hover table-condensed\">
                        <tr class=\"info\">
                            <th>Scholarnumber</th>
                            <th>Email</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Parent Name</th>
                            <th>Mobile</th>
                        </tr>
                        <tr>
                            <td><a href=\"studentDashboard.php?scholarnumber=$_REQUEST[scholarnumber]&search=Search\">$row[scholarnumber]</a></td>
                            <td><a href=\"studentDashboard.php?scholarnumber=$_REQUEST[scholarnumber]&search=Search\">$row[username]</a></td>
                            <td><a href=\"studentDashboard.php?scholarnumber=$_REQUEST[scholarnumber]&search=Search\">$row[firstname] $row[middlename] $row[lastname]</a></td>
                            <td><a href=\"studentDashboard.php?scholarnumber=$_REQUEST[scholarnumber]&search=Search\">$row[classname] - $row[sectionname]</a></td>
                            <td><a href=\"studentDashboard.php?scholarnumber=$_REQUEST[scholarnumber]&search=Search\">$row[parentfirstname] $row[parentmiddlename] $row[parentlastname]</a></td>
                            <td><a href=\"studentDashboard.php?scholarnumber=$_REQUEST[scholarnumber]&search=Search\">$row[mobile]</a></td>
                        </tr>
                    </table>
                   ";
            } else {
                echo "<span class='text-danger'> Sorry..! no details found</span>";
            }
        }
    }

<?php
/*
 * 360 - School Empowerment System.
 * Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
 * Page details here: Page for quick new student registration
 * Updates here:
 */

/* Assign the breadcrumb page name for current page */

/* bread crumb page variables ends */

//call the main config file, functions file and header

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>


<script type="text/javascript">
    function checkAvailability() {
        jQuery.ajax({
            url: "scholarno_availability.php",
            data: 'scholarnumber=' + $("#scholarnumber").val(),
            type: "GET",
            success: function (data) {
                $("#student-scholarnumber-status").html(data);
            },
            error: function () {
            }
        });
    }

    function showquickstudent() {
        jQuery.ajax({
            url: "<?php echo DIR_FILES ?>/student/quickstudentdetails.php",
            data: {
                call: 'createStudentQuick',
                scholarnumber: $("#scholarnumber").val(),
                email: $("#email").val(),
                firstname: $("#firstname").val(),
                lastname: $("#lastname").val(),
                classid: $("#classid").val(),
                sectionid: $("#sectionid").val(),
                gender: $("#gender").val(),
                mobile: $("#mobile").val(),
                category: $("#category").val(),
                relation: $("#relation").val(),
                parentfirstname: $("#parentfirstname").val(),
                parentlastname: $("#parentlastname").val(),
                siblingid: $("#siblingid").val(),
                scholarnum: $("#scholarnum").val(),
                dob: $("#dob").val(),
                submit: $("#submit").val()

            },
            type: "GET",
            success: function (data) {
                $("#showinputdetails").html(data);
            },
            error: function () {
            }
        });
    }

    $(document).ready(function () {
        $("#scholar_list").keyup(function () {
            $.ajax({
                type: "POST",
                url: "loadScholarData.php",
                data: 'keyword=' + $(this).val() + '&instid=' +<?= $_SESSION['instsessassocid'] ?>,
                success: function (data) {
                    $("#suggesstion-box").show();

                    $("#suggesstion-box").html(data);
                }
            });
        });
    });

    function scholarStat() {
        if (document.getElementById('scholar_list').disabled) {
            document.getElementById('scholar_list').disabled = false;
            document.getElementById('suggesstion-box').style.display = 'none';
            document.getElementById('siblngsubmit').disabled = false;
        } else {
            document.getElementById('scholar_list').disabled = true;
            document.getElementById('suggesstion-box').style.display = 'none';
            document.getElementById('siblngsubmit').disabled = true;
        }
    }

    function selectItem(val, sibId)
    {
        document.getElementById('scholar_list').value = val;
        document.getElementById('siblingid').value = sibId;
        document.getElementById('suggesstion-box').style.display = 'none';
        window.location.href = 'quickStudent.php?siblingid=' + sibId;
    }

</script>

<div class="container">
    <div id="showinputdetails"></div>
    <!-- Modal -->
    <span class="clearfix">&nbsp;<br></span>
    <?php if (isset($_REQUEST['sibling']) && $_REQUEST['sibling'] == 1) {
    ;
}  ?>
    <div class="container">
        <?php
        renderMsg();
        if (isset($_GET['siblingid']) && !empty($_GET['siblingid'])) {
            $studentdetailsArray = getStudentDetails(); ?>
            <div class="alert alert-success"> Sibling Attached Successfully : The desired sibling profile is now attached.</div>
            <?php

        } else {
            ?>
            <form class="form-inline" name="siblingfrm" enctype="multipart/form-data" method="post">
                <div class="col-lg-5 col-md-5">
                    <label>
                        <input type="checkbox" class="btn-default" 
                               id="sibling" name="sibling"  tabindex="1" value ="1" 
                               required="true"  style="width: auto"  onchange=" scholarStat();"> 
                        Is Any Sibling ?
                    </label>

                    <input type="text"  id="scholar_list" class="form-control"  name="scholarnum" 
                           palceholder="Is Any Sibling" 
                           value ="<?php echo submitFailFieldValue("siblingid"); ?>"  
                           disabled="disabled" onblur="document.getElementById('suggesstion-box').style.display = 'none'" 
                           required="true" placeholder="Enter ScholarNo"/>

                                   <!--<input type="submit" name="submit" value="Attach" class="btn btn-success" 
                                           id="siblngsubmit" disabled="disabled">-->
                </div><!-- /.col-lg-4 -->
                <div class="col-lg-12 col-md-12 col-sm-12" style="align: right;">    
                    <div class="autocomplete" id="suggesstion-box" style="z-index: 100;display: none" ></div>
                </div>

            </form>    
<span class="clearfix"> &nbsp;<br><br><br></span>
            <?php

        }
        ?>
            
        <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform" enctype="multipart/form-data" >

            <input type="hidden" name="mode" value="<?php if (isset($_REQUEST['mode'])) {
            echo cleanVar($_REQUEST['mode']);
        } ?>">
            <input type="hidden" name="issibling" value="<?php
            if (isset($_GET['sibling']) && $_GET['sibling'] == 1) {
                echo $_GET['sibling'];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="studenttype" value="1">
            <input type="hidden" name="siblingid" id="siblingid" value="<?php
            if (isset($_GET['siblingid']) && !empty($_GET['siblingid'])) {
                echo $_GET['siblingid'];
            } else {
                echo '';
            }
            ?>">
            <input type="hidden" name="steps" value="save">

<?php //renderMsg();?>

            <div class="col-lg-4 col-md-4">
                <label for="scholarnumber">Scholar No*</label>
                <input type="text" class="form-control" id="scholarnumber" 
                       name="scholarnumber" placeholder="Scholar No" onBlur="checkAvailability()"
                       value ="<?php echo submitFailFieldValue("scholarnumber") ?>" required="true" /> 
                <span id="student-scholarnumber-status"></span>
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="firstname">First Name*</label>
                <input type="text" class="form-control" id="firstname"  name="firstname" placeholder="Firstname" 
                       value ="<?php echo submitFailFieldValue("firstname"); ?>" required="true" >
            </div>    

            <div class="col-lg-4 col-md-4">
                <label for="lastname">Last Name*</label>
                <input type="text" id="lastname" class="form-control"
                       value ="<?php
                       if (isset($studentdetailsArray['lastname'])) {
                           echo $studentdetailsArray['lastname'];
                       } else {
                           echo submitFailFieldValue("lastname");
                       }
                       ?>" 
                       name="lastname" placeholder="Lastname"   required="true"   >
            </div>  
            <span class="clearfix">&nbsp;<br></span>
            <span class="clearfix">&nbsp;<br></span>
            <div class=" col-lg-2 col-md-4">
                <label for="gender">Gender*</label><br>
                <div class="input-group">
                    <span class="input-group-addon">
<?php $gender = getGender('Male'); ?>
                        <input type="radio" name="gender" id="gender" 
                               value ="<?php echo $gender; ?>"> Male 
                    </span>
                    <span class="input-group-addon">
<?php $gender = getGender('Female') ?>
                        <input  type="radio" name="gender" id="gender"
                                value ="<?php echo $gender; ?>" > Female 
                    </span>
                </div>
            </div>

            <div class="col-lg-2 col-md-4">
                <label for="classid">Class*</label>
                <select name="classid" id="classid"   class="form-control"  required="true" >
<?php echo PopulateSelect("classname", submitFailFieldValue("classid")); ?>
                </select>
            </div>


            <div class="col-lg-2 col-md-4">
                <label for="sectionid" required="true" >Section*</label>
                <select name="sectionid" id="sectionid"  class="form-control"  required="true" >
<?php echo PopulateSelect("sectionname", submitFailFieldValue("sectionid")); ?>
                </select>
            </div>
            
            <div class="col-lg-3 col-md-3">
                <label for="parentfirstname"> Parent Firstname* </label>
                <input type="text" name="parentfirstname" id="parentfirstname" class="form-control" required="true" 
                       value ="<?php
                       if (isset($studentdetailsArray['parentfirstname'])) {
                           echo $studentdetailsArray['parentfirstname'];
                       } else {
                           echo submitFailFieldValue("parentfirstname");
                       }
                       ?>"  >

            </div>

            <div class="col-lg-3 col-md-3">
                <label for="parentlastname"> Parent Lastname* </label>
                <input type="text" name="parentlastname" id="parentlastname" class="form-control" required="true"
                       value ="<?php
                       if (isset($studentdetailsArray['parentlastname'])) {
                           echo $studentdetailsArray['parentlastname'];
                       } else {
                           echo submitFailFieldValue("parentlastname");
                       }
                       ?>" >

            </div>  

            <span class="clearfix">&nbsp;<br></span>
            <span class="clearfix">&nbsp;<br></span>
            <div class="col-lg-2 col-md-3">
                <label for="mobile">Mobile*</label>
                <input  type="text" class="form-control" maxlength="10"
                        value ="<?php
                        if (!empty($studentdetailsArray['mobile'])) {
                            echo $studentdetailsArray['mobile'];
                        } else {
                            echo submitFailFieldValue("mobile");
                        }
                        ?>"
                        id="mobile"  name="mobile" required="true" />
            </div>
            <div class=" col-lg-2 col-md-3">
                <label for="dob">D.O.B </label>
                <input type="date" class="form-control"  id="dob"   name="dob"  placeholder="YYYY/MM/DD"
                       value ="<?php echo submitFailFieldValue("dob"); ?>" >
            </div>
            <div class="col-lg-2 col-md-3">
                <label for="category" required="true" >Category</label>
                <select name="category" id="category"   class="form-control"   >
<?php echo PopulateSelect("category", submitFailFieldValue("category")); ?>
                </select>
            </div>
            
            <div class="col-lg-2 col-md-3">
                <label for="relation"> Relation </label>
                <select name="relation" id="relation" class="form-control" >
<?php echo populateSelect("relation", submitFailFieldValue("relation")); ?> 
                </select>
            </div>

            <span class="clearfix">&nbsp;<br></span>   
            <span class="clearfix">&nbsp;<br></span>
            <span class="clearfix">&nbsp;<br></span>

            <div class="controls" align="center">
                <input id="clearDiv" type="button" value="Cancel" class="btn-lg btn">
                <!-- Button trigger modal -->
                <input  type="button" name="submit" value="Save" id="submit" onclick="showquickstudent()" class="btn btn-lg btn-success">
                <input  type="button" name="addnew" value="Add New" id="addnew"  onclick="window.location.href = 'quickStudent.php'" class="btn-lg btn btn-info">

            </div>
            <span class="clearfix">
                <p>&nbsp;</p>
            </span> 
            <!--//Container Closed-->
        </form>
    </div> 
</div>

<?php
require VIEW_FOOTER;

function getStudentDetails()
{
    $sqlString = "SELECT * FROM tblstudent AS T1 
                    LEFT JOIN tblstudentcontact AS T2 ON T1.studentid=T2.studentid
                    LEFT JOIN tblstudentdetails AS T3 ON T1.studentid=T3.studentid
                    LEFT JOIN tblstudentacademichistory AS T4 ON T1.studentid=T4.studentid
                    LEFT JOIN tblclsecassoc AS T5 ON T4.clsecassocid=T5.clsecassocid
                    LEFT JOIN tbluserparentassociation AS T6 ON T1.studentid = T6.studentid
                    LEFT JOIN tblparent AS T7 ON T6.parentid = T7.parentid
                    LEFT JOIN tbluser AS T8 ON T8.userid = T6.userid
                    ";

    if (isset($_GET['siblingid']) && !empty($_GET['siblingid'])) {
        $sqlString .=" WHERE T1.studentid='" . $_GET['siblingid'] . "'  GROUP BY T1.studentid";
    }

    if (!empty($sqlString)) {
        $result = dbSelect($sqlString) or die('Query Error');

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $studentDetails = $row;
            }
        }
    }
    return $studentDetails;
}

function getGender($name)
{
    $sql = "SELECT t1.mastercollectionid FROM `tblmastercollection` AS t1,`tblmastercollectiontype` AS t2
            WHERE t1.mastercollectiontypeid = t2.mastercollectiontypeid AND t2.mastercollectiontype = 'Gender'
            AND t1.collectionname = '$name'";

    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);

    return $row['mastercollectionid'];
}

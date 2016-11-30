<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new students
 * Updates here:
 */

//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;

// check is the form is in edit mode, if so pass on JS for confirm message
$jsConfirm = "";

if (!empty(isEditable())) {
    $jsConfirm = "onsubmit=\"return confirm('You are about to update the record, please be sure before you proceed!');\"";
}

if (!isset($_REQUEST['sid']) || $_REQUEST['sid'] <= 0) {
    $class = "class='disabled disabledTab' data-toggle='tab' ";
} else {
    $class = "";
}
?>


<script type="text/javascript">
    function copyParentDetails() {

        $('#permasuburbid').val($('#currentsuburbid').val());
        $("#permasuburbid").attr("disabled", "disabled");

        $('#permaaddress1').val($('#currentaddress1').val());
        $("#permaaddress1").attr("disabled", "disabled");

        $('#permaaddress2').val($('#currentaddress2').val());
        $("#permaaddress2").attr("disabled", "disabled");

        $('#permazipcode').val($('#currentzipcode').val());
        $('#permazipcode').attr("disabled", "disabled");

        $('#permacityid').val($('#currentcityid').val());
        $('#permacityid').attr("disabled", "disabled");

        $('#permastateid').val($('#currentstateid').val());
        $('#permastateid').attr("disabled", "disabled");

        $('#permacountryid').val($('#currentcountryid').val());
        $('#permacountryid').attr("disabled", "disabled");



    }
    $(document).ready(function ($)
    {
        // for displaying modal for confirming before updating  record...//
        $('#save').click(function ()
        {
            $('#step').val('save');
            $('#myModal').modal('show');
            return false;

        });
        $('#next').click(function ()
        {
            $('#step').val('next');
            $('#myModal').modal('show');
            return false;

        });

        $('#submitForm').click(function ()
        {
            $('#imform').submit();
            $('#myModal').modal('hide');
        });
    });
</script>
<div class="container"> 
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Update Record&hellip; !</h4>
                </div>
                <div class="modal-body">
                    <p class="alert-danger"> Do You Want To Update Record as  action once done , couldn't be reverted back.</p> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitForm">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    // check whether the page is in edit mode, if so call the function
    if ((isset($_GET) && $_REQUEST['mode'] == 'edit') || (isset($_GET['siblingid']) && !empty($_GET['siblingid']))) {
        $studentdetailsArray = getStudentDetails(); //echoThis($studentdetailsArray); die;
    }
    ?>

    <ul class="nav nav-tabs" role="tablist" data >
        <li <?= $class; ?> > <a href="studentPersonal.php<?php echo isEditable(); ?>">Personal</a></li>
        <li class="active"><a href="studentParent.php<?php echo isEditable(); ?>">Parents</a></li>
        <li <?= $class; ?>><a href="studentMedical.php<?php echo isEditable(); ?>">Medical</a></li>
        <li <?= $class; ?>><a href="studentFees.php<?php echo isEditable(); ?>">Fees Rule</a></li>
        <li <?= $class; ?>><a href="studentDocument.php<?php echo isEditable(); ?>">Documents</a></li>
        <li <?= $class; ?>><a href="studentFeeDetails.php<?php echo isEditable(); ?>">Fee Details</a></li>
    </ul>  
    <!-- END TAB -->

    <span class="clearfix">&nbsp;<br></span>

    <?php
    renderMsg();
    $showAllParent = getAllParentDetails();
    $parentContactDetails = getParentContactDetails();
    
    $sno = 1;
    if (!isset($_REQUEST['parentid']) && (int) $showAllParent['rowcount'] > 0 && $_REQUEST['mode'] != 'add') {
        $email = (!empty($_GET['email'])) ? cleanVar($_GET['email']) : ''; ?>

        <!-- Showing Parent List -->

        <div class="alert alert-success">
            <form action="" enctype="multipart/form-data" method="GET" >
                <span class="text-info">The parents details attached with your profile is as follows :<br/><br/>
                    <ul class="list-group">
                        <?php foreach ($showAllParent['records'] as $parentKey) { ?>
                            <li class="list-group-item"> 
                                <span class="glyphicon glyphicon-chevron-right">  </span>&nbsp;&nbsp;
                                <a href="studentParent.php?sid=<?php echo $_GET['sid'] ?>&mode=edit&parentid=<?php echo $parentKey['parentid'] ?>">
                                    <?php echo $parentKey['parentname']; ?> &nbsp;-&nbsp; ( <?php echo $parentKey['relation']; ?> ) 
                                </a>
                            </li>
                            <?php $sno++; } ?>
                    </ul>
                </span> 
                <div align="center">
                    <a href="studentParent.php?sid=<?= $_REQUEST['sid'] ?>&mode=add&pcount=<?php echo $showAllParent['rowcount']; ?>">
                        <button type="button" value="Add Parent"  name="addparent" class="btn btn-success" id="addparent">Add Parent</button>
                    </a>
                </div>
            </form>
        </div> 

        <!-- END PARENT LIST -->
    <?php 
    } else {
        ?>

        <?php
        if (!isset($_REQUEST['parentid']) && isset($_REQUEST['pcount']) <= 0) {
            $mode = 'add'; ?>
            <div class="alert alert-warning">
                <span class="text-info"> 
                    There is no parent information found with this student. Please add a parent now with all
                    following required fields.
                </span> 
            </div>
            <?php

        } else {
            if (isset($_REQUEST['mode']) && $_REQUEST['mode'] != 'add' && isset($_REQUEST['parentid'])) {
                $parentDetailsArray = getParentDetails();
                $mode = 'edit';
            } else {
                $mode = 'add';
            }
        } ?>

        <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform" enctype="multipart/form-data">

            <input type="hidden" name="mode"       value="<?php if (isset($mode)) {
            echo $mode;
        } ?>">
            <input type="hidden" name="sid"        value="<?php if (isset($_REQUEST['sid'])) {
            echo $_REQUEST['sid'];
        } ?>">
            <input type="hidden" name="parentid"   value="<?php if (isset($_REQUEST['parentid'])) {
            echo $_REQUEST['parentid'];
        } ?>">
            <input type="hidden" name="step" value="" />

            <div class="col-lg-4 col-md-4 col-md-4">
                <label for="parentfirstname" class="small">First Name*</label>
                <input type="text" id="parentfirstname" class="form-control  "  name="parentfirstname"  
                       value ="<?php
                       if (isset($parentDetailsArray)) {
                           echo $parentDetailsArray[0]['parentfirstname'];
                       } else {
                           echo submitFailFieldValue("parentfirstname");
                       } ?>"  required="true">  
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="parentmiddlename" class="small">Middle Name</label>
                <input type="text" id="parentmiddlename"  class="form-control  " name="parentmiddlename"  
                       value ="<?php
                       if (!empty($parentDetailsArray)) {
                           echo $parentDetailsArray[0]['parentmiddlename'];
                       } else {
                           echo submitFailFieldValue("parentmiddlename");
                       } ?>"> 
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="lastname" class="small">Last Name*</label>
                <input type="text" id="parentlastname"  class="form-control  " name="parentlastname"  required="true"  
                       value ="<?php
                       if (!empty($parentDetailsArray)) {
                           echo $parentDetailsArray[0]['parentlastname'];
                       } else {
                           echo submitFailFieldValue("parentlastname");
                       } ?>" > 
            </div>
            <span class="clearfix">&nbsp;<br></span>
            <div class="col-lg-4 col-md-4">
                <label for="gender" class="small">Gender*</label><br />
                <select name="gender"   class="form-control  " required="true">
                    <?php
                    if (!empty($parentDetailsArray)) {
                        echo PopulateSelect("gender", $parentDetailsArray[0]['gender']);
                    } else {
                        echo PopulateSelect("gender", submitFailFieldValue("gender"));
                    } ?>
                </select>
            </div>


            <div class="col-lg-4 col-md-4">
                <label for="religion" class="small">Religion*</label>
                <select name="religion" id="religion"   class="form-control  " required="true">
                    <?php
                    if (!empty($parentDetailsArray[0]['religion'])) {
                        echo PopulateSelect("religion", $parentDetailsArray[0]['religion']);
                    } elseif (!empty($parentContactDetails['religion'])) {
                        echo PopulateSelect("religion", $parentContactDetails['religion']);
                    } else {
                        echo PopulateSelect("religion", submitFailFieldValue("religion"));
                    } ?>
                </select>
            </div>

            <div class="col-lg-4 col-md-4">
                <label for="category" class="small">Category*</label>
                <select name="category" id="category"  class="form-control  " required="true">
                    <?php
                    if (!empty($parentDetailsArray[0]['category'])) {
                        echo PopulateSelect("category", $parentDetailsArray[0]['category']);
                    } elseif (!empty($parentContactDetails['category'])) {
                        echo PopulateSelect("category", $parentContactDetails['category']);
                    } else {
                        echo PopulateSelect("category", submitFailFieldValue("category"));
                    } ?>
                </select>
            </div>
            <span class="clearfix">&nbsp;<br></span>

            <div class="col-lg-6 col-md-6">
                <label for="email1" class="small">Email</label>
                <input type="text" class="form-control  "  id="email1" name="email1" 
                       value ="" placeholder="eg: abc@example.com"   />
                <p style="font-size: 12px;"> Your email address will be the user-name and password would be emailed automatically. </p>
            </div>  

            <div class="col-lg-6 col-md-6">
                <label for="email1" class="small">Secondary Email</label>
                <input type="text" class="form-control  "  id="email2" name="email2" placeholder="eg: abc@example.com"
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['email2'])) {
                           echo $parentDetailsArray[0]['email2'];
                       } else {
                           echo submitFailFieldValue("email2");
                       } ?>" />
            </div>

            <span class="clearfix">&nbsp;</span>
            <hr>
            <div class="col-lg-2 col-md-2">
                <label for="currentzipcode" class="small">Current Pincode*</label>
                <input type="text" class="form-control  "  
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['currentzipcode'])) {
                           echo $parentDetailsArray[0]['currentzipcode'];
                       } elseif (!empty($parentContactDetails['currentzipcode'])) {
                           echo $parentContactDetails['currentzipcode'];
                       } else {
                           echo submitFailFieldValue("currentzipcode");
                       } ?>"  
                       id="currentzipcode" name="currentzipcode" />

            </div>

            <div class="col-lg-5 col-md-5">
                <label for="currentaddress1" class="small">Current Address 1*</label>
                <input type="text" name="currentaddress1" id="currentaddress1" class="form-control  "  
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['currentaddress1'])) {
                           echo $parentDetailsArray[0]['currentaddress1'];
                       } elseif (!empty($parentContactDetails['currentaddress1'])) {
                           echo $parentContactDetails['currentaddress1'];
                       } else {
                           echo submitFailFieldValue("currentaddress1");
                       } ?>"required="true">
            </div>

            <div class="col-lg-5 col-md-5">
                <label for="currentaddress2" class="small">Current Address 2</label>
                <input type="text" name="currentaddress2" id="currentaddress2" 
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['currentaddress2'])) {
                           echo $parentDetailsArray[0]['currentaddress2'];
                       } elseif (!empty($parentContactDetails['currentaddress2'])) {
                           echo $parentContactDetails['currentaddress2'];
                       } else {
                           echo submitFailFieldValue("currentaddress2");
                       } ?>"  class="form-control  " >
            </div>

            <span class="clearfix">&nbsp;<br></span>

            <div class="col-lg-3 col-md-3">
                <label for="currentsuburbid" class="small">Current Suburb*</label>
                <select name="currentsuburbid" id="currentsuburbid" class="form-control  " required="true">
                    <?php
                    if (!empty($parentDetailsArray[0]['currentsuburbid'])) {
                        echo PopulateSelect("currentsuburb", $parentDetailsArray[0]['currentsuburbid']);
                    } elseif (!empty($parentContactDetails['currentsuburbid'])) {
                        echo PopulateSelect("currentsuburb", $parentContactDetails['currentsuburbid']);
                    } else {
                        echo PopulateSelect("currentsuburb", submitFailFieldValue("currentsuburbid"));
                    } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="currentcityid" class="small">Current City*</label>
                <select name="currentcityid"  id="currentcityid" class="form-control  "  required="true">
                    <?php
                    if (!empty($parentDetailsArray[0]['currentcityid'])) {
                        echo PopulateSelect("cityname", $parentDetailsArray[0]['currentcityid']);
                    } elseif (!empty($parentContactDetails['currentcityid'])) {
                        echo PopulateSelect("cityname", $parentContactDetails['currentcityid']);
                    } else {
                        echo PopulateSelect("cityname", submitFailFieldValue("currentcityid"));
                    } ?>
                </select>
            </div>
            <div class="col-lg-3 col-md-3">
                <label for="currentstateid" class="small">Current State*</label>
                <select name="currentstateid"  id="currentstateid" class="form-control  "  required="true">
                    <?php
                    if (!empty($parentDetailsArray[0]['currentstateid'])) {
                        echo PopulateSelect("statename", $parentDetailsArray[0]['currentstateid']);
                    } elseif (!empty($parentContactDetails['currentstateid'])) {
                        echo PopulateSelect("statename", $parentContactDetails['currentstateid']);
                    } else {
                        echo PopulateSelect("statename", submitFailFieldValue("currentstateid"));
                    } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="currentcountryid" class="small">Current Country*</label>
                <select name="currentcountryid"  id="currentcountryid" class="form-control  "  required="true" >
                    <?php
                    if (!empty($parentDetailsArray[0]['currentcountryid'])) {
                        echo PopulateSelect("countryname", $parentDetailsArray[0]['currentcountryid']);
                    } elseif (!empty($parentContactDetails['currentcountryid'])) {
                        echo PopulateSelect("countryname", $parentContactDetails['currentcountryid']);
                    } else {
                        echo PopulateSelect("countryname", submitFailFieldValue("currentcountryid"));
                    } ?>
                </select>
            </div>
            <span class="clearfix">&nbsp;<br></span>

            <!-- panel for permanent contact address ---------- -->       

            <button type="button" class="btn btn-warning btn-mg" name="detailsmatch" id="detailsmatch"
                    accesskey=""onclick="Javascript : copyParentDetails();">
                Copy Details
            </button>

            <hr>

            <div class="col-lg-2 col-md-2">
                <label for="permazipcode" class="small">Permanent Pincode</label>
                <input type="text" class="form-control  " id="permazipcode"   
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['permazipcode'])) {
                           echo $parentDetailsArray[0]['permazipcode'];
                       } elseif (!empty($parentContactDetails['permazipcode'])) {
                           echo $parentContactDetails['permazipcode'];
                       } else {
                           echo submitFailFieldValue("permazipcode");
                       } ?>"
                       name="permazipcode"  />
            </div>
            <div class="col-lg-5 col-md-5">
                <label for="permaaddress1" class="small">Permanent Address 1*</label>
                <input type="text" name="permaaddress1" id="permaaddress1"  class="form-control  "
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['permaaddress1'])) {
                           echo $parentDetailsArray[0]['permaaddress1'];
                       } elseif (!empty($parentContactDetails['permaaddress1'])) {
                           echo $parentContactDetails['permaaddress1'];
                       } else {
                           echo submitFailFieldValue("permaaddress1");
                       } ?>"  >
            </div>

            <div class="col-lg-5 col-md-5">
                <label for="permaaddress2" class="small">Permanent Address 2</label>
                <input type="text" name="permaaddress2" id="permaaddress2" 
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['permaaddress2'])) {
                           echo $parentDetailsArray[0]['permaaddress2'];
                       } elseif (!empty($parentContactDetails['permaaddress2'])) {
                           echo $parentContactDetails['permaaddress2'];
                       } else {
                           echo submitFailFieldValue("permaaddress2");
                       } ?>"  class="form-control  " >
            </div>

            <span class="clearfix">&nbsp;</span>
            <div class="col-lg-3 col-md-3">
                <label for="permasuburbid" class="small">Permanent Suburb*</label>
                <select name="permasuburbid" id="permasuburbid"   class="form-control  "  required>
                    <?php
                    if (!empty($parentDetailsArray[0]['permasuburbid'])) {
                        echo PopulateSelect("currentsuburb", $parentDetailsArray[0]['permasuburbid']);
                    } elseif (!empty($parentContactDetails['currentcountryid'])) {
                        echo PopulateSelect("currentsuburb", $parentContactDetails['permasuburbid']);
                    } else {
                        echo PopulateSelect("currentsuburb", submitFailFieldValue("permasuburbid"));
                    } ?>
                </select> 
            </div>



            <div class="col-lg-3 col-md-3">
                <label for="permacityid" class="small">Permanent City*</label>
                <select name="permacityid"   id="permacityid" class="form-control  "  >
                    <?php
                    if (!empty($parentDetailsArray[0]['permacityid'])) {
                        echo PopulateSelect("cityname", $parentDetailsArray[0]['permacityid']);
                    } elseif (!empty($parentContactDetails['permazipcode'])) {
                        echo PopulateSelect("cityname", $parentDetailsArray['permacityid']);
                    } else {
                        echo PopulateSelect("cityname", submitFailFieldValue("permacityid"));
                    } ?>
                </select>
            </div>


            <div class="col-lg-3 col-md-3">
                <label for="permastateid" class="small">Permanent State*</label>
                <select name="permastateid"  id="permastateid" class="form-control  " required>
                    <?php
                    if (!empty($parentDetailsArray[0]['permastateid'])) {
                        echo PopulateSelect("statename", $parentDetailsArray[0]['permastateid']);
                    } elseif (!empty($parentContactDetails['permastateid'])) {
                        echo PopulateSelect("statename", $parentDetailsArray['permastateid']);
                    } else {
                        echo PopulateSelect("statename", submitFailFieldValue("permastateid"));
                    } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="permacountryid" class="small">Permanent Country*</label>
                <select name="permacountryid"  id="permacountryid" class="form-control  " >
                    <?php
                    if (!empty($parentDetailsArray[0]['permacountryid'])) {
                        echo PopulateSelect("countryname", $parentDetailsArray[0]['permacountryid']);
                    } elseif (!empty($parentContactDetails['permacountryid'])) {
                        echo PopulateSelect("countryname", $parentDetailsArray['permacountryid']);
                    } else {
                        echo PopulateSelect("countryname", submitFailFieldValue("permacountryid"));
                    } ?>
                </select>
            </div>

            <span class="clearfix">&nbsp;<br></span>


            <!-- panel ends------ -->
            <hr>

            <div class="col-lg-3 col-md-3">
                <label for="phone1" class="small">Primary Phone No. </label>
                <input type="text" class="form-control  " id="phone1" name="phone1"  maxlength="7"
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['phone1'])) {
                           echo $parentDetailsArray[0]['phone1'];
                       } elseif (!empty($parentContactDetails['phone1'])) {
                           echo $parentContactDetails['phone1'];
                       } else {
                           echo submitFailFieldValue("phone1");
                       } ?>"  />	
                <small style="font-size: 12px;"> Please enter 7 digit landline number without area code(eg 27*****)</small>
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="officephone" class="small">Alternate Phone No</label>
                <input type="text" class="form-control  " maxlength="7" minl="10"
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['officephone'])) {
                           echo $parentDetailsArray[0]['officephone'];
                       } else {
                           echo submitFailFieldValue("officephone");
                       } ?>" id="officephone" name="officephone" />	
            </div>


            <div class="col-lg-3 col-md-3">
                <label for="mobile1" class="small">Primary Mobile No*</label>
                <input type="text" class="form-control  " maxlength="10"  
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['mobile1'])) {
                           echo $parentDetailsArray[0]['mobile1'];
                       } elseif (!empty($parentContactDetails['mobile1'])) {
                           echo $parentContactDetails['mobile1'];
                       } else {
                           echo submitFailFieldValue("mobile1");
                       } ?>"id="mobile1" name="mobile1"  />	
            </div>	


            <div class="col-lg-3 col-md-3">
                <label for="mobile2" class="small">Alternate Mobile No</label>
                <input type="text" class="form-control  " maxlength="10" min="10"
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['mobile2'])) {
                           echo $parentDetailsArray[0]['mobile2'];
                       } else {
                           echo submitFailFieldValue("mobile2");
                       } ?>" id="mobile2" name="mobile2" 
                       />	
            </div>			

            <span class="clearfix">&nbsp;</span>

            <div class="col-lg-3 col-md-3">
                <label for="fax1" class="small">Primary Fax</label>
                <input type="text" class="form-control  "  
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['fax1'])) {
                           echo $parentDetailsArray[0]['fax1'];
                       } else {
                           echo submitFailFieldValue("fax1");
                       } ?>" name="fax1" />	
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="fax2" class="small">Secondary Fax</label>
                <input type="text" class="form-control  " 
                       value ="<?php
                       if (!empty($parentDetailsArray[0]['fax2'])) {
                           echo $parentDetailsArray[0]['fax2'];
                       } else {
                           echo submitFailFieldValue("fax2");
                       } ?>" name="fax2" />	
            </div>


            <div class="col-lg-3 col-md-3">
                <label for="qualificationid" class="small">Qualification*</label>
                <select name="qualificationid" class="form-control  "  required>
                    <?php
                    if (!empty($parentDetailsArray[0]['qualificationid'])) {
                        echo PopulateSelect("qualification", $parentDetailsArray[0]['qualificationid']);
                    } else {
                        echo PopulateSelect("qualification", submitFailFieldValue("qualificationid"));
                    } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="occupation" class="small">Occupation*</label>
                <select name="occupation" class="form-control  "  required="true" id="occupation"> 
                    <?php
                    if (!empty($parentDetailsArray[0]['occupation'])) {
                        echo PopulateSelect("occupation", $parentDetailsArray[0]['occupation']);
                    } else {
                        echo PopulateSelect("occupation", submitFailFieldValue("occupation"));
                    } ?>
                </select>
            </div>

            <span class="clearfix">&nbsp;<br></span>

            <div class="col-lg-3 col-md-3">
                <label for="relationid" class="small">Relation*</label>
                <select name="relationid" class="form-control  "   required="true">
                    <?php
                    if (!empty($parentDetailsArray[0]['relationid'])) {
                        echo PopulateSelect("relation", $parentDetailsArray[0]['relationid']);
                    } else {
                        echo PopulateSelect("relation", submitFailFieldValue("relationid"));
                    } ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-3">
                <label for="income" class="small">Annual Income</label>
                <select name="income" class="form-control  "  >
                    <?php
                    if (!empty($parentDetailsArray[0]['income'])) {
                        echo PopulateSelect("income", $parentDetailsArray[0]['income']);
                    } else {
                        echo PopulateSelect("income", submitFailFieldValue("income"));
                    } ?>
                </select>
            </div>

            <span class="clearfix">&nbsp;<br></span> 
            <span class="clearfix"><p>&nbsp;</p></span> 
            <div class="controls" align="center">
                <input id="clearDiv" type="button"  value="Cancel" class="btn">
                <!-- Button trigger modal -->
                <input type="submit" id="submit1"  name="submit"  value="SAVE" class="btn btn-success">
                <input type="submit" id="submit"  name="submit" value="SAVE & NEXT>>" class="btn btn-success">
            </div>
        </form>
        <span class="clearfix">&nbsp;<br></span>
        <span class="clearfix">&nbsp;<br></span>
        <?php 
    } ?>
</div>

<?php

function getStudentDetails()
{
    if ($_REQUEST['mode'] == 'edit' || $_REQUEST['siblingid']) {
        $where = '';
        $sqlString = "SELECT * FROM tblstudent AS T1 
                    LEFT JOIN tblstudentcontact AS T2 ON T1.studentid=T2.studentid
                    LEFT JOIN tblstudentdetails AS T3 ON T1.studentid=T3.studentid
                    LEFT JOIN tblstudentacademichistory AS T4 ON T1.studentid=T4.studentid
                    LEFT JOIN tblclsecassoc AS T5 ON T4.clsecassocid=T5.clsecassocid
                    LEFT JOIN tbluserparentassociation AS T6 ON T1.studentid = T6.studentid
                    LEFT JOIN tbluser AS T7 ON T7.userid = T6.userid
                     ";

        if (isset($_GET['sid']) && is_numeric((int) $_GET['sid'])) {
            $sqlString .=" WHERE T1.studentid='" . $_GET['sid'] . "'"
                    . " AND T1.instsessassocid = $_SESSION[instsessassocid]";
        }

        if (isset($_REQUEST['siblingid']) && !empty($_REQUEST['siblingid'])) {
            $sqlString .=" WHERE T1.studentid='" . $_REQUEST['siblingid'] . "'";
        }
       // echoThis($sqlString . " GROUP BY T1.studentid");
        $result = dbSelect($sqlString . " GROUP BY T1.studentid");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $studentDetails = $row;
            }
            return $studentDetails;
        } else {
            return false;
        }
    }
}

function getParentDetails()
{
    $sqlParent = "SELECT * FROM tblparent AS t1 LEFT JOIN tblparentcontact AS t2 ON t1.parentid=t2.parentid 
                    WHERE t1.parentid=" . cleanVar($_REQUEST['parentid']);

    $resultParent = dbSelect($sqlParent);
    if (mysqli_num_rows($resultParent) > 0) {
        while ($rowParent = mysqli_fetch_assoc($resultParent)) {
            $parentDetail[] = $rowParent;
        }

        return $parentDetail;
    }
    return 0;
}

function getAllParentDetails()
{
    $where = " where 1=1 ";
    if (isset($_GET['sid'])) {
        $studentid = cleanVar($_GET['sid']);
        $row = mysqli_fetch_assoc(dbSelect("SELECT `userid` FROM `tbluserparentassociation` WHERE `studentid` = '$studentid' "));
        $userid = $row['userid'];
    }

    if (isset($studentid) && is_numeric((int) $studentid) && (int) $studentid > 0) {
        $sqlString = "SELECT t1.parentid,UPPER(CONCAT(t2.parentfirstname,' ' , t2.parentmiddlename, ' ' , t2.parentlastname)) as parentname,
                        CASE t3.relationid WHEN '214' THEN 'FATHER' WHEN '215' THEN 'MOTHER' WHEN '216' THEN 'GUARDIAN'  
                        WHEN 0 THEN  'NA' END  AS relation 
                        FROM tbluserparentassociation AS t1 LEFT JOIN tblparent as t2 ON 
                        t1.parentid=t2.parentid  LEFT JOIN tblparentcontact AS t3 ON t1.parentid=t3.parentid  where 1=1   ";
        if (!empty($userid) && $userid > 0) {
            $sqlString .= "AND t1.userid='$userid' ";
        } else {
            $sqlString .= "AND t1.studentid ='$studentid' ";
        }
        $sqlString .=" GROUP BY t1.parentid ORDER BY t3.relationid";
    }
    
    if (isset($sqlString)) {
        //echoThis($sqlString); die;
        $result = dbSelect($sqlString) or die('SQL ERROR');
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $parentArray['records'][] = $row;
            }

            $parentArray['rowcount'] = mysqli_num_rows($result);
        } else {
            $parentArray = 0;
        }
       
        return $parentArray;
    }
    return 0;
}

// check is edit mode is enabled, if so return true.
// e = edit
function isEditable()
{
    $newUrlString = '?';

    if (isset($_GET['sid'])) {
        $newUrlString.='sid=' . cleanVar($_GET['sid']);
    }
    if (isset($_GET['mode'])) {
        $newUrlString.='&mode=' . cleanVar($_GET['mode']);
    }

    if ($newUrlString == '?') {
        return false;
    } else {
        return $newUrlString;
    }
}

function getParentContactDetails()
{
    $studentid = cleanVar($_GET['sid']);
    $sql = " SELECT *  FROM 
              `tblstudentcontact` AS t1,
              `tblstudentdetails` AS t2 
              WHERE t1.studentid = '$studentid'
              AND t1.studentid = t2.studentid";
    
    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $studentContactDetails = $row;
        }

        return $studentContactDetails;
    } else {
        return 0;
    }
}

require_once VIEW_FOOTER;
?>
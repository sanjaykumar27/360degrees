<script type="text/javascript">
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "scholarno_availability.php",
            data: 'scholarnumber=' + $("#scholarnumber").val(),
            type: "GET",
            success: function (data) {
                $("#student-scholarnumber-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function () {}
        });
    }
</script>
<!-- Modal -->

<?php if (isset($_REQUEST['siblingid']) && $_REQUEST['siblingid']) {
    ?>
    <div class="alert alert-success"> Sibling Attached Successfully : The desired sibling profile is now attached.</div>

<?php 
} elseif ($_REQUEST['mode'] != 'edit') {
    ?>

    <!-- IS ANY sibiLING ---------------------------->
    <div id="siblingDiv">
        <form name="siblingfrm" enctype="multipart/form-data" method="post">
            <!-- check to enter the sibling scholar no -->
            <span class="clearfix"><br></span>
            <div class="col-lg-4">
                <input type="checkbox" class="btn-default" id="sibling" name="sibling" tabindex="1" value ="1" required  style="width: auto"  onchange="scholarStat();"/>
                <label for="scholarnum">Is Any Sibling*</label>&nbsp;&nbsp;
                <input type="text"  id="scholar_list" name="scholarnum" tabindex="1" value =""  disabled="disabled" onblur="document.getElementById('suggesstion-box').style.display = 'none'" required>
                <div id="suggesstion-box" style="height:180px; padding:05px; overflow-y: auto; display:none; float: right; left:100px; border: 0px;z-index:200000" ></div>
            </div>
        </form>
    </div>
    <div class="clearfix">&nbsp;</div>


<?php 
} ?>
<div id="studentdetails">
    <?php renderMsg(); ?>
    <span class="clearfix">&nbsp;<br></span>

    <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imform" enctype="multipart/form-data" >
        <input type="hidden" name="steps" value="" id="steps">
        <input type="hidden" name='attach' id="attach" value="<?php
        if (isset($_GET['sibling']) && $_GET['sibling'] == 1) {
            echo '1';
        } else {
            echo '0';
        }
        ?>" >
        <input type="hidden" name="mode" value="<?php if (isset($_REQUEST['mode'])) {
            echo cleanVar($_REQUEST['mode']);
        } ?>">
        <input type="hidden" name="sid" id="sid" value="<?php
        if (isset($_GET['sid'])) {
            echo cleanVar($_GET['sid']);
        } else {
            echo '0';
        }
        ?>">           

        <?php
        // check whether the page is in edit mode, if so call the function
        if ((isset($_GET) && $_REQUEST['mode'] == 'edit') || (isset($_GET['siblingid']) && !empty($_GET['siblingid']))) {
            $studentdetailsArray = getStudentDetails(); //echoThis($studentdetailsArray); die;
        }
        ?>

        <input type="hidden" name="siblingid" id="siblingid" value="<?php
        if (isset($_GET['siblingid']) && !empty($_GET['siblingid'])) {
            echo $_GET['siblingid'];
        } else {
            echo '';
        }
        ?>">
        <!-- Scholar No ----------------- -->

        <div class=" col-lg-3 col-md-3">
            <label for="scholarnumber" class="small">Scholar No*</label>
            <input type="text" class="form-control  " id="scholarnumber" 
                   name="scholarnumber" placeholder="Scholar No" onBlur="checkAvailability()"
                   value ="<?php
                   if (!empty($studentdetailsArray['scholarnumber'])) {
                       echo $studentdetailsArray['scholarnumber'];
                   } else {
                       echo submitFailFieldValue("scholarnumber");
                   }
                   ?>" required="true" >
            <span id="student-scholarnumber-status"></span>
        </div>
        <!-- First Name --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="firstname" class="small">First Name*</label>
            <input type="text" class="form-control  " id="firstname"  required="true"
                   name="firstname" placeholder="Firstname" 
                   value ="<?php
                   if (!empty($studentdetailsArray['firstname'])) {
                       echo $studentdetailsArray['firstname'];
                   } else {
                       echo submitFailFieldValue("firstname");
                   }
                   ?>" >
        </div>    

        <!-- Middle Name --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="middlename" class="small">Middle Name</label>
            <input type="text" id="middlename" class="form-control  "  name="middlename" 
                   value ="<?php
                   if (!empty($studentdetailsArray['middlename'])) {
                       echo $studentdetailsArray['middlename'];
                   } else {
                       echo submitFailFieldValue("middlename");
                   }
                   ?>" placeholder="Middlename"  >
        </div>

        <!-- Last name --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="lastname" class="small">Last Name</label>
            <input type="text" id="lastname" class="form-control  " 
                   value ="<?php
                   if (!empty($studentdetailsArray['lastname'])) {
                       echo $studentdetailsArray['lastname'];
                   } else {
                       echo submitFailFieldValue("lastname");
                   }
                   ?>"  name="lastname" placeholder="Lastname"    >
        </div> 
        <span class="clearfix">&nbsp;<br></span>

        <!-- Student type --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="studenttype" class="small">Student Type</label>
            <select class="form-control  " name="studenttype"  id="studenttype" >
                <?php
                if (!empty($studentdetailsArray['studenttype'])) {
                    echo PopulateSelect("studenttype", $studentdetailsArray['studenttype']);
                } else {
                    echo PopulateSelect("studenttype", submitFailFieldValue("studenttype"));
                }
                ?>
            </select>  
        </div>
        <!-- Gender field --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="gender" class="small">Gender*</label>
            <select class="form-control  "   name="gender" id="gender"  required="true"  >
                <?php
                if (!empty($studentdetailsArray['gender'])) {
                    echo PopulateSelect("gender", $studentdetailsArray['gender']);
                } else {
                    echo PopulateSelect("gender", submitFailFieldValue("gender"));
                }
                ?>

            </select>
        </div>
        <!-- Class Field --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="classid" class="small">Class*</label>
            <select name="classid" id="classid"   class="form-control  "  required="true">
                <?php
                if (!empty($studentdetailsArray['classid'])) {
                    echo PopulateSelect("classname", $studentdetailsArray['classid']);
                } else {
                    echo PopulateSelect("classname", submitFailFieldValue("classid"));
                }
                ?>
            </select>
        </div>

        <!-- Section Field --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="sectionid" class="small">Section*</label>
            <select name="sectionid" id="sectionid"   class="  form-control" required="true" >
                <?php
                if (!empty($studentdetailsArray['sectionid'])) {
                    echo PopulateSelect("sectionname", $studentdetailsArray['sectionid']);
                } else {
                    echo PopulateSelect("sectionname", submitFailFieldValue("sectionid"));
                }
                ?>
            </select>
        </div>
     <span class="clearfix">&nbsp;<br></span>
        <!-- D.O.B Field --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="dob" class="small">D.O.B* </label>
            <input type="date" class="form-control  "  id="dob" name="dob"  placeholder="YYYY/MM/DD"
                   value ="<?php
                   if (!empty($studentdetailsArray['dob'])) {
                       echo $studentdetailsArray['dob'];
                   } else {
                       echo submitFailFieldValue("dob");
                   }
                   ?>" required="true">
        </div>
        
        <!-- Email field --------------- -->

        <div class="col-lg-3 col-md-3">
            <label for="category" class="small">Category*</label>
            <select name="category" id="category"    class="  form-control" required="true" >
                <?php
                if (!empty($studentdetailsArray['category'])) {
                    echo PopulateSelect("category", $studentdetailsArray['category']);
                } else {
                    echo PopulateSelect("category", submitFailFieldValue("category"));
                }
                ?>
            </select>
        </div>

        <div class="col-lg-3 col-md-3">
            <label for="religion" class="small">Religion*</label>
            <select name="religion" id="religion"   class="  form-control" required="true" >
                <?php
                if (!empty($studentdetailsArray['religion'])) {
                    echo PopulateSelect("religion", $studentdetailsArray['religion']);
                } else {
                    echo PopulateSelect("religion", submitFailFieldValue("religion"));
                }
                ?>
            </select>
        </div>
        <div class="col-lg-3 col-md-3">
            <label for="email" class="small">Email</label>
            <input type="text" class="form-control  " id="email" name="email" 
                   value ="<?php
                   if (!empty($studentdetailsArray['username'])) {
                       echo $studentdetailsArray['username'];
                   } else {
                       echo submitFailFieldValue("username");
                   }
                   ?>"  >
            <p style="font-size: 12px;">The email is the user-name; password would be email automatically. </p>
        </div>
        <span class="clearfix">&nbsp;<br></span>
        <hr>

        <!-- panel for contact information starts here ------_____------------- -->
        <!-- current address ---- -->
        <div class="col-lg-6 col-md-6">
            <label for="currentaddress1" class="small">Current Address 1*</label>
            <input type="text" name="currentaddress1"  id="currentaddress1" 
                   value ="<?php
                   if (!empty($studentdetailsArray['currentaddress1'])) {
                       echo $studentdetailsArray['currentaddress1'];
                   } else {
                       echo submitFailFieldValue("currentaddress1");
                   }
                   ?>"  class="form-control  ">
        </div>	
        <div class="col-lg-6 col-md-6">
            <label for="currentaddress2" class="small">Current Address 2</label>
            <input type="text" name="currentaddress2"  id="currentaddress2" 
                   value ="<?php
                   if (!empty($studentdetailsArray['currentaddress2'])) {
                       echo $studentdetailsArray['currentaddress2'];
                   } else {
                       echo submitFailFieldValue("currentaddress2");
                   }
                   ?>" class="form-control  ">
        </div>	

        <span class="clearfix">&nbsp;<br></span>

        <!-- current suburb------------------------ -->

        <div class="col-lg-4 col-md-4">
            <label for="currentsuburbid" class="small">Current Suburb*</label>
            <select  name="currentsuburbid" id="currentsuburbid"   class="  form-control" required="true">
                <?php
                if (!empty($studentdetailsArray['currentsuburbid'])) {
                    echo PopulateSelect("currentsuburb", $studentdetailsArray['currentsuburbid']);
                } else {
                    echo PopulateSelect("currentsuburb", submitFailFieldValue("currentsuburbid"));
                }
                ?>
            </select>
        </div> 	

        <!-- current pincode----------------------- -->             
        <div class="col-lg-2 col-md-2">
            <label for="currentzipcode" class="small">Current Pincode*</label>
            <input type="text" class="form-control  "  required="true"
                   value ="<?php
                   if (!empty($studentdetailsArray['currentzipcode'])) {
                       echo $studentdetailsArray['currentzipcode'];
                   } else {
                       echo submitFailFieldValue("currentzipcode");
                   }
                   ?>"id="currentzipcode"   name="currentzipcode"  />
        </div>
        <!-- current city------------------------ -->         
        <div class="col-lg-2 col-md-2">
            <label for="currentcityid" class="small">Current City*</label>
            <select name="currentcityid" id="currentcityid"  class="form-control  " required="true">
                <?php
                if (!empty($studentdetailsArray['currentcityid'])) {
                    echo PopulateSelect("cityname", $studentdetailsArray['currentcityid']);
                } else {
                    echo PopulateSelect("cityname", submitFailFieldValue("currentcityid"));
                }
                ?>
            </select>
        </div>	
        <!-- ------Current state ------------------ -->
        <div class="col-lg-2 col-md-2">
            <label for="currentstateid" class="small">Current State*</label>
            <select name="currentstateid" id="currentstateid"  class="form-control  " required="true">
                <?php
                if (!empty($studentdetailsArray['currentstateid'])) {
                    echo PopulateSelect("statename", $studentdetailsArray['currentstateid']);
                } else {
                    echo PopulateSelect("statename", submitFailFieldValue("currentstateid"));
                }
                ?>
            </select>
        </div>
        <!-- ------Current Country ------------------ -->
        <div class="col-lg-2 col-md-2">
            <label for="currentcountryid" class="small">Current Country*</label>
            <select name="currentcountryid" id="currentcountryid"  class="form-control  " required="true" >
                <?php
                if (!empty($studentdetailsArray['currentcountryid'])) {
                    echo PopulateSelect("countryname", $studentdetailsArray['currentcountryid']);
                } else {
                    echo PopulateSelect("countryname", submitFailFieldValue("currentcountryid"));
                }
                ?>
            </select>
        </div>	

</div>

<span class="clearfix ">&nbsp;<br></span>
<button type="button" class="btn btn-success btn-mg" name="detailsmatch" id="detailsmatch"
        onclick="Javascript : copyDetails();">
    Copy Details
</button>
<span class="clearfix visible-lg">&nbsp;</span>
<hr>

<!-- permanent Address 1--------------------- -->
<div class="col-lg-6 col-md-6">
    <label for="permaaddress1" class="small">Permanent Address 1 </label>
    <input type="text" name="permaaddress1"  id="permaaddress1" 
           value ="<?php
           if (!empty($studentdetailsArray['permaaddress1'])) {
               echo $studentdetailsArray['permaaddress1'];
           } else {
               echo submitFailFieldValue("permaaddress1");
           }
           ?>" class="form-control  ">
</div>
<div class="col-lg-6 col-md-6">
    <label for="permaaddress2" class="small">Permanent Address 2</label>
    <input type="text" name="permaaddress2"  id="permaaddress2" 
           value ="<?php
           if (!empty($studentdetailsArray['permaaddress2'])) {
               echo $studentdetailsArray['permaaddress2'];
           } else {
               echo submitFailFieldValue("permaaddress2");
           }
           ?>" class="form-control  ">
</div>
<span class="clearfix">&nbsp;</span>

<div class="col-lg-3 col-md-3">
    <label for="permasuburbid" class="small">Permanent Suburb</label>
    <select name="permasuburbid" id="permasuburbid"  class="form-control  ">
        <?php
        if (!empty($studentdetailsArray['permasuburbid'])) {
            echo PopulateSelect("currentsuburb", $studentdetailsArray['permasuburbid']);
        } else {
            echo PopulateSelect("currentsuburb", submitFailFieldValue("permasuburbid"));
        }
        ?>
    </select>   
</div>

<div class="col-lg-2 col-md-3">
    <label for="permazipcode" class="small">Permanent Pincode</label>
    <input type="text" class="form-control  "  id="permazipcode"  
           value ="<?php
           if (!empty($studentdetailsArray['permazipcode'])) {
               echo $studentdetailsArray['permazipcode'];
           } else {
               echo submitFailFieldValue("permazipcode");
           }
           ?>"  name="permazipcode" />
</div>

<div class="col-lg-2 col-md-2">
    <label for="permacityid" class="small">Permanent City</label>
    <select name="permacityid" id="permacityid"  class="form-control  ">
        <?php
        if (!empty($studentdetailsArray['permacityid'])) {
            echo PopulateSelect("cityname", $studentdetailsArray['permacityid']);
        } else {
            echo PopulateSelect("cityname", submitFailFieldValue("permacityid"));
        }
        ?>
    </select>
</div>

<div class="col-lg-2 col-md-2">
    <label for="permastateid" class="small">Permanent State</label>
    <select name="permastateid" id="permastateid"  class="form-control  ">
        <?php
        if (!empty($studentdetailsArray['permastateid'])) {
            echo PopulateSelect("statename", $studentdetailsArray['permastateid']);
        } else {
            echo PopulateSelect("statename", submitFailFieldValue("permastateid"));
        }
        ?>
    </select>
</div>

<div class="col-lg-2 col-md-2">
    <label class="small" for="permacountryid">Permanent Country</label>
    <select name="permacountryid" id="permacountryid"  class="form-control  "  >
        <?php
        if (!empty($studentdetailsArray['permacountryid'])) {
            echo PopulateSelect("countryname", $studentdetailsArray['permacountryid']);
        } else {
            echo PopulateSelect("countryname", submitFailFieldValue("permacountryid"));
        }
        ?>
    </select>
</div>

<span class="clearfix">&nbsp;</span>
<span class="clearfix">&nbsp;<br></span>
<hr>

<div class="col-lg-3 col-md-3">
    <label for="phone1" class="small">Phone(LandLine)</label>
    <input type="text" class="form-control  "  
           value ="<?php
           if (!empty($studentdetailsArray['phone1'])) {
               echo $studentdetailsArray['phone1'];
           } else {
               echo submitFailFieldValue("phone1");
           }
           ?>" id="phone1" name="phone1"  maxlength="7"  >
    <small> Enter landline number without area code </small>           	
</div>

<div class="col-lg-3 col-md-3">
    <label for="phone2" class="small">Secondary Phone</label>
    <input type="text" class="form-control   " 
           value ="<?php
           if (!empty($studentdetailsArray['phone2'])) {
               echo $studentdetailsArray['phone2'];
           } else {
               echo submitFailFieldValue("phone2");
           }
           ?>" id="phone2" name="phone2"  maxlength="7"/>	
</div>


<div class="col-lg-3 col-md-3" >
    <label for="fax1" class="small">Fax1</label>
    <input type="text" class="form-control   "  
           value ="<?php
           if (!empty($studentdetailsArray['fax1'])) {
               echo $studentdetailsArray['fax1'];
           } else {
               echo submitFailFieldValue("fax1");
           }
           ?>"  name="fax1"/>	
</div>

<div class="col-lg-3 col-md-3">
    <label for="fax2" class="small">Fax2</label>
    <input type="text" class="form-control   "  
           value ="<?php
           if (!empty($studentdetailsArray['fax2'])) {
               echo $studentdetailsArray['fax2'];
           } else {
               echo submitFailFieldValue("fax2");
           }
           ?>"  name="fax2"/>	
</div>

<span class="clearfix">&nbsp;</span>
<span class="clearfix">&nbsp;</span>

<div class="col-lg-3 col-md-3" class="small">
    <label for="mobile" class="small">Mobile*</label>
    <input type="text" class="form-control  "    required="true"  maxlength="10"
           value ="<?php
           if (!empty($studentdetailsArray['mobile'])) {
               echo $studentdetailsArray['mobile'];
           } else {
               echo submitFailFieldValue("mobile");
           }
           ?>" id="mobile" name="mobile"/>	
</div>		

<div class="col-lg-3 col-md-3" class="small">
    <label class="small" for="passportnum">Passport No</label>
    <input type="text" class="form-control  "  
           value ="<?php
           if (!empty($studentdetailsArray['passportnum'])) {
               echo $studentdetailsArray['passportnum'];
           } else {
               echo submitFailFieldValue("passportnum");
           }
           ?>" id="passportnum" name="passportnum"/>	
</div>

<div class="col-lg-3 col-md-3" class="small">
    <label class="small" for="dateofjoining">Date of Joining*</label>
    <input type="date" class="form-control  "  required="true"
           value ="<?php
           if (!empty($studentdetailsArray['dateofjoining'])) {
               echo $studentdetailsArray['dateofjoining'];
           } else {
               echo submitFailFieldValue("dateofjoining");
           }
           ?>" id="dateofjoining" name="dateofjoining" placeholder="YYYY/MM/DD">
</div>


<div class="col-lg-3 col-md-3">
    <label for="conveyancerequired" class="small">Conveyance Required</label>
    <?php 
      $options = "
               <option value=\"0\" selected=\"selected\">Not Required</option>
               <option value=\"1\">Required</option>
               ";
        if(isset($studentdetailsArray['conveyancerequired']) && $studentdetailsArray['conveyancerequired'] == 1){
           $options = "
               <option value=\"0\">Not Required</option>
               <option value=\"1\" selected=\"selected\">Required</option>
               ";
        }
    ?>
    <select name="conveyancerequired" id="conveyancerequired"   class="form-control  ">
        <?php echo $options ?> 
    </select>
</div>
<span class="clearfix">&nbsp;</span>

<div class="col-lg-3 col-md-3" id="pickuppointid">
    <label for="pickuppointid" class="small">Pickup Point Name</label>
    <select name="pickuppointid" id="pickuppointid" style="background-color: #d9f2bd" class="form-control  ">
        <?php
        if (!empty($studentdetailsArray['pickuppointid'])) {
            echo PopulateSelect("pickuppointname", $studentdetailsArray['pickuppointid']);
        } else {
            echo PopulateSelect("pickuppointname", submitFailFieldValue("pickuppointid"));
        }
        ?>
    </select>
</div>

<div class="col-lg-3 col-md-3">
    <label for="previousschool" class="small">Previous school Name</label>
    <input type="text" class="form-control  "   
           value ="<?php
           if (!empty($studentdetailsArray['previousschool'])) {
               echo $studentdetailsArray['previousschool'];
           } else {
               echo submitFailFieldValue("previousschool");
           }
           ?>" id="previousschool" name="previousschool" />	
</div>	
<div class="col-lg-3 col-md-3">
    <label for="previousclass" class="small">Previous Class Attended</label>
    <select name="previousclass"  id="previousclass" class="  form-control">
        <?php
        if (!empty($studentdetailsArray['previousclass'])) {
            echo PopulateSelect("classname", $studentdetailsArray['previousclass']);
        } else {
            echo PopulateSelect("classname", submitFailFieldValue("previousclass"));
        }
        ?>
    </select>
</div>

<div class="col-lg-2 col-md-2">
    <label for="previousresult" class="small">Previous Result</label>
    <select name="previousresult"   id="previousresult" class="  form-control">
        <?php
        if (!empty($studentdetailsArray['previousresult'])) {
            echo PopulateSelect("result", $studentdetailsArray['previousresult']);
        } else {
            echo PopulateSelect("result", submitFailFieldValue("previousresult"));
        }
        ?>
    </select>
</div>
<div class="col-lg-2 col-md-2">
    <label for="percentgrade" class="small">Percentage/Grade</label>
    <input type="text" class="form-control  "  
           value ="<?php
           if (!empty($studentdetailsArray['percentgrade'])) {
               echo $studentdetailsArray['percentgrade'];
           } else {
               echo submitFailFieldValue("percentgrade");
           }
           ?>" id="percentgrade" name="percentgrade">
</div>	

<div class="col-lg-2 col-md-2">
    <label for="housename" class="small">House</label>
    <input type="text"  
           value ="<?php
           if (!empty($studentdetailsArray['housename'])) {
               echo $studentdetailsArray['housename'];
           } else {
               echo submitFailFieldValue("housename");
           }
           ?>" class="form-control  " id="housename" name="housename" >
</div>
<span class="clearfix">&nbsp;</span>
<span class="clearfix">&nbsp;</span>

<div class="col-lg-6 col-md-6" class="small">
    <label for="admissionreferencedby">Reference by</label>
    <textarea name="admissionreferencedby"  id="admissionreferencedby" 
              value ="<?php
              if (!empty($studentdetailsArray['admissionreferencedby'])) {
                  echo $studentdetailsArray['admissionreferencedby'];
              } else {
                  echo submitFailFieldValue("admissionreferencedby");
              }
              ?>" class="form-control" cols="3" rows="3" >
    </textarea>
</div>	

<div class="col-lg-6 col-md-6">
    <label for="otheradditionalinformation " class="small">Others Additional Information</label>
    <textarea name="otheradditionalinformation"   id="otheradditionalinformation" 
              value ="<?php
              if (!empty($studentdetailsArray['otheradditionalinformation'])) {
                  echo $studentdetailsArray['otheradditionalinformation'];
              } else {
                  echo submitFailFieldValue("otheradditionalinformation");
              }
              ?>"  class="form-control" cols="3" rows="3    " >
    </textarea>
</div>  

<span class="clearfix">&nbsp;</span>
<div class="col-lg-6 col-md-6">
    <label for="profilepicture" class="small">Upload Image</label>
    <input type="file"   name="profilepicture" id="profilepicture"
           value ="<?php
           if (!empty($studentdetailsArray['profilepicture'])) {
               echo $studentdetailsArray['profilepicture'];
           } else {
               echo submitFailFieldValue("profilepicture");
           }
           ?>"   class="form-control input-lg"  >
</div>
<span class="clearfix">&nbsp;</span>
<span class="clearfix">&nbsp;</span>

<div class="col-lg-12 col-md-12">
    <h3 class="heading" style="color: Red;">Emergency Contact Details </h3>
    <hr>
    <div class="col-lg-4 col-md-4">
        <label for="emeregencycontactname" class="small">Contact Name*</label>
        <input type="text" class="form-control" name="emeregencycontactname" required="true"
               value="<?php
               if (!empty($studentdetailsArray['emeregencycontactname'])) {
                   echo $studentdetailsArray['emeregencycontactname'];
               } else {
                   echo submitFailFieldValue("emeregencycontactname");
               }
               ?>" >
    </div>
    <div class="col-lg-4 col-md-4">
        <label class="small" for="emeregencyphoneno">Contact No(Mobile)*</label>
        <input type="text" class="form-control"  name="emeregencyphoneno" required="true" maxlength="10"
               value="<?php
               if (!empty($studentdetailsArray['emeregencyphoneno'])) {
                   echo $studentdetailsArray['emeregencyphoneno'];
               } else {
                   echo submitFailFieldValue("emeregencyphoneno");
               }
               ?>">
    </div>
    <div class="col-lg-4 col-md-4">
        <label class="small" for="emeregencycontactaddress">Contact Address*</label>
        <input style="font-size: 15px;" type="text" class="form-control" name="emeregencycontactaddress" required="true" max="10"
               value="<?php
               if (!empty($studentdetailsArray['emeregencycontactaddress'])) {
                   echo $studentdetailsArray['emeregencycontactaddress'];
               } else {
                   echo submitFailFieldValue("emeregencycontactaddress");
               }
               ?>">
    </div>

    <span class="clearfix">&nbsp;</span>
    <span class="clearfix"><p>&nbsp;</p></span> 

    <div class="controls" align="center">
        <input id="clearDiv" type="button"  value="Cancel" class="btn">
        <input type="submit" id="submit1"  name="submit" value="SAVE" class="btn btn-success" >
        <input type="submit" id="submit"  name="submit" value="SAVE & NEXT" class="btn btn-success" >
    </div>
    <span class="clearfix">&nbsp;<br></span>
    <span class="clearfix">&nbsp;<br></span>
</form>
</div> 

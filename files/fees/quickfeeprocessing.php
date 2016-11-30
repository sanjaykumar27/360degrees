<?php
/*
 * 360 - School Empowerment System.
 * Developer: Sanjay Kumar Chaurasia | www.ebizneeds.com.au
 * Page details here: This is quick pay module, after collecting fees
 * page is redirected here to search student again
 * Updates here:
 */

//call the main config file, functions file and header

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>


<script type="text/javascript">
    /* ajax function to search student */
    function showquickstudent() {
        jQuery.ajax({
            url: "<?php echo DIR_FILES ?>/fees/feecollectionprocessing.php",
            data: {
                call: 'createStudentQuick',
                scholarnumber: $("#scholarnumber").val(),
            },
            type: "GET",
            success: function (data) {
                $("#showinputdetails").html(data);
            },
            error: function () {
            }
        });
    }

    /* this part catch input from text field everytime key is pressed */
    $(document).ready(function () {
        /* this searches using scholar number */
        $("#scholar_list").keyup(function () {
            $.ajax({
                type: "POST",
                url: "../student/loadScholarData.php",
                data: 'keyword=' + $(this).val() + '&instid=' +<?= $_SESSION['instsessassocid'] ?>,
                success: function (data) {
                    $("#suggesstion-box").show();

                    $("#suggesstion-box").html(data);
                }
            });
        });
        
        /* this search using student name */
        $("#studentname").keyup(function () {
            $.ajax({
                type: "POST",
                url: "../student/loadScholarData.php",
                data: 'keyword=' + $(this).val() + '&instid=' +<?= $_SESSION['instsessassocid'] ?>,
                success: function (data) {
                    $("#suggesstion-box").show();

                    $("#suggesstion-box").html(data);
                }
            });
        });
    });
    
    /* this get the student id, scholar from the hidden field */

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

    /* this part redirect to the feecollectionprocessing page then
     * student is click 
     */
    function selectItem(val, sibId)
    {
        document.getElementById('scholar_list').value = val;
        document.getElementById('studentid').value = sibId;
        document.getElementById('suggesstion-box').style.display = 'none';
        window.location.replace("<?php echo DIR_FILES ?>/fees/feeCollectionProcessing.php?pop-up=y&studentid=" + sibId)
    }

</script>

<div class="container">
    <div class="row">
        <span class="clearfix"><br></span>
        <div class="panel panel-primary">
            <div class="panel-heading">Search Student</div>
            <div class="panel-body">
                <form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform" id="imform">
                    
                    <!-- input scholar number -->
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                        <label for="exampleInputEmail2">Scholar No</label>
                        <input type="text"  id="scholar_list" class="form-control"  name="scholarnum" 
                               palceholder="Is Any Sibling" 
                               value ="<?php echo submitFailFieldValue("studentid"); ?>"  
                               onblur="document.getElementById('suggesstion-box').style.display = 'none'" 
                               required="true" placeholder="Enter Scholar No"/>
                    </div>
                   
                    <!------ hidden field for studentid  -->
                    <input type="hidden" name="studentid" id="studentid" value="<?php
                    if (isset($_GET['studentid']) && !empty($_GET['studentid'])) {
                        echo $_GET['studentid'];
                    } else {
                        echo '';
                    }
                    ?>">
                   
                    <!-- input field for student name -->
                    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                        <div class="form-group">
                            <label for="exampleInputEmail2">Student Name</label>
                            <input type="text" class="form-control" id="studentname" name="studentname"
                                   onblur="document.getElementById('suggesstion-box').style.display = 'none'"  placeholder="student name">
                        </div>
                    </div>
                </form>
                
                <!-- Div to show student list using list group -->
                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">    
                        <div class="autocomplete" id="suggesstion-box" style="display: none" > </div>
                  </div>
            </div>
        </div>
    </div>
</div>



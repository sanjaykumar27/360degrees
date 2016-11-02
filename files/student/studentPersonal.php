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

if (!isset($_REQUEST['sid']) || $_REQUEST['sid'] <= 0) {
    $class = "class='disabled disabledTab' data-toggle='tab' ";
} else {
    $class = "";
}
?>
<?php
  
// check whether the page is in edit mode, if so call the function

if ((isset($_GET) && $_REQUEST['mode'] == 'edit') || (isset($_GET['siblingid']) && !empty($_GET['siblingid']))) {
    $studentdetailsArray = getStudentDetails(); //echoThis($studentdetailsArray); die;
}
?>
<style>
    .autocomplete
    {
        position:absolute;
        z-index: 1000;
        overflow:scroll; 
        height:150px;
        left:150px;
        width: 250px;
    }

</style>

<script type="text/javascript">
    $(function () {
        $('#pickuppointid').hide();
        $('#conveyancerequired').change(function () {
            if ($('#conveyancerequired').val() == '1') {
                $('#pickuppointid').show();
            } else {
                $('#pickuppointid').hide();
            }
        });
    });

    function copyDetails() {
        $('#permasuburbid').val($('#currentsuburbid').val());
        $("#permasuburbid").attr("disabled", "disabled");

        $('#permaaddress1').val($('#currentaddress1').val());
        $("#permaaddress1").attr("disabled", "disabled");

        $('#permaaddress2').val($('#currentaddress2').val());
        $("#permaaddress2").attr("disabled", "disabled");

        $('#permazipcode').val($('#currentzipcode').val());
        $("#permazipcode").attr("disabled", "disabled");

        $('#permacityid').val($('#currentcityid').val());
        $("#permacityid").attr("disabled", "disabled");

        $('#permastateid').val($('#currentstateid').val());
        $("#permastateid").attr("disabled", "disabled");

        $('#permacountryid').val($('#currentcountryid').val());
        $("#permacountryid").attr("disabled", "disabled");
    }

    jQuery(document).ready(function ($) {
        // function to copy details to other box, example permanent address and temporary address 
        // $('input#detailsmatch').click(function () {
        // if ($(this).is(':checked')) {
        //    $('#permasuburbid').val($('#currentsuburbid').val());
        //     $("#permasuburbid").attr("disabled", "disabled");

        //    $('#permaaddress1').val($('#currentaddress1').val());
        //    $("#permaaddress1").attr("disabled", "disabled");

        //    $('#permaaddress2').val($('#currentaddress2').val());
        //    $("#permaaddress2").attr("disabled", "disabled");

        //    $('#permazipcode').val($('#currentzipcode').val());
        //    $("#permazipcode").attr("disabled", "disabled");

        //    $('#permacityid').val($('#currentcityid').val());
        //    $("#permacityid").attr("disabled", "disabled");

        //    $('#permastateid').val($('#currentstateid').val());
        //    $("#permastateid").attr("disabled", "disabled");

        //    $('#permacountryid').val($('#currentcountryid').val());
        //    $("#permacountryid").attr("disabled", "disabled");

        //} 
        //});

        // for displaying modal for confirming before updating  record...//

        $('#save').click(function () {
            $('#myModal').modal('show');
            $('#steps').val('save');
            return false;

        });

        $('#next').click(function () {

            $('#myModal').modal('show');
            $('#steps').val('next');
            return false;

        });

        $('#submitForm').click(function () {
            $('#imform').submit();
            $('#myModal').modal('hide');
        });
    });

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


    function scholarStat()
    {
        if (document.getElementById('scholar_list').disabled)
        {
            document.getElementById('scholar_list').disabled = false;
            document.getElementById('suggesstion-box').style.display = 'none';
            document.getElementById('siblngsubmit').disabled = false;


        } else
        {
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
        window.location.href = 'studentPersonal.php?mode=complete&siblingid=' + sibId;
    }
</script>
<div class='container'>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Update Record&hellip; !</h4>
                </div>
                <div class="modal-body">
                    <p class="alert-danger">Please make sure details entered are correct, If not check and enter again .</p> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button>
                    <button type="button" class="btn btn-primary" id="submitForm">Save</button>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" role="tablist" data>

<!-- <p align="left" style="font-size: 30px;">Student Information</p>-->
        <li class="active" data-toggle="tab"><a href="studentPersonal.php<?php echo isEditable(); ?>">Personal</a></li>
        <li <?= $class; ?>><a href="studentParent.php<?php echo isEditable(); ?>">Parents</a></li>
        <li <?= $class; ?>><a href="studentMedical.php<?php echo isEditable(); ?>">Medical</a></li>
        <li <?= $class; ?>><a href="studentFees.php<?php echo isEditable(); ?>">Fees Rule</a></li>
        <li <?= $class; ?>><a href="studentDocument.php<?php echo isEditable(); ?>">Documents</a></li>
        <li <?= $class; ?>><a href="studentFeeDetails.php<?php echo isEditable(); ?>">Fee Details</a></li>

    </ul>


    <?php
    if (isset($_REQUEST['mode'])) {
        if ($_REQUEST['mode'] == 'quick') {
            include 'quickStudent.php';
        } elseif ($_REQUEST['mode'] == 'complete') {
            include 'addStudent.php';
        } elseif ($_REQUEST['mode'] == 'edit') {
            include 'addStudent.php';
        }
    }
    ?>
</div>

<?php
require_once VIEW_FOOTER;

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
                    . "AND T1.instsessassocid = $_SESSION[instsessassocid]";
        }

        if (isset($_REQUEST['siblingid']) && !empty($_REQUEST['siblingid'])) {
            $sqlString .=" WHERE T1.studentid='" . $_REQUEST['siblingid'] . "'";
        }
       
        $result = dbSelect($sqlString . " GROUP BY T1.studentid") or die('Query Error');

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
    return $newUrlString;
}
?>



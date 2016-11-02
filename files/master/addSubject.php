<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new subjects
 * Updates here:
 */

//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;

if (isset($_GET['status'])) {
    if (statusUpdate('tblsubjects', $_GET['status'], "subjectid=" . $_GET['sid'])) {
        header('Location:' . $_SERVER['PHP_SELF'] . "?s=49");
    }
}

$response = actionDelete();

$sno = (int) (isset($_GET['page']) ? (($_GET['page'] - 1) * ROW_PER_PAGE) + 1 : 1);
$page = (int) (isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1);

if (isset($_GET['mode']) && $_GET['mode'] == 'edit') {
    $mode = 'edit';
} else {
    $mode = 'add';
}

require_once VIEW_HEADER;


?>

<script type="text/javascript">

    if (<?php if (isset($_GET['edid'])) {
    echo $_GET['edid'];
} else {
    echo 0;
}
?>)
    {
        $(function () {
            $("#searchsubject").hide();
            $('#add,#show').click(function () {

                $('#addsubject').toggle(500);
                $('#searchsubject').toggle(500);
            });
        });
    } else
    {
        $(function () {
            $("#addsubject").hide();
            $('#add,#show').click(function () {

                $('#addsubject').toggle(200);
                $('#searchsubject').toggle(200);
            });
        });

    }

    var rowNum = 0;
    function addRow() {
        rowNum++;
        var row = '<div id="rowNum' + rowNum + '" class="form-inline">'.concat(
                '<p><div class="form-group"> <div class="input-group"> <div class="input-group-addon">Name</div>',
                '<input type="text" class="form-control" placeholder="Subject Name"  id="subjectname[]" name="subjectname[]" required="true">',
                '</div> </div>  <div class="form-group"> <div class="input-group"> <div class="input-group-addon">Code</div>',
                '<input type="text" class="form-control" placeholder="Subject Code"  id="subjectcode[]" name="subjectcode[]">',
                '</div> </div>  <div class="form-group"> <div class="input-group"> <div class="input-group-addon"></div>',
                '<input type="text" class="form-control" placeholder="Short description"  id="comments[]" name="comments[]">',
                '</div> </div> <div class="form-group"><div class="input-group"><div class="input-group-addon">Status</div><div class="btn-group" data-toggle="buttons"><label for="status" class="btn btn-default">',
                '<input type="checkbox" name="status[]" id="status" value="1"> <span class="glyphicon glyphicon-ok"></span></label></div></div></div>',
                '&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
                '<span class="glyphicon glyphicon-minus"></span>  </button>  </div>');

        jQuery('#itemRows').append(row);


    }

    function removeRow(rnum) {
        jQuery('#rowNum' + rnum).remove();
    }

    function displayErrorJS(err) {

        var errMsg = [];
        errMsg[0] = "You are about to delete this particular Subject for class <?php echo $response ?> ?  click Yes to confirm ...!";
        errMsg[1] = "Do you want to delete this particular subject ?  click Yes to confirm ...!";
        var strModal = '<div id="jsErrorAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'.concat(
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"></strong>Attention..!</strong></div>',
                '<div class="modal-body"><div class="alert alert-danger alert-dismissible fade in" role="alert">',
                errMsg[err] + '</div></div>',
                '<div class="modal-footer"><button type="button" class="btn btn-success" data-dismiss="modal" onClick="Javascript: confirmDelete();">Yes</button><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div></div></div></div>');

        $(strModal).appendTo('body');
        $('#jsErrorAlert').modal('toggle');
    }


    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    }

    function confirmDelete() {
        var pageNo = getParameterByName('page');
        var delid = getParameterByName('delid');
        var url = "addSubject.php?c=" + delid + "&page=" + pageNo;
        window.location.replace(url);

    }

// AJAX call for autocomplete 
    $(document).ready(function () {
        $("#subjectname").keyup(function () {
            $.ajax({
                type: "GET",
                url: "readsubjectdata.php",
                data: 'keyword=' + $(this).val(),
                success: function (data) {
                    $("#suggesstion-box").show();
                    $("#suggesstion-box").html(data);
                }
            });
        });
    });
    //To select country name
    function selectCountry(val) {
        $("#mastercollectiontype").val(val);
        $("#suggesstion-box").hide();
    }

</script>

<div class="container" id="searchsubject">
    <div class="span10">
<?php renderMsg(); ?>
        <h2 class='text-primary'> Search Subject's </h2>
        <form method="get" name="imform" id="imform">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <label for="subjectname">Subject</label>
                    <input type="text" name="subjectname" class="form-control" id="subjectname">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <label for="subjectcode">Subject Code</label>
                    <input  type="text" name="subjectcode" class="form-control" id="subjectcode">
                </div>
            </div>

            <span class="clearfix">&nbsp;<br></span>
            <div class="row">
                <div class="col-md-8">
                    <div class="controls" align="center">
                        <button type="button" id="add"  name="search" class="btn btn-success">Add Subject</button>
                        <button value="reset" type="reset" class="btn">Cancel</button>
                        <button type="submit"  name="search" value="Search" class="btn btn-success">Search</button>
                    </div>
                </div>
            </div>

        </form>
        <?php
        if (isset($_GET['search']) && $_GET['search'] == "Search") {
            getcompletesubjectdetails();
        }
        ?>
    </div>
</div>

<input type="hidden" name="edid" value="<?php if (isset($_GET['edid'])) {
            echo cleanVar($_GET['edid']);
        } ?>">
<input type="hidden" name="mode" value="<?php echo $mode ?>">

<div class="container" id="addsubject">
    <form action="<?php echo PROCESS_FORM; ?>" method="post"  >
        <div class="row">
            <div class="span10">
                <h1>Manage Subjects</h1>
                <?php
                renderMsg();
                if (isset($_GET['edid']) && !empty($_GET['edid'])) {
                    $subjectDetails = getSubjectDetails();
                }
                ?>

                <div class="row-fluid">
                    <div class="form-inline" id="itemRows">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Name</div>
                                <input type="text" class="form-control" placeholder="Subject Name"  id="subjectname" name="subjectname[]" required="true" 
                                       value="<?php if (isset($_GET['edid'])) {
                    echo $subjectDetails['subjectname'];
                } else {
                    echo submitFailFieldValue('subjectname');
                }
                ?>">
                            </div>

                        </div>  


                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Code</div>
                                <input type="text" class="form-control" placeholder="Subject Code"  id="subjectcode[]" name="subjectcode[]" 
                                       value="<?php if (isset($subjectDetails['subjectcode'])) {
                    echo $subjectDetails['subjectcode'];
                } else {
                    echo submitFailFieldValue('subjectcode');
                }
                ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"></div>
                                <input type="text" class="form-control" placeholder="Short description"  id="comments[]" name="comments[]" 
                                       value="<?php if (isset($subjectDetails['comments'])) {
                    echo $subjectDetails['comments'];
                } else {
                    echo submitFailFieldValue('comments');
                }
                ?>">
                            </div>
                        </div>     

                        <!--<div class="form-group">
                            <div class="input-group">
                              <div class="input-group-addon"></div>
                             <select name="academicsessionid" id="academicsessionid" class="form-control"> 

                                 <?php
if (isset($subjectDetails['instsessassocid'])) {
                    echo populateSelect("academicsessionid", $subjectDetails['sessionname']);
                } else {
    echo populateSelect("academicsessionid", submitFailFieldValue("academicsessionid"));
}
?>
                            </select>
                            </div>
                          </div>    
                        -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">Status</div>
                                <div class="btn-group" data-toggle="buttons">
                                    <label for="status" class="btn btn-default">
                                        <input type="checkbox" name="status[]" id="status" value="1"> <span class="glyphicon glyphicon-ok"></span>
                                    </label>
                                </div>   
                            </div></div>

                        &nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-success" id="addrow" onclick="addRow(this.form);" title="Click here to add more subjects!"><span class="glyphicon glyphicon-plus"></span>  </button> 

                    </div>
                    <span id="suggesstion-box"></span>
                    <span class="clearfix"><p>&nbsp;</p></span>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="controls" align="center">
                                <button type="button" id="show" class="btn btn-success" >Search Subject</button>
                                <button value="reset" type="reset" class="btn">Cancel</button>
                                <button type="submit" value="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>
                    <span class="clearfix"><p>&nbsp;</p></span>
                </div>   
            </div>
        </div>
    </form>
</div>

<?php
require VIEW_FOOTER;

function getcompletesubjectdetails()
{
    if (!isset($_GET['page'])) {
        $startpage = 0;
    } else {
        $startpage = ($_GET['page'] - 1) * 10;
    }

    $page = (int) (isset($_GET['page']) && !empty($_GET['page']) ? $_GET['page'] : 1);
    $sno = (int) (isset($_GET['page']) ? (($_GET['page'] - 1) * ROW_PER_PAGE) + 1 : 1);
    $strTable = " 
        <span class=\"clearfix\">&nbsp;<br></span>
            <table class=\"table table-bordered table-hover\" >
                <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Subject Title</th>
                        <th>Subject Code</th>
                        <th style=\"text-align: center\">More Option</th>
                    </tr>
                </thead>
       ";
    $sql = "SELECT * FROM `tblsubjects` WHERE `instsessassocid`= 1  AND `deleted`= 0";
    if (isset($_GET['subjectname']) && !empty($_GET['subjectname'])) {
        $sql .= " AND  `subjectname` like '%$_GET[subjectname]%' ";
    }

    if (isset($_GET['subjectcode']) && !empty($_GET['subjectcode'])) {
        $sql .= " AND  `subjectcode` like '%$_GET[subjectcode]%'";
    }
    $totalRows = mysqli_num_rows(dbSelect($sql));

    $finalsql = $sql . " LIMIT $startpage ," . ROW_PER_PAGE;
    
    // $sno  = 1;
    $result = dbSelect($finalsql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['status'] == 1) {
                $statusStyle = 'class="glyphicon glyphicon-ok-circle" style="color:green"';
            } else {
                $statusStyle = 'class="glyphicon glyphicon-ban-circle" style="color:red"';
            }
            $strTable .= "
                   
            <tr>
                <td><a href=\"addSubject.php?edid=$row[subjectid]&mode=edit\">$sno</a></td>
                <td><a href=\"addSubject.php?edid=$row[subjectid]&mode=edit\"> $row[subjectname]</a></td>
                <td><a href=\"addSubject.php?edid=$row[subjectid]&mode=edit\"> $row[subjectcode]</a></td>
                <td width=\"130\">".
                       hoverList($row['subjectid'], $row['status'], '')."
                </td>
            </tr>";
            $sno++;
        }
        $strTable .= "</table>";

        echo $strTable;
        echo "<div class=\"col-lg-6\" style=\"text-align: right; padding: 0px\">" .
        getPagination($totalRows, ROW_PER_PAGE) . "</div>";
    } else {
        echo "<div class=\"container\"><div class=\"alert alert-warning\">
                    <p> No record(s) found for Subjects. Please try to add a subject by clicking ADD SUBJECT button below :</p>
                 </div></div>";
    }
}

function getAllSubjectDetails($action)
{
    $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
    $orderby = " ORDER BY subjectname limit " . $startPage . ',' . ROW_PER_PAGE;

    if (isset($_SESSION['instsessassocid']) && !empty($_SESSION['instsessassocid'])) {
        $sql = "SELECT t1.subjectid,LOWER (t1.subjectname) as subjectname,UPPER(t1.subjectcode) as subjectcode,t1.status, "
                . "LOWER(if(t3.classname!='',t3.classname,'N/A')) AS classname "
                . "FROM tblsubjects  as t1 "
                . " LEFT JOIN tblclssubjassoc as t2 ON t1. subjectid=t2.subjectid "
                . " LEFT JOIN tblclassmaster as t3 ON t2.classid=t3.classid "
                . " LEFT JOIN tblinstsessassoc as t4 ON t1.instsessassocid=t4.instsessassocid "
                . " WHERE t4.instsessassocid=" . $_SESSION['instsessassocid'] . " AND deleted!=1 ";

        $finalSql = $sql . $orderby;
    } else {
        $finalSql = "SELECT subjectid,subjectname,subjectcode,status, classname FROM tblsubjects "
                . "LEFT JOIN tblclssubjassoc ON tblsubjects.subjectid=tblclasssubassoc.subjectid WHERE  deleted!=1 " . $orderby;
    }


    $result = dbSelect($finalSql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $subjectDetails['records'][] = $row;
        }
        $subjectDetails['totalrows'] = mysqli_num_rows(dbSelect($sql));
        return $subjectDetails;
    } else {
        return 0;
    }
}

function getSubjectDetails()
{
    if (isset($_GET['edid'])) {
        $finalSql = "SELECT T1.*,T3.sessionname,T3.academicsessionid FROM tblsubjects as T1 "
                . "LEFT JOIN tblinstsessassoc as T2 ON T1.instsessassocid=T2.instsessassocid "
                . "LEFT JOIN tblacademicsession as T3 ON T2.academicsessionid=T3.academicsessionid "
                . "WHERE subjectid='" . cleanVar($_GET['edid']) . "' AND T1.deleted!=1";

        $result = dbSelect($finalSql);
        $subjectDetails = mysqli_fetch_assoc($result);
        return $subjectDetails;
    }
    return 0;
}

function actionDelete()
{
    $classname = array();
    if (isset($_GET['delid'])) {
        $subjectid = cleanVar($_GET['delid']);
        $sql = "UPDATE `tblsubjects` SET  `deleted`  = 1 WHERE `subjectid` = '$subjectid' ";
        $result = dbUpdate($sql);
    } elseif (isset($_GET['c'])) {
        $subjectid = cleanVar($_GET['c']);
        $sql = "UPDATE `tblsubjects` SET  `deleted`  = 1 WHERE `subjectid` = '$subjectid' ";
        $result = dbUpdate($sql);
    }
}
?>
<!--<td><a href=\"addSubject.php?edid=$row[subjectid]&mode=edit\"><span class=\"glyphicon glyphicon-pencil\" ></span></a></td>
                <td><a href=\"addSubject.php?delid=$row[subjectid]&page=$page\"><span class=\"glyphicon glyphicon-trash\"></span></a></td>
                <td><a href=\"addSubject.php?status=$row[status]&sid=$row[subjectid]&page=$page\" > <span $statusStyle></span></a></a></td>
           
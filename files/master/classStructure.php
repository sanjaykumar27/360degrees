<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au 
 * Page details here: Page to add new subjects
 * Updates here: 
 */
/* assign if selectize needs to be loaded for this page */
$loadSelectize = rtrim(basename($_SERVER['PHP_SELF']), '.php');
/* Selectize load bool ends */

//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>

<style>

</style>

<script type="text/javascript">
    $(function ()
    {
        $('#exams, #subjectid, #classid, #sectionid, #examname').selectize({hideSelected: 'true'});
       
        $('#checkBoxTips').click(function (e) {
            setPopover(this);
            e.stopPropagation();
        });

        $('#popupsave').click(function () {
            $("#CheckBoxPopover").hide();
           
        });
        
        var $pop = $("#CheckBoxPopover");
        
        function setPopover(element) {
            setPopoverPosition(element);
            var title = $(element).attr("title");
            $pop.find("h3.popover-title").text(title);
            $pop.show();
        }
        
        function setPopoverPosition(element) {
            var offset = $(element).offset();
            $pop.css('left', offset.left + 20);
            $pop.css('top', offset.top - 65);
        }
    });

    function displayErrorJS(err) {
        var errMsg = [];
        errMsg[0] = "Enteries for Examination, Exam start & end Dates should match...!";
        errMsg[1] = "Please enter Dates in Valid(dd/mm/yyyy) format..!";


        var strModal = '<div id="jsErrorAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'.concat(
                '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"></strong>Warning..!</strong></div>',
                '<div class="modal-body"><div class="alert alert-danger alert-dismissible fade in" role="alert">',
                errMsg[err] + '</div></div>',
                '<div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div></div></div></div>');

        $(strModal).appendTo('body');
        $('#jsErrorAlert').modal('toggle');
    }

    function removeNum(rowNum) {
        $("#newrow" + rowNum).remove();
    }

    var exam = "<td></td>";

    var rowNum = 0;
    function addExamRow() {
        rowNum++;
        exam = '<td><div class="col-lg-12">\
                        <label>Exam Name<div id="forExamsTour"></div></label>\
                        <select multiple="multiple" id="examname' + rowNum + '" name="exams[]" required="true">\
                            <option value="1">Formative Assessment I</option>\
                            <option value="2">Formative Assessment II</option>\
                            <option value="3">Formative Assessment III</option>\
                            <option value="4">Formative Assessment IV</option>\
                            <option value="5">Annual</option>\
                        </select></div></td>';
        addSubjectRow();
    }


    function addSubjectRow() {
        rowNum++;
        $("#examstructure").append('<tr>' + exam + '<td><div class="col-lg-12"><label>Subjects <div id="forSubTour"></div></label>\n\
                        <select multiple="multiple" id="subjectid' + rowNum + '" name="subjectid[]" required="true" >\n\
<?php echo populateSelect("subjectname", submitFailFieldValue("subjectid")); ?>\n\
                        </select><small> (Type subject name and select.)</small></div>\n\
                                </td><td width="150" style="padding-top: 30px;">\n\
                    <div class="checkbox" id="checkBoxTips' + rowNum + '"><label><input type="checkbox" value="">Is Optional</label>\n\
                    </div></td><td width="200"><div class="col-lg-12">\n\
                        <label>Exam Components <div id="forExamsTour"></div></label>\n\
                        <select multiple="multiple" id="exams' + rowNum + '" name="examcomponent[]" required="true">\n\
                            <option value="1">Written</option><option value="2">Verbal</option>\n\
                            <option value="3">Projects</option><option value="4">Activities</option>\n\
                        </select></div></td><td><div class=" col-lg-12"><label for="examdate">Exam Date</label>\n\
                        <input type="text" id="examdate"  name="examdate[0]" required="true" class="form-control"\n\
                               value="<?php echo submitFailFieldValue("examdate[0]"); ?>">\n\
                        <small>Enter the date in  when the assigned amount is due.  </small>\n\
                        <div class="hidden" id="divduedate"><code>Exam date is required.</code></div>\n\
                    </div></td><td width="150"><div class="col-lg-12">\n\
                        <label>Marks <div id="forSubTour"></div></label>\n\
                        <input type="text" class="form-control" id="marks" name="marks">\n\
                    </div></td></tr>');
        $('#subjectid' + rowNum).selectize({hideSelected: 'true'});
        $('#exams' + rowNum).selectize({hideSelected: 'true'});
        $('#examname' + rowNum).selectize({hideSelected: 'true'});


        $('#checkBoxTips' + rowNum).click(function (e) {
            setPopover(this);
            e.stopPropagation();
        });

        $('#popupsave').click(function () {
             $("#CheckBoxPopover").hide();
        });
        
        var $pop = $("#CheckBoxPopover");
        
        function setPopover(element) {
            setPopoverPosition(element);
            var title = $(element).attr("title");
            $pop.find("h3.popover-title").text(title);
            $pop.show();
        }
        
        function setPopoverPosition(element) {
            var offset = $(element).offset();
            $pop.css('left', offset.left + 20);
            $pop.css('top', offset.top - 65);
        }
        exam = '<td></td>';
    }


</script>


<div class="container" id="mainContainer">
    <!---- This Modal Is basically used For Confirmation of  action performed while Inserting record through form...---->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="alert alert-warning" role="alert">
                        You are about to create the structure of following class (s), please pay special attention.  
                    </div>		
                </div>
                <div class="modal-body">
                    <div class="row-fluid"> <!--- Data is Appended To This Div Through writeIt() Function ----></div>
                    <div class="alert alert-warning" role="alert">
                        Please make sure entries are correct , as once submitted action couldn't be reverted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button>
                    <button type="button" id="submitform" class="btn btn-primary">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!----  Modal Ends Here ...---->



    <form action="<?php echo PROCESS_FORM; ?>" method="post" id="imForm">
        <h2>&nbsp;Create Class Structure</h2><br>
        <table class="table" id="examstructure">
            <tr>
                <?php renderMsg(); ?>
                <td colspan="2">
                    <div class="col-lg-12" >
                        <label for="classid"> Class <div id="forClassTour"></div></label>
                        <select multiple="multiple" id="classid" name="classid[]" required="true" >
                            <?php echo populateSelect("classname", submitFailFieldValue("classid")); ?>
                        </select> 
                        <small>Type & Select only classes with the same structure.</small>
                    </div>
                </td>

                <td colspan="2">
                    <div>
                        <label for="sectionid"> Sections <div id="forSecTour"></div></label> 
                        <select multiple="multiple" id="sectionid" name="sectionid[]" required="true" >
                            <?php echo populateSelect("sectionname", submitFailFieldValue("sectionid")); ?>
                        </select>
                    </div>
                </td>
                <td colspan="2"></td>
            </tr>

            <tr >
                <td>
                    <div class="col-lg-12">
                        <label>Exam Name<div id="forExamsTour"></div></label>
                        <select multiple="multiple" id="examname" name="exams[]" required="true">
                            <option value="1">Formative Assessment I</option>
                            <option value="2">Formative Assessment II</option>
                            <option value="3">Formative Assessment III</option>
                            <option value="4">Formative Assessment IV</option>
                            <option value="5">Annual</option>
                        </select>
                        <small> (Type all exam type for this class & Sections.)</small>
                    </div>
                </td>

                <td>
                    <div class="col-lg-12">
                        <label>Subjects <div id="forSubTour"></div></label>
                        <select multiple="multiple" id="subjectid" name="subjectid[]" required="true" >
                            <?php echo populateSelect("subjectname", submitFailFieldValue("subjectid")); ?>
                        </select>
                        <small> (Type subject name and select.)</small>
                    </div>
                </td>

                <td width="150" style="padding-top: 30px;">
                    <div class="checkbox" id="checkBoxTips" >
                        <label><input type="checkbox" value="">Is Optional</label>
                    </div>
                </td>
                <td width="200">
                    <div class="col-lg-12">
                        <label>Exam Components <div id="forExamsTour"></div></label>
                        <select multiple="multiple" id="exams" name="examcomponent[]" required="true">
                            <option value="1">Written</option>
                            <option value="2">Verbal</option>
                            <option value="3">Projects</option>
                            <option value="4">Activities</option>
                        </select>
                    </div>
                </td>

                <td>
                    <div class=" col-lg-12">
                        <label for="examdate">Exam Date</label>
                        <input type="text" id="examdate"  name="examdate[0]" required="true" class="form-control"
                               value="<?php echo submitFailFieldValue("examdate[0]"); ?>">
                        <small>Enter the date in  when the assigned amount is due.  </small>
                        <div class="hidden" id="divduedate"><code>Exam date is required.</code></div>
                    </div>  
                </td>

                <td width="150">
                    <div class="col-lg-12">
                        <label>Marks <div id="forSubTour"></div></label>
                        <input type="text" class="form-control" id="marks" name="marks">
                    </div> 
                </td>
            </tr>
        </table>
        <div class="col-lg-2 pull-right">
            <label>Add New Rows </label><br>
            <button type="button" class="btn btn-primary btn-round" id="add" 
                    onclick="addSubjectRow();" title="click to add another subject">
                <span class="glyphicon glyphicon-plus"></span>
            </button> 
            <button type="button" class="btn btn-success btn-round" id="add" 
                    onclick="addExamRow();" title="Click to add another exam">
                <span class="glyphicon glyphicon-plus"></span>
            </button> 
        </div>

        <span class="clearfix"><p>&nbsp;</p></span> 

        <div class="controls" align="center"> <div id="forSaveTour"></div>
            <input id="clearDiv" type="button" tabindex="42" value="Cancel" class="btn">
            <!-- Button trigger modal -->
            <input type="submit" id="save"  name="save" value="SAVE" class="btn btn-success">
        </div>
   </form>
    <div id='CheckBoxPopover' class="popover fade right in" style="display: hidden;">
        <div class="arrow"></div>
        <h3 class="popover-title">Enter Due Dates and Amount</h3>
        <div class="popover-content">
            <div class="col-lg-12">
                <label>Due date <div id="forSubTour"></div></label>
                <input type="text" class="form-control" id="marks" name="marks">
                <br></div>
            <div class="col-lg-12">
                <label>Amount <div id="forSubTour"></div></label>
                <input type="text" class="form-control" id="marks" name="marks">
                <br></div>
            <br><button id="popupsave" class="btn btn-success">Save</button>
        </div>
    </div>
</div>


<?php

require VIEW_FOOTER;

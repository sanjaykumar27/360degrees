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


<script type="text/javascript">
 
$(document).ready(function() 
{
    $('#exams, #subjectid, #classid, #sectionid, #examstartdate, #examenddate').selectize({hideSelected: 'true'}); 
    $("#save").click(function() 
    { 
        if (examDatesValidate())
        {
            $('#myModal').modal('show'); // Load Modal
            $('#myModal').on('shown.bs.modal', function () 
            {
                writeIt(); // Calls Function every Time Modal Is Loaded On Page ///
            });
        }
    });    
    $("#submitform").click(function() {$('#imForm').submit();});
		
    // Instance the tour

    $("#tourhelp").click(function(){
	
	var tour = new Tour({
	steps: [
		{
		element: "#forClassTour",
		title: "Select Class(s)",
		content: "Select Classes With Identical Structure for the Academic Session.",
		placement: "right"
		},
		{
		element: "#forSecTour",
		title: "Select Section's",
		content: "Select Section's for the selected Classes",
		placement: "right"
		},
		
		{
		element: "#forSubTour",
		title: "Select Subject's",
		content: "Select Subjects's to be included in selected Classes for the complete Academic Session.",
		placement: "right"
		},
		
		{
		element: "#forExamsTour",
		title: "Select Exam's",
		content: "Select Exams to be conducted for the selected classes in complete Academic Session",
		placement: "left"
		},
		
		{
		element: "#forExamStartTour",
		title: "Select Exam Start Date",
		content: "Select Starting Dates for the Selected Exam",
		placement: "right"
		},
		
		{
		element: "#forExamEndTour",
		title: "Select Exam End Date",
		content: "Select End Dates for the Selected Exam",
		placement: "right"
		}
	]
	}); 
    
	tour.init();
	tour.restart();
 });
});

// This Function Fetches The Data entered by User 		
function Addit() 
{
    var fields = ["classid","sectionid","subjectid","exams","examstartdate","examenddate"];
    var arr = {};
    var val = []; 
    for(var i = 0; i < fields.length; i++)
    { 
        $('#' + fields[i]).closest('div').find('.item').each(function() 
        {
            val.push($(this).html());
            arr[fields[i]] = val;			
        });
        val = [];
    }
	
    return arr;
	
}
// This Function Is Used To Dispaly Data entered Into Tabular Format ...//
function writeIt()
{
	
    var getValueArray = Addit(); 
    var getClasses = getValueArray["classid"]; 
    var getSections = getValueArray["sectionid"]; 
    var getSubjects = getValueArray["subjectid"]; 
    var getExams = getValueArray["exams"]; 
    var getExamStartDate = getValueArray["examstartdate"]; 
    var getExamEndDate = getValueArray["examenddate"]; 
	
    var tblClassSecData = '<table class="table table-bordered table-striped">'.concat(
                            '<tr class="info"> ',
                                '<td> <h5> <strong> Class(s) </strong></h5></br> </td>',
                                '<td> <h5> have following  <strong> Section(s) </strong></h5> </td>',
                                '<td> <h5>with following <strong>Subject(s)</strong></h5>  </td>',
                            '</tr>');
	
    var tblExamData = '<table class="table table-bordered table-striped">'.concat(
                            '<tr class="info"> ',
                                    '<th>Exam(s) for the year </th>',
                                    '<th> Start Date</th>',
                                    '<th> End Date </th>',
                            '</tr>');

			
    $.each(getClasses,function(index, value) 
    {
        tblClassSecData += "<tr><td><b>" + value +" </td></b><td><b>" + getSections +"</b></td><td><b>" + getSubjects +"</b></td><tr>";	
    });
	
	
    for( var i = 0; i< getExams.length ; i++ ) 
    { 
        tblExamData += "<tr><td><b>" + getExams[i] +" </td></b><td><b>" + getExamStartDate[i]+"</b></td><td><b>" + getExamEndDate[i] +"</b></td><tr>";	
    }
	
		
    var tblEnd1 = tblClassSecData + '</table>';
    var tblEnd2 = tblExamData + '</table>';
	
    //  We Need To Empty The Div First Before Appending //
    $(".row-fluid").empty();

    $(".row-fluid").append(tblEnd1);
    $(".row-fluid").append(tblEnd2);
	
}

function displayErrorJS(err){ 
	var errMsg = [];
	errMsg[0] = "Enteries for Examination, Exam start & end Dates should match...!";
	errMsg[1] = "Please enter Dates in Valid(dd/mm/yyyy) format..!";
	
	
	var strModal = '<div id="jsErrorAlert" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'.concat(
	'<div class="modal-dialog"><div class="modal-content"><div class="modal-header"></strong>Warning..!</strong></div>',
	'<div class="modal-body"><div class="alert alert-danger alert-dismissible fade in" role="alert">',
	 errMsg[err]+'</div></div>',
	 '<div class="modal-footer"><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></div></div></div></div>');
	
	$(strModal).appendTo('body');
	$('#jsErrorAlert').modal('toggle');
}

function examDatesValidate(){
	
	var flag = true;
	
	// gets value as array  
	
	var valExams = $('#exams').val();
	var trExams = $.trim(valExams);
	var lenExams = trExams.split(',').length;
	
	// gets value in string 
	
	var valExamStartDate = $('#examstartdate').val(); 
	var valExamEndDate = $.trim($('#examenddate').val()); 
	var trExamStartDate = $.trim(valExamStartDate);
	var lenExamStartDate = trExamStartDate.split(',').length;
	var trExamEndDate = $.trim(valExamEndDate);
	var lenExamEndDate = trExamEndDate.split(',').length;

	//console.log(lenExams + " | " + lenExamStartDate + " | " + lenExamEndDate );
	
	if (lenExams != lenExamStartDate &&  lenExams != lenExamEndDate || lenExamStartDate != lenExamEndDate) {
		flag = false ;
		displayErrorJS(0);
	}
	return flag;
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
    

    <?php renderMsg(); 
            
                $details = classStructureDetails();
                $countrows = $details['totalrows'];
                unset($details['totalrows']);
            
            ?>
    
    <div id="classStructureInfo">
        <table class="table table-bordered table-striped" >  
            <thead>
                <tr>
                    <th align="center">S.No</th>
                    <th align="center">Class</th>
                    <th align="center">Section's</th>
                    <th align="center">Subject's</th>
                    <th align="center">Exams's</th>
                    <th align="center">Update</th>
                    <th align="center">Delete</th>
                    <th align="center">Status</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $i = 1;
            
            $page = '';
            if(isset($_GET['page']) && !empty($_GET['page'])){
                $page = $_GET['page'];
            }
            
            foreach($details as $key => $value){
                if($key['status']==1)
                    $statusStyle = 'class="glyphicon glyphicon-ok-circle" style="color:green"';
                else{
                    $statusStyle = 'class="glyphicon glyphicon-ban-circle" style="color:red" ';
                    }
            ?>
                <tr>
                    <td> <a href="classMaster.php?cid=<?=$value['classid'];?>&mode=edit" class=""><?php echo $i ;?> </a></td>
                    <td> <a href="classMaster.php?cid=<?=$value['classid'];?>&mode=edit" class=""><?php echo $value['classname'];?> </a></td>
                    <td> <a href="classMaster.php?cid=<?=$value['classid'];?>&mode=edit" class=""><?php echo Strtoupper($value['sectionname']);?> </a></td>
                    <td> <a href="classMaster.php?cid=<?=$value['classid'];?>&mode=edit" class=""><?php echo $value['subjectname'];?> </a></td>
                    <td>
                        <table class="table table-bordered condensed" >
                            <thead>
                            <tr>
                                <th align="center">Exams</th>
                                <th align="center">Start Date</th>
                                <th align="center">End Date</th>
                            </tr>
                            </thead>
                            <?php
                            foreach($value['examsDetails'] as $k => $val)
                            {
                                if($val['examid'] == 1){$examName = "Formative Assessment I";}
                                elseif($val['examid'] == 2){$examName = "Formative Assessment II";}
                                elseif($val['examid'] == 3){$examName = "Formative Assessment III";}
                                elseif($val['examid'] == 4){$examName = "Formative Assessment IV";}
                            ?>
                            <tr>
                                <td><?php echo $examName;?></td>
                                <td><?php echo $val['examstartdate'];?></td>
                                <td><?php echo $val['examenddate'];?></td>
                            </tr>
                            <?php 
                            }
                            ?>
                        </table>
                    </td>
                    <td>
                        <a href="classMaster.php?cid=<?=$value['classid'];?>&mode=edit" class="">
                        <span class="glyphicon glyphicon-edit"></span>
                        </a>
                    </td>
                    <td>
                        <a href="classMaster.php?delid=<?=$value['classid']?>&page=<?=$page?>" class="">
                        <span class="glyphicon glyphicon-trash"></span></a>
                        
                    </td>
                    <td>
                        <a href="studentDashboard.php?sid=<?=$key['studentid']?>&status=<?=$key['status']?>&page=<?=$page?>" class="">
                        <span <?=$statusStyle?>></span>
                        </a>    
                    </td>
                </tr>
            <?php	
            $i++;
            }
            ?>
        </table>
        <?php  getPagination($countrows,ROW_PER_PAGE);?>
        
        <a href="<?= DIR_FILES ?>/master/classStructure.php" class="btn btn-success" align="center" 
                id="createStructure" >Create New Structure</a>
    </div>
</div>


<?php
require VIEW_FOOTER;

function classStructureDetails()
{
   $countrows = '' ;
   $details = array(); 
    $sql = "SELECT t1.classid, t1.status ,  
            GROUP_CONCAT( DISTINCT t1.sectionid) as sectionid , 
            GROUP_CONCAT(DISTINCT t2.subjectid) as subjectid , 
            GROUP_CONCAT(DISTINCT t3.examid) as examid 
            
            FROM `tblclsecassoc` As t1 , 
            `tblclssubjassoc`  t2,
            `tblclsexamassoc`  t3 
              
            
            WHERE t1.instsessassocid = $_SESSION[instsessassocid]
            AND  t1.classid = t2.classid
            AND t1.classid = t3.classid 
            AND  t1.status=1  
            AND t3.status=1
            
            GROUP BY t1.classid";
	
   
    $result = dbSelect($sql);
    
    
    if(mysqli_num_rows($result) > 0){
        $countrows = mysqli_num_rows($result);
        while($row = mysqli_fetch_assoc($result)){
            $details[] = $row;  
        }

        foreach($details as $key => $value){	
            $details[$key]['classname']	= getClassName($value['classid']);

            if (strpos($value['sectionid'],',')){
                $sectionid = explode(",", $value['sectionid']);
            }

            else{
                $sectionid[] = $value['sectionid)'];
            }

            $details[$key]['sectionname'] = implode(",",getSectionName($sectionid));

            if (strpos($value['sectionid'],',')){
                $subjectid = explode(",", $value['subjectid']);
            }
            else{
                $subjectid[] = $value['subjectid'];
            }
            
            $details[$key]['subjectname'] = implode(",",getSubjectName($subjectid));

            if (strpos($value['examid'],',')){
                $exams = explode(",", $value['examid']);
            }
            else{
                $exams[] = $value['examid'];
            }

            $details[$key]['examsDetails'] = getExamName($value['classid']);

        }
        $details['totalrows'] = $countrows;
    }
    else {
        $details = 0;
    }
        
    return($details);
}

function getClassName($classid){
    $sql = "SELECT `classname` FROM `tblclassmaster` WHERE `classid` = $classid ";
    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    return $row['classname'];	
}

function getSectionName($sectionid){
    $sectionName = array();
	
    foreach($sectionid as $key => $value){ 
        $sql = "SELECT `sectionname` FROM `tblsection` WHERE `sectionid` = $value ";
        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);

        $sectionName[] = $row['sectionname'];	
    }
    return $sectionName;
}

function getSubjectName($subjectid){
    $subjectName = array();
	
    foreach($subjectid as $key => $value){ 
        $sql = "SELECT `subjectname` FROM `tblsubjects` WHERE `subjectid` = $value ";
        $result = dbSelect($sql);
        $row = mysqli_fetch_assoc($result);

        $subjectName[] = $row['subjectname'];	
    }
    return $subjectName;
}

function getExamName($classid){
    $sql = "SELECT `examid`,`examstartdate`,`examenddate` FROM `tblclsexamassoc` WHERE `classid` = $classid ";
    $result = dbSelect($sql);
    while($row = mysqli_fetch_assoc($result))
    {
        $examDetails[] = $row;
    }
    return $examDetails;
	
}
?>
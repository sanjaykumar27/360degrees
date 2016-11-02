<?php
/*
 * 360 - School Empowerment System.
 * Developer: Sanjay Chaurasia (schaurasia@ebizneeds.com) | www.ebizneeds.com
 * Page details here: Dashboard page for Vehile Mileage, reports etc. 
 * Updates here:
 */
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<script type="text/javascript">
    function removeNum(rowNum) {
        $("#row" + rowNum).remove();
    }

    function addRows(num, number) {
        var i = 0;
        for (i = 0; i < num; i++) {
            number = number+1;
            $('#meter_table').append(
                    '<tr id="row'+number+'"><td><div class="input-group"><input type="date" name="travel_date[]" class="form-control"></div></td><td><div class="input-group"><input type="text" class="form-control" name="start_meter[]" ></div></td><td><div class="input-group"><input type="text" name="end_meter[]"  class="form-control"></div></td><td><div class="input-group"><input type="text" name="remark[]"  class="form-control"></div></td><td><button type="button" class="btn" ><i class="fa fa-trash fa-lg" aria-hidden="true" onclick="removeNum('+number+')"></i></button></td></tr>'
                    );
              }
        }
</script>
<div class="container">
<form action="<?php echo PROCESS_FORM; ?>" enctype="multipart/form-data"  method="post">
    
    <?php renderMsg(); ?>
    
<div id="meter_form">
        <table class="table" id="meter_table" >
             <tr><td colspan="2" class="h3">Enter Vehicle Travel Data</td>
                    <td colspan="3" align="right">
                        <div class="input-group">
                            
                            <select id="busid" class="form-control" name="busid" required="true">
                                <?php
                                  if (isset($routeDetail['busid'])) {
                                      echo populateSelect("busid", $routeDetail['busid']);
                                  } else {
                                      echo populateSelect("busid", submitFailFieldValue("busid"));
                                  }
                                ?>
                        </div></td></tr>
            <tr>
                <th width="200">Date</th>
                <th width="200">Start Meter</th>
                <th width="200">End Meter</th>
                <th width="300">Remarks</th>
                <th>Remove Row</th>
            </tr>
            <?php
            for ($i = 0; $i < 7; $i++) {
                ?><tr id="row<?php echo $i; ?>">
                    <td>
                        <div class="input-group">
                            <input type="date" class="form-control" name="travel_date[]" 
                              value ="<?php echo submitFailFieldValue("travel_date"); ?>"  id="date[<?php echo $i; ?>]" > 
                        </div>
                    </td>
                    <td >
                        <div class="input-group">
                            <input type="text" class="form-control" name="start_meter[]"
                                   value ="<?php echo submitFailFieldValue("start_meter"); ?>" > 
                        </div>
                    </td>
                    <td >
                        <div class="input-group">
                            <input type="text" class="form-control" name="end_meter[]"
                                  value ="<?php echo submitFailFieldValue("end_meter"); ?>" > 
                        </div>
                    </td>  
                    <td> <div class="input-group">
                            <input type="text" class="form-control" name="remark[]" 
                                   value ="<?php echo submitFailFieldValue("remark"); ?>" > 
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn" ><i class="fa fa-trash fa-lg" aria-hidden="true" onclick="removeNum(<?php echo $i; ?>)"></i></button>
                    </td>
                </tr>

            <?php $num = $i; } ?>
        </table>

        <div class='control' align="center">
            <button type='reset' name="cancel"  value="Reset" class="btn">Cancel</button>
            <button name='search'  value="Search" class="btn btn-success">Submit</button>
        </div>
        <p>Add Rows</p>
        <div>
            <button type="button" onclick="addRows(1, <?php echo $num;?>)" class="btn" >1</button>
            <button type="button" onclick="addRows(5, <?php echo $num;?>)" class="btn" >5</button>
            <button type="button" onclick="addRows(10, <?php echo $num;?>)" class="btn" >10</button>
        </div>
 
    <span class="clearfix">&nbsp;<br></span>
</div>
    
    </form>
</div>
<?php include_once VIEW_FOOTER; ?>


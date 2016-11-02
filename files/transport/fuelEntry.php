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
    $(function () {

    });
    function removeNum(rowNum) {
        $("#row" + rowNum).remove();
    }

    function addRowsfuel(num, number) {
        var i = 0;
        for (i = 0; i < num; i++) {
            number = number + 1;
            $('#table_fuel').append(
                    '<tr id="row' + number + '"><td><div class="input-group"><input type="date" name="filled_date[]"  class="form-control"></div></td><td><div class="input-group"><input type="text" class="form-control" name="liters[]"></div></td><td><div class="input-group"><input type="text" name="fuel_amount[]"  class="form-control"></div></td><td><div class="input-group"><input type="text" name="remarks[]"  class="form-control"></div></td><td><button type="button" class="btn" ><i class="fa fa-trash fa-lg" aria-hidden="true" onclick="removeNum(' + number + ')"></i></button></td></tr>'
                    );
        }
    }
</script>
<div class="container">
    <form action="<?php echo PROCESS_FORM; ?>" enctype="multipart/form-data"  method="post">
        <?php renderMsg(); ?>
        
            <table class="table" id="table_fuel">
                <tr><td colspan="2" class="h3">Enter Vehicle Fuel Data</td>
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
                    <th width="200">Liters</th>
                    <th width="200">Amount Per Liter</th>
                    <th width="250">Remarks</th>
                    <th width="150">Remove Row</th>
                </tr>
                <?php
                  for ($i = 0; $i < 7; $i++) {
                      ?><tr id="row<?php echo $i; ?>">
                          <td>
                              <div>
                                  <div class="input-group">
                                      <input type="date" class="form-control" name="filled_date[]" 
                                             value ="<?php echo submitFailFieldValue("filled_date"); ?>" > 
                                  </div>
                              </div>
                          </td>
                          <td ><div>
                                  <div class="input-group">
                                      <input type="text" class="form-control" name="liters[]" 
                                             value ="<?php echo submitFailFieldValue("liters"); ?>" > 
                                  </div>
                              </div>
                          </td>
                          <td ><div>
                                  <div class="input-group">
                                      <input type="text" name="fuel_amount[]"  class="form-control"
                                             value ="<?php echo submitFailFieldValue("fuel_amount"); ?>" > 
                                  </div>
                              </div>
                          </td>  
                          <td><div>
                                  <div class="input-group">
                                      <input type="text" name="remarks[]"  class="form-control"
                                             value ="<?php echo submitFailFieldValue("remarks"); ?>" > 
                                  </div>
                              </div>
                          </td>
                          <td>
                              <button type="button" class="btn" ><i class="fa fa-trash fa-lg" aria-hidden="true" onclick="removeNum(<?php echo $i; ?>)"></i></button>
                          </td>
                      </tr>
                      <?php $num = $i;
                  }
                ?>
            </table>
            <div class='control' align="center">
                <button type='reset' name="cancel"  value="Reset" class="btn">Cancel</button>
                <button name='search'  value="Search" class="btn btn-success">Submit</button>
            </div>


            <p>Add Rows</p>
            <div>
                <button type="button" onclick="addRowsfuel(1, <?php echo $num; ?>)" class="btn" >1</button>
                <button type="button" onclick="addRowsfuel(5, <?php echo $num; ?>)" class="btn" >5</button>
                <button type="button" onclick="addRowsfuel(10, <?php echo $num; ?>)" class="btn" >10</button>
            </div>
        </div>
    </form>
</div>
<?php include_once VIEW_FOOTER; ?>

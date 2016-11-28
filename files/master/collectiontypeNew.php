<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: Page to manage all the collection elements across the application
   * Updates here:
   */
//call the main config file, functions file and header
  require_once "../../config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
?>

<script type="text/javascript">
    var rowNum = 0;
    function addRow(frm) {
        rowNum++;
        var row = '<span class="clearfix"><br></span><br><div id="rowNum' + rowNum + '"><span class="clearfix">&nbsp;</span><div class="col-md-6">'.concat(
                '<input type="text" name="collectionname[]" id="collectionname[]" class="form-control" required="true">',
                '</div><div class="col-md-1"> <button type="button" class="btn btn-danger" id="remove" onclick="removeRow(' + rowNum + ');">',
                '<span class="glyphicon glyphicon-minus"></span></button></div></div>');

        jQuery('#itemRows').append(row);

    }

    function removeRow(rnum) {
        jQuery('#rowNum' + rnum).remove();
    }

    // AJAX call for autocomplete 
    $(document).ready(function () {
        $("#mastercollectiontype").keyup(function () {
            $.ajax({
                type: "GET",
                url: "readmastercollection.php",
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

<?php
  if (isset($_GET['edid'])) {
      if ($_GET['type'] == 'head') {
          $collectionHeadDetail = getAllCollectionTypeDetails();
          //echoThis($collectionHeadDetail);die;
      }
  }
?>
<form action="<?php echo PROCESS_FORM; ?>" method="post" name="imform">
    <div class="container" id="addcollection">
        <div class="span10">
            <?php renderMsg(); ?>
            <h3>Add New Element in Collection Type</h3>
            <div class="row">           
                <div class="col-md-6">
                    <label for="mastercollectiontype">Collection Type Head</label>
                    <input type="text" disabled class="form-control" placeholder="Collection Type Head" 
                           id="mastercollectiontype" 
                           name="mastercollectiontype" 
                           value ="<?php if (isset($_GET['edid'])) {
                                                echo $collectionHeadDetail['records'][0]['mastercollectiontype'];
                                            } else {
                                                echo submitFailFieldValue("mastercollectiontype");
                                            } ?>" required="true" >
                    <span id="suggesstion-box"></span>
                </div>
            </div>       

            <span class="clearfix">&nbsp;<br></span>

            <div class="row" id="itemRows">
                <div class="col-md-6">
                    <label for="collectionname">Collection Item</label>
                    <input type="text" name="collectionname[]" id="collectionname[]" placeholder="Collection Item(s)" 
                           class="form-control" required="true" 
                           value="<?php echo submitFailFieldValue("collectionname"); ?>" >
                </div>

                <div class="col-md-2">
                    <label for=""> Add</label><br>
                    <button type="button" class="btn btn-success" id="add" onclick="addRow(this.form);">
                        <span class="glyphicon glyphicon-plus"></span></button> 
                </div>
            </div>
            <span class="clearfix">&nbsp;<br></span>

            <div class="row">
                <div class="col-md-6">
                    <label for="description">Collection Description</label>
                    <input type="text" name="description" id="description" class="form-control" 
                           value ="<?php echo submitFailFieldValue("description"); ?>" />
                    <div class="small">Brief description of the collection.</div>                       
                </div>
            </div>

            <span class="clearfix"><br></span>
            <div class="row">
                <div class="col-md-6">
                    <label for="status">Active
                        <input type="checkbox" name="status" id="status" value="1" checked required> </label>
                </div>
            </div>

            <span class="clearfix"><p>&nbsp;</p></span>

            <div class="controls" align="center">
                <input id="clearDiv" type="button"  value="Cancel" class="btn">
                <!-- Button trigger modal -->
                <input type="submit" id="save"  name="save" value="SAVE" class="btn btn-success">
            </div> 

        </div> <!--span class closed-->   
    </div> <!--container closed-->
</form>   

<?php
  require VIEW_FOOTER;

  function getAllCollectionTypeDetails() {
      $startPage = (int) (!isset($_REQUEST['page']) ? 0 : ($_REQUEST['page'] - 1) * ROW_PER_PAGE);
      $orderby = " ORDER BY mastercollectiontypeid limit " . $startPage . ',' . ROW_PER_PAGE;
      if (isset($_SESSION['instsessassocid']) && !empty($_SESSION['instsessassocid'])) {
          if (isset($_GET['edid'])) {
              $sql = "SELECT mastercollectiontypeid, LOWER(mastercollectiontype) as mastercollectiontype,status 
                FROM tblmastercollectiontype WHERE tblmastercollectiontype.deleted!=1"
                      . " AND mastercollectiontypeid=" . $_GET['edid'];
          } else {
              $sql = "SELECT mastercollectiontypeid,  LOWER(mastercollectiontype) as mastercollectiontype,status 
                FROM tblmastercollectiontype  WHERE tblmastercollectiontype.deleted!=1 ";
          }
          $finalSql = $sql . $orderby;
      } else {
          $finalSql = "SELECT mastercollectiontypeid,  LOWER(mastercollectiontype) as mastercollectiontype,status 
                FROM tblmastercollectiontype WHERE deleted!=1 " . $orderby;
      }

      $result = dbSelect($finalSql);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $collectionDetails['records'][] = $row;
          }

          $collectionDetails['totalrows'] = mysqli_num_rows(dbSelect($sql));

          return $collectionDetails;
      } else {
          return 0;
      }
  }

  
?>
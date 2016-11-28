<?php
  /*
   * 360 - School Empowerment System.
   * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
   * Page details here: Master for fees head and related processing
   * Updates here:
   */

//call the main config file, functions file and header

  require_once "./config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
?>
<div class="col-md-4">
    <input id="txt" class="form-control" value="sanjay" disabled="true"/>
</div>
<input id="txt[2]" value="btn2" />
<input type="button" value="button" id="btn">
<br>
<script>
    $(function () {
        displayHideDiv('showcollection,addcollection',null);
        $('#btn').dblclick(function () {
            $("#txt").prop('disabled', false);
        });
        $("#txt").autocomplete({
            source: 'test.php'
        });
    });
</script>
<br>
<?php 
  $num = 0;
  echoThis(roundOff($num));
    
    
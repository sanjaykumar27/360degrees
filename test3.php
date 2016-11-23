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

<input id="txt[1]" value="btn1" />
<input id="txt[2]" value="btn2" />
<input type="button" value="button" id="btn">

<script type="">
    $(function () {
        $('#btn').click(function () {
            var id = 2;
            var temp = "#btn["+id+"]";
            alert(temp);
        });
    });
</script>
<?php
/* AIzaSyC1X0X9qFb_eXU224JsrxdgLJXczc0NvmY
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Page to add new subjects
 * Updates here:
 */

//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;


global $totalrows;
$totalrows = 0;
$sno = 1;

if (isset($_GET['mode']) && $_GET['mode'] == 'delete') {
    if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {
        $delid = cleanVar($_GET['delid']);
        $sqlDelete = " UPDATE tblbusroute SET deleted=1 WHERE busrouteid=$delid";
        $result = dbUpdate($sqlDelete);
        if ($result) {
            echo "<script>window.location='addRoute.php'</script>";
        }
    }
}

if (isset($_GET['pid']) && isset($_GET['status'])) {
    if (is_numeric($_GET['pid']) && is_numeric($_GET['status'])) {
        $pid = cleanVar($_GET['pid']);
        $status = cleanVar($_GET['status']);

        $resultStatus = statusUpdate('tblbusroute', $status, 'busrouteid=' . $pid);
        if ($resultStatus) {
            echo "<script>window.location='addRoute.php'</script>";
        }
    }
}
$routeDetails = getRouteList();

$mode = (isset($_GET['mode']) && !empty($_GET['mode'])) ? 'edit' : 'add';
?>

<script lang="javascript">
    $(document).ready(function ()
    {
        $(function ()
        {
<?php if (isset($_GET['edid']) && !(isset($_GET['mode']) === 'edit')) {
    ?>
                $("#addroute").show();
                $("#showroute").hide();
                $('#addroutebtn, #showroutebtn').click(function ()
                {
                    $('#addroute').toggle(200);
                    $('#showroute').toggle(200);
                });
    <?php
} else {
    ?>
                $("#showroute").show();
                $("#addroute").hide();

                $('#addroutebtn, #showroutebtn').click(function ()
                {
                    $('#addroute').toggle(200);
                    $('#showroute').toggle(200);
                });

    <?php }
?>

        });
        $('#pickuppointname').multiselect();
    });
</script>

<div class="container">
<?php renderMsg(); ?>
    <div id="showroute">
    <?php if ($routeDetails) {
        ?>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th >SNo.</th>
                        <th> Route Name</th>
                        <th> Vechile No</th>
                        <th> Driver Name </th>
                        <th> Driver Contact </th>
                        <th>Pick-up Points </th>
                        <th> Update </th>
                        <th> Delete </th>
                        <th> Status </th>
                    </tr>
                </thead>
                <tbody>
    <?php
    foreach ($routeDetails['records'] as $key => $value) {
        if ($value['status'] == 1) {
            $statusStyle = 'class="fa fa-toggle-off fa-2x" style="color:green;"';
        }
        //$statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
        else {
            // $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
            $statusStyle = 'class="fa fa-toggle-off fa-2x" style="color:red;"';
        }
        ?>
                        <tr>

                            <td> <?php echo $sno ?> </td>
                            <td> <a href="addRoute.php?edid=<?php echo $value['busrouteid']; ?>&mode=edit"><?php echo ucwords($value['routename']) ?> </td>
                            <td> <a href="addVehicle.php?vid=<?php echo $value['vehicleid']; ?>&mode=edit"><?php echo ucwords($value['vehicletitle']) ?> (<?php echo strtoupper($value['platenumber']); ?>) </td>
                            <td> <a href="addDriver.php?edid=<?php echo $value['driverid']; ?>&mode=edit"><?php echo ucwords($value['drivername']) ?> </td>
                            <td> <?php echo $value['mobile'] ?> </td>
                            <td> <a href="#" title="<?php echo $value['pickup'] ?>"> Show Pick </a> </td>

                            <td> <a href="addRoute.php?edid=<?= $value['busrouteid']; ?>&mode=edit" class=""><span class="glyphicon glyphicon-edit"></span></a></td>
                            <td> <a href="addRoute.php?delid=<?php echo $value['busrouteid']; ?>&mode=delete"><span class="glyphicon glyphicon-trash"></span></a></td>
                            <td> <a href="addRoute.php?pid=<?= $value['busrouteid'] ?>&status=<?= $value['status'] ?>" class=""><i <?= $statusStyle ?>></i></a> </td>
                        </tr>
                        <?php $sno++;
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } else {
            ?>
            <div class="alert alert-danger"><p>No record(s) found yet. Please try again later or add vehicle route first.</p></div>
            <?php }
        ?>
        <div class="col-lg-12">
            <div class="col-lg-6" style="padding-left: 0px; text-align: left"> <button class="btn btn-success" type="button" name="addroutebtn" id="addroutebtn">Add Route</button></div>
            <div class="col-lg-6" style="padding-right: 0px; text-align: right"> <?php getPagination($totalrows, ROW_PER_PAGE); ?></div>
        </div>
    </div>

    <div id="addroute">

        <h1>Add Vehicle Routes</h1>
        <form method="post" enctype="multipart/form-data" action="<?php echo PROCESS_FORM; ?>">
            <?php
            if ($mode == 'edit') {
                $edid = cleanVar($_GET['edid']);
                $routeDetail = getRouteDetails();
                ?>
                <input type="hidden" name="edid" id="edid" value="<?php echo $edid ?>">

                <?php }
            ?>
            <input type="hidden" name="mode" id="mode" value="<?php echo $mode ?>">
            <div class="col-lg-4" >
                <label for="routename">Route Name</label>
                <input type="text" id="routename" class="form-control" name="routename" required="true"
                       value ="<?php
                       if (isset($routeDetail['routename'])) {
                           echo $routeDetail['routename'];
                       } else {
                           echo submitFailFieldValue("routename");
                       }
                       ?>">
            </div>

            <div class="col-lg-4">
                <label for="busid">Vehicle Name</label>
                <select id="busid" class="form-control" name="busid">
                    <?php
                    if (isset($routeDetail['busid'])) {
                        echo populateSelect("busid", $routeDetail['busid']);
                    } else {
                        echo populateSelect("busid", submitFailFieldValue("busid"));
                    }
                    ?>
                </select>
            </div>
            <div class="col-lg-4">
                <label for="drivername">Driver Name</label>
                <select id="drivername" class="form-control" name="drivername">
<?php
if (isset($routeDetail['driverid'])) {
    echo populateSelect("drivername", $routeDetail['driverid']);
} else {
    echo populateSelect("drivername", submitFailFieldValue("driverid"));
}
?>
                </select>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="col-lg-4">
                <label for="startpoint">Start Point </label>
                <select id="startpoint" class="form-control" name="startpoint">
<?php
if (isset($routeDetail['startpointid'])) {
    echo populateSelect("instituteid", $routeDetail['startpointid']);
} else {
    echo populateSelect("instituteid", submitFailFieldValue('startpoint'));
}
?>
                </select>
            </div>

            <div class="col-lg-4"> 
                <label for="pickuppointname"> Pick-up Points</label><br>
                <select id="pickuppointname" class="form-control"  name="pickuppointname[]"  multiple="multiple">
<?php
if (isset($routeDetail['pickuppointid'])) {
    $pickupPoints = explode(",", $routeDetail['pickuppointid']);
    echo populateSelect("pickuppointname", $pickupPoints);
} else {
    echo populateSelect("pickuppointname", submitFailFieldValue('pickuppointname'));
}
?>
                </select>
            </div>
            <div class="col-lg-4">
                <label for="endpoint">Destination Point</label>
                <select id="endpoint" class="form-control" name="endpoint">
<?php
if (isset($routeDetail['endpointid'])) {
    echo populateSelect("instituteid", $routeDetail['startpointid']);
} else {
    echo populateSelect("instituteid", submitFailFieldValue('endpointid'));
}
?>
                </select>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <center>
                <div class="col-lg-12">
                    <input type="button" name="showroutebtn" id="showroutebtn" class="btn btn-success" value="Show Route">
                    <input type="reset" name="reset" id="reset" class="btn btn-default" value="Cancel">
                    <input type="button" name="showmap" id="showmap" class="btn btn-primary" value="Show Map">
                    <input type="submit" name="save" id="save" class="btn btn-success" value="Save Route">
                </div>
            </center>
        </form>
    </div>

</div>

<?php

function getRouteList() {

    $instsessasocid = cleanVar($_SESSION['instsessassocid']);
    $sqlRoute = " SELECT t1.busrouteid, LOWER(t1.routename) as routename, t3.vehicleid, LOWER(t3.vehicletitle) vehicletitle,
                 IFNULL(t3.platenumber,'N/A') as platenumber,t2.driverid,
                 LOWER(IFNULL( CONCAT ( t2.driverfirstname,' ',t2.drivermiddlename,' ',t2.driverlastname),'N/A' )) as drivername,
                  t2.mobile, t1.status 
                  FROM tblbusroute as t1 LEFT JOIN tbldrivers as t2 ON  t1.driverid=t2.driverid 
                  LEFT JOIN tblvehicle as t3 ON t1.busid=t3.vehicleid
                  WHERE t1.instsessassocid=$instsessasocid AND 
                  t1.deleted=0 ORDER BY routename";

    $resRoute = dbSelect($sqlRoute);
    if (mysqli_num_rows($resRoute) > 0) {
        while ($row = mysqli_fetch_assoc($resRoute)) {
            $sqlPickUp = " SELECT UPPER(t3.pickuppointname) as pickuppointname FROM tblrouteassoc as t1 , tblbusroute as t2 , tblpickuppoint as t3 
                           WHERE t1.routeid=t2.busrouteid AND t1.pickuppointid=t3.pickuppointid AND t2.busrouteid=$row[busrouteid] ORDER BY t3.pickuppointname ";

            $resPickUp = dbSelect($sqlPickUp) ;
            if (mysqli_num_rows($resPickUp) > 0) {
                while ($rowPickUp = mysqli_fetch_assoc($resPickUp)) {
                    $rowpicknew[] = $rowPickUp['pickuppointname'];
                }

                $row['pickup'] = implode(' / ', $rowpicknew);
                $routeDetails ['records'][] = $row;
                // $routeDetails ['records']['pickuppoints']=$pickUpDetails;
            }
        }
        $totalrows = mysqli_num_rows($resRoute);
        return $routeDetails;
    } else {
        return 0;
    }
}

function getRouteDetails() {
    if (isset($_GET['mode']) && !empty($_GET['mode']) && $_GET['mode'] == 'edit') {
        $edid = cleanVar($_GET['edid']);

        if (!empty($edid) && is_numeric($edid)) {
            $sqlRouteDetails = " SELECT t1.routename,t1.busid, t1.driverid, t1.startpointid ,
                t1.endpointid, GROUP_CONCAT(t2.pickuppointid) AS pickuppointid
                
                FROM `tblbusroute` AS t1,
                `tblrouteassoc` AS t2

                WHERE t1.busrouteid = '$edid'
                AND t1.busrouteid = t2.routeid
                AND t1. deleted = 0";

            $resRouteDetails = dbSelect($sqlRouteDetails);
            if (mysqli_num_rows($resRouteDetails) > 0) {
                $row = mysqli_fetch_assoc($resRouteDetails);
                return $row;
            }
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

require VIEW_FOOTER;

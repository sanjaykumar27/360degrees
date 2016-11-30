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
require_once VIEW_HEADER;


    if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {
        $result=dbUpdate("UPDATE tblvehicle SET deleted=1 WHERE vehicleid=".cleanVar($_GET['delid']));
    }

if (isset($_GET['vid']) && is_numeric($_GET['vid'])) {
    if (isset($_GET['status'])) {
        $result=  statusUpdate('tblvehicle', cleanVar($_GET['status']), 'vehicleid='.cleanVar($_GET['vid']));
        if ($result) {
            echo "<script>window.location='addVehicle.php?s=34'</script>";
        }
    }
}


$vehicleArray= showAllVehicle();
if ($vehicleArray['totalrows']=='') {
    $totalRows=0;
}
$sno=1;

?>

<script>
   
   $(function()
    {
        <?php if (isset($_GET['vid']) && !(isset($_GET['mode']) === 'edit')) {
    ?>
        $( "#addvehicle" ).show();
        $("#showvehicle").hide();
        $('#add, #show').click(function()
        {
            $('#addvehicle').toggle(200);
            $('#showvehicle').toggle(200); 
        });
        <?php 
} else {
    ?>        
        $("#showvehicle").show();
        $( "#addvehicle" ).hide();
            
        $('#add, #show').click(function()
        {
                $('#addvehicle').toggle(200);
                $('#showvehicle').toggle(200); 
        });
            
        <?php 
} ?>
                  
    });
           // $('input[type=date]').datepicker( {format: "dd-mm-yyyy"});
      
</script>   
<div class="container" >
    <?php renderMsg(); ?>
    <div class="modal fade" id="attachdriver" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ATTACH DRIVER</h4>
                </div>
                <div class="modal-body"> 
                    <p>Please Select a driver from the below given list and press attach :</p>
                    <form name="driverfrm" id="driverfrm" method="POST" >
                        <?php 
                            $driverArray=getDriverList();
                            if ($driverArray!=0) {
                                foreach ($driverArray['records'] as $key=>$value) {
                                    echo '<div class="col-lg-4 col-md-4"><input type="checkbox"/>'.$value['drivername'] . '</div>';
                                }
                            } else {
                                echo "<div class='alert alert-warning'> There is no driver details exists </div>";
                            }
                        ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div id="showvehicle" name="showvehicle">
         
    <?php if (!empty($vehicleArray) && $vehicleArray!=0) {
                            ?>
    <table class="table table-bordered table-hover">
        <thead >
            <tr >
                <th >SNo.</th>
                <th> Vehicle Name.</th>
                <th> Vehicle No </th>
                <th> Vehicle Type </th>
                <th> Fuel Type </th>
                
                <th> Update </th>
                <th> Delete </th>
                <th> Status </th>
            </tr>
                
        </thead>
        <tbody>
            <?php 
            foreach ($vehicleArray['records'] as $key =>$value) {
                if ($value['status']==1) {
                    $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
                } else {
                    $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
                } ?>
            <tr >
                <td> <a href="addVehicle.php?vid=<?=$value['vehicleid']; ?>&mode=edit" class=""><?php echo $sno ?> </a></td>
                <td> <a href="addVehicle.php?vid=<?=$value['vehicleid']; ?>&mode=edit" class=""><?php echo ucwords($value['vehicletitle'])?> </a></td>
                <td> <a href="addVehicle.php?vid=<?=$value['vehicleid']; ?>&mode=edit" class=""><?php echo $value['platenumber']?></a></td>
                <td> <a href="addVehicle.php?vid=<?=$value['vehicleid']; ?>&mode=edit" class=""><?php echo $value['vehicletype']?></a></td>
                <td> <a href="addVehicle.php?vid=<?=$value['vehicleid']; ?>&mode=edit" class=""><?php echo $value['fueltype']?> </a></td>
                
                <td><a href="addVehicle.php?vid=<?=$value['vehicleid']; ?>&mode=edit" class=""><span class="glyphicon glyphicon-edit"></span></a></td>
                <td><a href="addVehicle.php?delid=<?=$value['vehicleid']?>" class=""><span class="glyphicon glyphicon-trash"></span></a></td>
                <td><a href="addVehicle.php?vid=<?=$value['vehicleid']?>&status=<?=$value['status']?>" class=""><span <?=$statusStyle?>></span></a> </td>
            </tr>
            <?php $sno++;
            } ?>
        </tbody>
        
    </table>    
    <?php 
                        } else {
                            ?>
    <div class="alert alert-danger">
        <p> No record(s) found. </p>
    </div>
    <?php 
                        } ?>
        <div class="col-lg-6" style="text-align: left; padding: 0px">
        <a href="#"><button type="submit" id="add" class="btn btn-success" href="#">Add Vehicle</button></a>
        <!--<button type="submit" name="search" id= "search" class="btn btn-primary" >Search</button>-->
        </div>
        <div class="col-sm-6" style="text-align: right; padding: 0px">
          <?php //getPagination($totalRows, ROW_PER_PAGE);?>
        </div>
    </div>
    
    <?php 
    $mode= (isset($_GET['mode']) && $_GET['mode'] == 'edit') ? 'edit' :'add' ;
    
    if (isset($_GET['mode']) && !empty($_GET['vid']) && $_GET['mode']=='edit') {
        $vehicleid = cleanVar($_GET['vid']);
        $vehicleDetail= vehicleDetail();
    }

?>    <div id="addvehicle" name="addvehicle">

        <form action="<?php echo PROCESS_FORM; ?>" method="post" enctype= "multipart/form-data"  name="imForm">
            <input type="hidden"  name="mode"  id="mode" value="<?php echo $mode ?>" >
            <?php if ($mode=='edit') {
    ?>
            <input type="hidden"  name="vehicleid"  id="vehicleid" value="<?php echo $vehicleid ?>" >
                <?php 
}  ?>
            <h1 >Add Vehicle</h1>
                 <?php renderMsg(); ?>
            <div class="col-lg-4 col-md-4 col-md-4">
                <label for="vechile_name">Vehicle Name*</label>
                <input type="text" name="vechile_name" id="vehicle_name" tabindex="1" class="form-control" required="true"
                       value="<?php if (isset($vehicleDetail['vehicletitle'])) {
    echo $vehicleDetail['vehicletitle'];
} else {
    echo submitFailFieldValue("vechile_name");
} ?>">
            </div>
            <div class="col-lg-2 col-md-2">
                <label for="type" >Vehicle Type*</label>
                <select name="type" id="type" class="form-control"  tabindex="2" required="true">
                    <option value="1">Bus </option>
                    <option value="2">Mini Bus </option>
                    <option value="3">Three Wheeler </optio>
                </select>
            </div>     
            <div class="col-lg-2 col-md-2">
                <label for="Fuel Type">Fuel Type* </label>
                <select name="fueltype" id="fueltype" class="form-control"  tabindex="3" required="true" >
                    <option value="1">Petrol</option>
                    <option value="2">Diesel </option>
                    <option value="3">CNG/LPG </option>
                </select>
            </div>     
            
            <div class="col-lg-2 col-md-2">
                <label for="modelno"> Model No </label>
                <input type="text" name="modelno" id="modelno" class="form-control"  tabindex="4" 
                       value="<?php if (isset($vehicleDetail['modelno'])) {
    echo $vehicleDetail['modelno'];
} else {
    echo submitFailFieldValue("modelno");
} ?>">
            </div>
            <div class="col-lg-2 col-md-2"> 
                <label for="makeyear" > Year of make* </label>
                <input type="text" name="makeyear" id="makeyear" class="form-control" tabindex="5" required="rue"
                       value="<?php if (isset($vehicleDetail['makeyear'])) {
    echo $vehicleDetail['makeyear'];
} else {
    echo submitFailFieldValue("makeyear");
} ?>">
                
            </div>
    
            <span class="clearfix"><p>&nbsp;</p></span>
              
            <div class="col-lg-4 col-md-4">
                <label for="vehicleno"> Vehicle Number* </label>
                <input type="text" name="vehicleno"  id="vehicleno"  class="form-control" tabindex="6" required="true" 
                       value="<?php if (isset($vehicleDetail['platenumber'])) {
    echo $vehicleDetail['platenumber'];
} else {
    echo submitFailFieldValue("vehicleno");
} ?>">
            </div>
            
              <div class="col-lg-4 col-md-4"> 
                  <label for="chasisno"> Chasis No* </label>
                  <input type="text" name="chasisno" id="chasisno" class="form-control" tabindex="7"  required="true"
                         value="<?php  if (isset($vehicleDetail['chasisnumber'])) {
    echo $vehicleDetail['chasisnumber'];
} else {
    echo submitFailFieldValue("chasisno");
} ?>">
              </div>
              <div class="col-lg-4 col-md-4">
                  <label for="engineno">Engine No* </label>
                  <input type="text" name="engineno" id="engineno" class="form-control" tabindex="8" required="true"
                         value="<?php if (isset($vehicleDetail['enginenumber'])) {
    echo $vehicleDetail['enginenumber'];
} else {
    echo submitFailFieldValue("engineno");
} ?>">
              </div>
            
            <span class="clearfix"><p>&nbsp;</p></span>
            
            <div class="col-lg-4 col-md-4">
                <label for="registrationno">Registration No*</label>
                <input type="text" name="registrationno" id="registrationno" class="form-control" tabindex="9"  required="true"
                       value="<?php if (isset($vehicleDetail['registrationno'])) {
    echo $vehicleDetail['registrationno'];
} else {
    echo submitFailFieldValue("registrationno");
} ?>">
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="regvalidfrom">Registration Valid From*</label>
                <div class="input-group">
                    <input type="date" name="regvalidfrom" class="form-control" tabindex="10" id="regvalidfrom" required="true"
                       value="<?php  if (isset($vehicleDetail['rcvalidfrom'])) {
    echo $vehicleDetail['rcvalidfrom'];
} else {
    echo submitFailFieldValue("regvalidfrom");
} ?>">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>
                </div>
            </div>
             <div class="col-lg-4 col-md-4">
                <label for="regvalidto">Registration Valid To*</label>
                <div class="input-group">
                    <input type="date" name="regvalidto" class="form-control" tabindex="10" id="regvalidfrom" required="true"
                       value="<?php if (isset($vehicleDetail['rcvalidto'])) {
    echo $vehicleDetail['rcvalidto'];
} else {
    echo submitFailFieldValue("regvalidto");
} ?>">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>
                </div>
            </div>
            
            <span class="clearfix"><p>&nbsp;</p></span>
             
            <div class="col-lg-4 col-md-4">
                <label for="insurancepolicyno">Insurance Policy No*</label>
                <div class="input-group">
                    <input type="text" name="insurancepolicyno"  class="form-control" tabindex="11" id="insurancepolicyno" required="true"
                       value="<?php  if (isset($vehicleDetail['insurancepolicyno'])) {
    echo $vehicleDetail['insurancepolicyno'];
} else {
    echo submitFailFieldValue("insurancepolicyno");
} ?>">


                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="insurancefromdate">Insurance From Date*</label>
                <div class="input-group">
                    <input type="date" name="insurancefromdate" id="insurancefromdate" class="form-control" tabindex="12"  required="true"
                       value="<?php if (isset($vehicleDetail['insurancevalidfrom'])) {
    echo $vehicleDetail['insurancevalidfrom'];
} else {
    echo submitFailFieldValue("insurancefromdate");
} ?>">                
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>

                </div>
            </div>
                
            
            <div class="col-lg-4 col-md-4">
                <label for="insurancetodate">Insurance To Date*</label>
                <div class="input-group">
                    <input type="date" name="insurancetodate" id="insurancetodate" class="form-control" tabindex="13"  required="true"
                       value="<?php if (isset($vehicleDetail['insurancevalidto'])) {
    echo $vehicleDetail['insurancevalidto'];
} else {
    echo submitFailFieldValue("insurancetodate");
} ?>">                
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>

                </div>
            </div>
            
            <span class="clearfix"><p>&nbsp;</p></span>
            
            <div class="col-lg-4 col-md-4">
                <label for="roadtaxdate">Road Tax Paid Upto*</label>
                <div class="input-group">
                    <input type="date" name="roadtaxdate" id="roadtaxdate" class="form-control" tabindex="14" required="true"
                           value="<?php if (isset($vehicleDetail['roadtaxpaidupto'])) {
    echo $vehicleDetail['roadtaxpaidupto'];
} else {
    echo submitFailFieldValue("roadtaxdate");
} ?>" > 
                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>
                </div>
                               
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="pcrvaliddate">P.C.R Valid Upto*</label>
                <div class="input-group">
                    <input type="date" name="pcrvaliddate" class="form-control" tabindex="15" required="true"
                       value="<?php if (isset($vehicleDetail['pcrvalidupto'])) {
    echo $vehicleDetail['pcrvalidupto'];
} else {
    echo submitFailFieldValue("pcrvaliddate");
} ?>">  
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>

                </div>
            </div>
            
            
            <div class="col-lg-4 col-md-4">
                <label for="Status">Seating Capacity*</label>
                <input type="text" name="seatingcapacity" id="seatingcapacity" tabindex="16" class="form-control" required="true"
                     value="<?php if (isset($vehicleDetail['seatcapacity'])) {
    echo $vehicleDetail['seatcapacity'];
} else {
    echo submitFailFieldValue("seatcapacity");
} ?>">           
            </div>
            
            <span class="clearfix"><p>&nbsp;</p></span>
            
            
            <div class="col-lg-4 col-md-4">
                <label for="Status">Vehicle Image</label>
                <input type="file" name="vehicleimage" id="vehicleimage" tabindex="16" class="form-control">              
            </div>
            <div class="col-lg-4 col-md-4">
                <label for="Status">Status</label>
                <select name="status" class="form-control" tabindex="16">
                    <option value="1"> Active </option>
                    <option value="2"> Inactive </option>
                        
                </select>               
            </div>
            <span class="clearfix"><p>&nbsp;</p></span> <span class="clearfix"><p>&nbsp;</p></span>
             <center>
            <div class="col-lg-12"> 
                <button type="button" id="show" class="btn btn-success" >Show Vehicle</button>
                <button type="reset" class="btn btn-primary">Reset</button>
                <button type="submit" id="submit" class="btn btn-success" >Save Vehicle</button>
            </div>
             </center>
        </form>
    </div>
</div>




<?php
require VIEW_FOOTER;

function showAllVehicle()
{
    $instsessassocid=$_SESSION['instsessassocid'];
    $sql= " SELECT vehicleid,LOWER(vehicletitle) as vehicletitle, 
            CASE vehicletype 
                WHEN 1 THEN 'Bus' 
                WHEN 2 THEN 'Mini Bus' 
                WHEN 3 THEN  'Three Wheeler' END AS vehicletype,
            CASE fueltype
                WHEN 1 THEN 'Petrol'
                WHEN 2 THEN 'Diesel'
                WHEN 3 THEN 'CNG/LPG' END AS fueltype ,  platenumber, seatcapacity , status
            FROM tblvehicle as t1
            WHERE instsessassocid='".$instsessassocid."' AND deleted!=1 ORDER BY vehicletitle , vehicleid";
    

    $res=  dbSelect($sql);
    if (mysqli_num_rows($res) >0) {
        while ($row=mysqli_fetch_assoc($res)) {
            $detailsArray['records'][]= $row;
        }
        $detailsArray['totalrows']=mysqli_num_rows($res);
        
        return $detailsArray;
    } else {
        return 0;
    }
}

function vehicleDetail()
{
    if (isset($_GET['mode']) && !empty($_GET['vid'])) {
        $sql= " SELECT * FROM tblvehicle WHERE vehicleid='".  cleanVar($_GET['vid'])."'";
        
       
        
        
        $result=  dbSelect($sql);
        if (mysqli_num_rows($result)>0) {
            $details=mysqli_fetch_assoc($result);
            return $details;
        } else {
            return 0;
        }
    }
}

function getDriverList()
{
    $sql= " SELECT driverid, UPPER(CONCAT (driverfirstname,' ' ,drivermiddlename ,' ' , driverlastname )) as drivername ,
            fathername, dob, mobile, qualification, status FROM tbldrivers WHERE deleted!=1 ORDER BY drivername";
    
    $res=  dbSelect($sql);
    if (mysqli_num_rows($res) >0) {
        while ($row=mysqli_fetch_assoc($res)) {
            $detailsArray['records'][]= $row;
        }
        $detailsArray['totalrows']=mysqli_num_rows($res);
        
        return $detailsArray;
    } else {
        return 0;
    }
}
?>
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

if (isset($_GET['mode']) && $_GET['mode']=='delete') {
    if (isset($_GET['delid']) && is_numeric($_GET['delid'])) {
        $result=dbUpdate("UPDATE tbldrivers SET deleted=1 WHERE driverid=".cleanVar($_GET['delid']));
        if ($result) {
            echo "<script>window.location='addDriver.php?s=36'</script>";
        }
    }
}

if (isset($_GET['mode']) && $_GET['mode'] == 'status') {
    if (isset($_GET['did']) && is_numeric($_GET['did'])) {
        $result=  statusUpdate('tbldrivers', cleanVar($_GET['status']), 'driverid='.cleanVar($_GET['did']));
        if ($result) {
            echo "<script>window.location='addDriver.php'</script>";
        }
    }
}
$driverArray= showAllDrivers();
if ($driverArray['totalrows']=='') {
    $totalRows=0;
}
$sno=1;

?>

<script>
        // $('input[type=date]').datepicker( {format: "yyyy/mm/dd"});
    $(function()
    {
        <?php if (isset($_GET['did']) && !(isset($_GET['mode']) === 'edit')) {
    ?>
        $( "#adddriver" ).show();
        $("#showdriver").hide();
        $('#add, #show').click(function()
        {
            $('#adddriver').toggle(200);
            $('#showdriver').toggle(200); 
        });
        <?php 
} else {
    ?>        
        $("#showdriver").show();
        $( "#adddriver" ).hide();
            
        $('#add, #show').click(function()
        {
                $('#adddriver').toggle(200);
                $('#showdriver').toggle(200); 
        });
            
        <?php 
} ?>
                  
    });
    
</script>   
<div class="container" >
    <?php      renderMsg(); ?>
    <div id="showdriver" name="showdriver">
         
    <?php if (!empty($driverArray) && $driverArray!=0) {
    ?>
    <table class="table table-bordered">
        <thead >
            <tr >
                <th >SNo.</th>
                <th> Driver Name.</th>
                <th> Driver Father </th>
                <th> Driver Contact</th>
                <th> Update </th>
                <th> Delete </th>
                <th> Status </th>
            </tr>
                
        </thead>
        <tbody>
            <?php 
            foreach ($driverArray['records'] as $key =>$value) {
                if ($value['status']==1) {
                    $statusStyle='class="glyphicon glyphicon-ok-circle" style="color:green"';
                } else {
                    $statusStyle='class="glyphicon glyphicon-ban-circle" style="color:red"';
                } ?>
            <tr >
                <td> <a href="addDriver.php?did=<?=$value['driverid']; ?>&mode=edit" class=""><?php echo $sno ?> </a></td>
                <td> <a href="addDriver.php?did=<?=$value['driverid']; ?>&mode=edit" class=""><?php echo ucwords($value['drivername'])?> </a></td>
                <td> <a href="addDriver.php?did=<?=$value['driverid']; ?>&mode=edit" class=""><?php echo ucwords($value['fathername'])?> </a></td>
                <td> <a href="addDriver.php?did=<?=$value['driverid']; ?>&mode=edit" class=""><?php echo $value['mobile']?> </a></td>
                
                <td> <a href="addDriver.php?did=<?=$value['driverid']; ?>&mode=edit" class=""><span class="glyphicon glyphicon-edit"></span></a></td>
                <td><a href="addDriver.php?delid=<?=$value['driverid']?>&mode=delete" class=""><span class="glyphicon glyphicon-trash"></span></a></td>
                <td><a href="addDriver.php?did=<?=$value['driverid']?>&status=<?=$value['status']?>&mode=status" class=""><span <?=$statusStyle?>></span></a> </td>
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
        <a href="#"><button type="submit" id="add" class="btn btn-success" href="#">Add Driver</button></a>
        <!--<button type="submit" name="search" id= "search" class="btn btn-primary" >Search</button>-->
        </div>
        <div class="col-sm-6" style="text-align: right; padding: 0px">
          <?php //getPagination($totalRows, ROW_PER_PAGE);?>
        </div>
    </div>
    
    <?php 
    $driverId = '';
    if (isset($_GET['mode']) && !empty($_GET['did']) && $_GET['mode']=='edit') {
        $driverDetail= driverDetail();
        $driverId = cleanVar($_GET['did']);
    }

?>    <div id="adddriver" name="adddriver">

        <form action="<?php echo PROCESS_FORM; ?>" method="post" enctype= "multipart/form-data"  name="imForm">
            <input type="hidden" name="mode" value="<?php if (isset($_GET['mode'])) {
    echo cleanVar($_GET['mode']);
} else {
    echo 'add';
}?>">
            <input type="hidden" name="driverid" value="<?php if (isset($_GET['mode'])) {
    echo $driverId ;
}?>">
            <h1 >Add Driver</h1>
                 <?php renderMsg(); ?>
            <div class="col-lg-4">
                <label for="firstname">Driver Firstname</label>
                <input type="text" name="firstname" id="firstname"  class="form-control" 
                       value="<?php if (isset($driverDetail['driverfirstname'])) {
    echo $driverDetail['driverfirstname'];
} else {
    echo submitFailFieldValue("firstname");
} ?>">
            </div>
            <div class="col-lg-4">
                <label for="middlename">Driver Middlename</label>
                <input type="text" name="middlename" id="middlename"  class="form-control" 
                       value="<?php if (isset($driverDetail['drivermiddlename'])) {
    echo $driverDetail['drivermiddlename'];
} else {
    echo submitFailFieldValue("middlename");
} ?>">
            </div>
            <div class="col-lg-4">
                <label for="lastname">Driver Lastname</label>
                <input type="text" name="lastname" id="lastname"  class="form-control" 
                       value="<?php if (isset($driverDetail['driverlastname'])) {
    echo $driverDetail['driverlastname'];
} else {
    echo submitFailFieldValue("lastname");
} ?>">
            </div>
            
            <span class="clearfix">&nbsp;</span> 
     
            <div class="col-lg-4">
                <label for="fathername">Driver Fathername</label>
                <input type="text" name="fathername" id="fathername"  class="form-control" 
                       value="<?php if (isset($driverDetail['fathername'])) {
    echo $driverDetail['fathername'];
} else {
    echo submitFailFieldValue("fathername");
} ?>">
            </div>
            
            <div class="col-lg-4">
                <label for="birthdate">Driver Birth Date</label>
                <div class="input-group">
                <input type="date" name="birthdate" id="birthdate"  class="form-control" 
                       value="<?php if (isset($driverDetail['dob'])) {
    echo $driverDetail['dob'];
} else {
    echo submitFailFieldValue("birthdate");
} ?>">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>
                </div>
            </div>
            
            <div class="col-lg-4">
                <label for="qualification">Qualification</label>
                <select name="qualification" class="form-control"   required>
                    <?php if (!empty($driverDetail['qualification'])) {
    echo PopulateSelect("qualification", $driverDetail['qualification']);
} else {
                           echo PopulateSelect("qualification", submitFailFieldValue("qualification"));
                       }
                    ?>
                </select>
            
            </div>

            <span class="clearfix">&nbsp;</span> 
            
            <div class="col-lg-4">
                <label for="address">Address</label>
                <input type="text" name="address" id="address"  class="form-control" 
                       value="<?php if (isset($driverDetail['address'])) {
                        echo $driverDetail['address'];
                    } else {
                        echo submitFailFieldValue("address");
                    } ?>">
            </div>
            
            <div class="col-lg-4">
              <label for="city">City</label>
                <select name="city"   id="city" class="form-control" >
                    <?php if (isset($driverDetail['city'])) {
                        echo PopulateSelect("cityname", $driverDetail['city']);
                    } else {
                                echo PopulateSelect("cityname", submitFailFieldValue("city"));
                            }
                    ?>
                </select>
                
            </div>
            
            <div class="col-lg-4">
                <label for="mobile">Contact Mobile</label>
                <input type="text" name="mobile" id="mobile"  maxlength="10" class="form-control" 
                    value="<?php if (isset($driverDetail['mobile'])) {
                        echo $driverDetail['mobile'];
                    } else {
                        echo submitFailFieldValue("mobile");
                    } ?>">
            </div>
            
            <span class="clearfix">&nbsp;</span> 
            
            <div class="col-lg-4">
                <label for="licenseno">License No</label>
                <input type="text" name="licenseno" id="licenseno"  class="form-control" 
                    value="<?php if (isset($driverDetail['licenseno'])) {
                        echo $driverDetail['licenseno'];
                    } else {
                        echo submitFailFieldValue("licenseno");
                    } ?>">
            </div>
            
            
             <div class="col-lg-4">
                <label for="validfrom">License Valid From</label>
                <div class="input-group">
                <input type="date" name="validfrom" id="validfrom"  class="form-control" 
                    value="<?php if (isset($driverDetail['validfrom'])) {
                        echo $driverDetail['validfrom'];
                    } else {
                        echo submitFailFieldValue("validfrom");
                    } ?>">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>
                </div>
             </div>
            
            <div class="col-lg-4">
                <label for="validto">License Valid To</label>
                <div class="input-group">
                <input type="date" name="validto" id="validto"  class="form-control" 
                    value="<?php if (isset($driverDetail['validto'])) {
                        echo $driverDetail['validto'];
                    } else {
                        echo submitFailFieldValue("validto");
                    } ?>">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" ></i></span>
                </div>
            </div>
            
            
            
            <span class="clearfix"><p>&nbsp;</p></span>
            
            <center>
            <div class="col-lg-12"> 
                <button type="button" id="show" class="btn btn-success" >Show Driver</button>
                <button type="reset" class="btn btn-primary">Reset</button>
                <button type="submit" id="submit" class="btn btn-success" >Save Driver</button>
            </div>
             </center>
        </form>
    </div>
</div>




<?php
require VIEW_FOOTER;

function showAllDrivers()
{
    $sql= " SELECT driverid, LOWER(CONCAT (driverfirstname,' ' ,drivermiddlename ,' ' , driverlastname )) as drivername ,
            LOWER(fathername) as fathername, dob, mobile, qualification, status FROM tbldrivers WHERE deleted!=1 ORDER BY drivername";
    

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

function driverDetail()
{
    if (isset($_GET['mode']) && !empty($_GET['did'])) {
        $sql= " SELECT * FROM tbldrivers WHERE driverid='".  cleanVar($_GET['did'])."'";
        
        $result=  dbSelect($sql);
        if (mysqli_num_rows($result)>0) {
            $details=mysqli_fetch_assoc($result);
            return $details;
        } else {
            return 0;
        }
    }
}
?>
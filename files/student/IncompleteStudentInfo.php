<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */

require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#studentlist').hide();

        $('#show').click(function () {
            $('#studentlist').toggle(400);
        });
    });
</script>
<div class="container">
    <div class="row">
        <div class="span8">
            <form action="<?php echo PROCESS_FORM; ?>" method="post" name="imForm">
                <input type="hidden" name="pageName" id="pageName" value="addInstitute">
                <div class="row-fluid">
                    
                    <div class="col-md-5">
                        <label for="Schoolemployee">Scholar No.</label>
                        <input type="text" value="" name="title"class="form-control"   />
                    </div>
                    <div class="col-md-5">
                        <label for="Schoolemployee">First Name</label>
                        <input type="text" value="" name="title" class="form-control" />
                    </div>
                    <span class="clearfix">&nbsp;<br></span>
                    <span class="clearfix">&nbsp;<br></span>
                    <div class="col-md-5">
                        <label for="Schoolemployee">Father name.</label>
                        <input type="text" value="" name="title" class="form-control" />
                    </div>
                    <div class="col-md-5">
                        <label for="Schoolemployee">Class.</label>
                        <select name="" value="" class="form-control"  >
                            <option value="Select">Select</option>
                            <option value="Nursery">Nursery</option>
                            <option value="K.G">K.G</option>
                            <option value="Prep">Prep</option>
                            <option value="I">I</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>

                    <span class="clearfix">&nbsp;<br></span>
                    <span class="clearfix">&nbsp;<br></span>

                    <div class="col-md-5">
                        <label for="Schoolemployee">Section</label>
                        <select name="" value="" class="form-control">
                            <option value="Select">Select</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label for="Schoolemployee">Type</label>
                        <select name="" value="" class="form-control">
                            <option value="Select">Select</option>
                            <option value="New">New</option>
                            <option value="Old">Old</option>
                            <option value="Transferred">Transferred</option>
                            <option value="Left">Left</option>
                        </select>

                    </div>
                    <span class="clearfix">&nbsp;<br></span>
                    <span class="clearfix">&nbsp;<br></span>

                    <div class="col-md-3">
                        <label for="Schoolemployee">Exact Match?</label>
                        <input type="checkbox" value="" name="title" style="margin-left:50px;" /></div>  

                    <div class="col-md-3">
                        <label for="Schoolemployee">Reset Search</label>
                        <input type="checkbox" value="" name="title" style="margin-left:52px; " /></div>

                </div> <!--span 8 div closed-->

                <div class="controls" align="right" style="margin-top:8px;">
                    <button type="button" class="btn btn-primary" >Search</button>
                    <button type="button" class="btn btn-primary" >Reset</button>
                    <button type="button" class="btn btn-primary" id="show" >List Students</button>
                    <a href="addstudentpersonal.php"> <button type="button" class="btn btn-primary">Add New Student</button></a>
                </div>

        </div> <!--row fluid closed-->
        </form>
    </div> <!--row div closed-->
</div> <!--container closed-->

<table class="table table-hover" id="studentlist" style=" border: solid 1px #e3e3e3;">
    <thead>
        <tr style="background:#2172A0;">
            <th style="color:#FFFFFF;">Image</th>
            <th style="color:#FFFFFF;">Scholar No.</th>
            <th style="color:#FFFFFF;">First/Last Name</th>
            <th style="color:#FFFFFF;">Fathers Name</th>
            <th style="color:#FFFFFF;">Type</th>
            <th style="color:#FFFFFF;">Class</th>
            <th style="color:#FFFFFF;">Incomplete fields</th>
            <th style="color:#FFFFFF;">Action</th>
        </tr>
    </thead>
    
    <tbody>
        <tr>
            <td>A</td>
            <td>10179</td>
            <td>DEVANSHU CHOUHAN</td>
            <td>OM PRAKASH CHOUHAN</td>
            <td> New</td>
            <td>K.G(A)</td>
            <td>Profile Image</td>
            <td>Edit/Delete</td>
        </tr>
        <tr>
            <td>B</td>
            <td>10180</td>
            <td>UTSAV ARORA</td>
            <td> VIKAS ARORA</td>
            <td> New</td>
            <td>K.G(A)</td>
            <td> Last Name, Profile Image, Phone Number</td>
            <td>Edit/Delete</td>
        </tr>

        <tr>
            <td>C</td>
            <td> 10204</td>
            <td>DIYA SHARMA</td>
            <td>RAHUL SHARMA</td>
            <td> New</td>
            <td>K.G(A)</td>
            <td>Mobile Number</td>
            <td>Edit/Delete</td>
        </tr>

        <tr>
            <td>D</td>
            <td> 10239</td>
            <td>PARTH DARYANANI</td>
            <td>DURGESH DARYANANI</td>
            <td> New</td>
            <td>K.G(A)</td>
            <td>Profile Image, Phone Number</td>
            <td>Edit/Delete</td>
        </tr>

        <tr>
            <td>E</td>
            <td> 10436</td>
            <td>DISHA BANSAL</td>
            <td>RAJENDRA BANSAL</td>
            <td> New</td>
            <td>K.G(A)</td>
            <td>Profile Image</td>
            <td>Edit/Delete</td>
        </tr>

    </tbody>
</table>

<span class="clearfix">&nbsp;<br></span>
<?php
require_once VIEW_FOOTER;
?>
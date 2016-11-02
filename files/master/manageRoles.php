<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au 
 * Page details here: Page to add new institute/branches
 * Updates here: 
 */


//call the main config file, functions file and header
require_once "../../config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;
?>
<form action="<?php echo PROCESS_FORM; ?>" method="post" enctype="multipart/form-data" name="imform">
<div class="container">
        <div class="span8">
            <?php renderMsg(); ?>

                <h1>Create User</h1>
                <div class="row">
                    <div class="col-lg-5">
                        <label for="roleid">Roles</label>
                        <select name="roleid" class="form-control" required="true">
                            <?php echo populateSelect("role", submitFailFieldValue("roleid")); ?>

                        </select>
                        <div class="hidden" name="divroleid" id="divroleid"><p class="text-danger">Please select an appropriate role!</p></div>
                    </div>
                    <div class="col-lg-5">
                        <label for="username">Email ID </label> <small> (This would be the username)</small>
                        <input type="email" class="form-control" placeholder="you@example.com"  id="username" name="username" required="true" 
                               value ="<?php echo submitFailFieldValue("username"); ?>">
                        <div class="hidden" name="divemail" id="divemail"><p class="text-danger">A valid, email is required! </p></div>
                    </div>

                </div>
                <span class ="clearfix"><br></span>
                <div class="row" id="passwrd">
                    <div class="col-lg-5">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" placeholder="Password"  id="password" name="password" required="true" pattern=".{7,}" title="Minimum 7 Characters.">
                        <small>Minimum 7 Characters.</small>
                        <div class="hidden" name="divpassword" id="divpassword"><p class="text-danger">Password must match, should be minimum 7 characters!</p></div>
                    </div>
                    <div class="col-lg-4">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" class="form-control" placeholder="Confirm Password"  id="confirmpassword" name="confirmpassword" required="true"  pattern=".{7,}" title="Minimum 7 Characters.">
                        <div class="hidden" name="divconfirmpassword" id="divconfirmpassword"><p class="text-danger">Password must match, should be minimum 7 characters!</p></div>

                    </div>

                    <div class="col-lg-1">
                        <label for="status">Active</label> <br>
                        <input type="checkbox" name="status" id="status" class="form-control" value="1" checked="checked">
                    </div>
                
                </div>
                <span class="clearfix"></span>
                <div class="row">    
                    
                    <div class="controls" align="center">
                        <input id="cancel" type="button" value="Cancel" class="btn">
                        <input type="submit" id="save" name="save" value="SAVE" class="btn btn-success">
                    </div>

                    <span class="clearfix">
                        <p>&nbsp;</p>
                    </span> 
                </div>
        </div>      
		</div>
</form> 		
     

<?php
require VIEW_FOOTER;
?>
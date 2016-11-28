<?php
/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here:
 * Updates here:
 */


//call the main config file, functions file and header

require_once "./config/config.php";
require_once DIR_FUNCTIONS;
require_once VIEW_HEADER;

?>
<script>
    $(function(){
        
    navigator.sayswho = (function () {
            var ua = navigator.userAgent, tem,
                    M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
            if (/trident/i.test(M[1])) {
                tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
                return 'IE ' + (tem[1] || '');
            }
            window.browser = M[1];
            
            if (M[1] === 'Chrome') {
                tem = ua.match(/\b(OPR|Edge)\/(\d+)/);
               
                if (tem != null)
                    return tem.slice(1).join(' ').replace('OPR', 'Opera');
            }
            
            M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
           
            if ((tem = ua.match(/version\/(\d+)/i)) != null)
                M.splice(1, 1, tem[1]);
            
            return M.join(' ');
        })();
        
        
        var version = navigator.sayswho;
        
        if(browser == "Chrome"){
            version = version.replace('Chrome', '');
            if(parseInt(version) < 50){
              window.location.replace("<?php echo DIR_FILES ?>/download.php?browser="+browser)
          }
        }
        
        if(browser == "Firefox"){
            version = version.replace('Firefox', '');
            if(parseInt(version) < 49){
              window.location.replace("<?php echo DIR_FILES ?>/download.php?browser="+browser)
          }
        }
        });
</script>
<div class="container">
    <div class="col-lg-4">
        <h1>Welcome to 360&deg; </h1>
        <p>Complete School Management Solution...</p>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
        <?php renderMsg(); ?>
        <div class="panel panel-info">
            
            <div class="panel-body">
                 <form action="<?php echo PROCESS_FORM; ?>" method="post" enctype= "multipart/form-data"  name="imForm">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" required name="email" placeholder="Email" maxlength="50" 
                         value ="<?php echo submitFailFieldValue("email"); ?>">
                    <div class="hidden" id="divemail"><code>Please enter valid, registered email ID. </code></div>
                    <br>
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" required placeholder="Password" name="password" maxlength="20">
                    <div class="hidden" id="divpassword"><code>Valid password is required. </code></div>
                   
                    <button type="submit" value="submit" class="btn btn-info" style="width: 100%;margin-top: 10px;margin-bottom: 10px;">Login Here</button>
                    </br> 
                    <div class="col-lg-6" style="text-align: left">
                    <input type="checkbox" name="rememberMe" value="yes" class="checkbox-inline" style> Remember Me
                        
                    </div>
                    <div class="col-lg-6" style="text-align: right">
                       <a href="#">Forgot Password ?</a>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</div>     

<?php
require VIEW_FOOTER;

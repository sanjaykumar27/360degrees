<?php

/*
 * 360 - School Empowerment System.
 * Developer: Ankur Mishra (amishra@ebizneeds.com.au) | www.ebizneeds.com.au
 * Page details here: Functions page for the whole application here, all application related functions are to be kept here.
 * Updates here:
 *
 */

/*    Error detecting funtion   */
set_error_handler('customErrorHandler');
register_shutdown_function('fatalErrorHandeler');

/* check before anything if session exists for login purpose */

if ((session_status() == PHP_SESSION_NONE)) {
    @session_start();
}
// user defined error handling function
function customErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {

    // timestamp for the error entry
    $dt = date("d-m-Y g:i (A)");
        
    // define an assoc array of error string
    // in reality the only entries we should
    // consider are E_WARNING, E_NOTICE, E_USER_ERROR,
    // E_USER_WARNING and E_USER_NOTICE
    $errortype = array(
        E_ERROR => 'Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parsing Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Runtime Notice',
        E_USER_DEPRECATED => 'User Depricated Error',
        E_RECOVERABLE_ERROR => 'Catchable Fatal Error'
    );
    // set of errors for which a var trace will be saved
    $user_errors = $errortype; //array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

    $err = "< ======== Error Occurred ======= >\n\n";
    $err .= "\tDate: " . $dt . "\n";
    $err .= "\tError No:  " . $errno . "\n";
    $err .= "\tError Type: " . $errortype[$errno] . "\n";
    $err .= "\tError Message:  " . $errmsg . "\n";
    $err .= "\tPage Name: " . $filename . "\n";
    $err .= "\tLine Num: " . $linenum . "\n";


    if (in_array($errno, $user_errors)) {
        $err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";
    }
    $err .= "\n < ============================ >\n";



    //$error = array($dt, $errno, $errortype[$errno], $errmsg, $filename, $linenum);

    if (!DEVELOPMENT_ENVIRONMENT) {
        $error = "";
    }

    $subject = "Critical User Error";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    error_log($err, 3, "/opt/logs/error/error.log");
    //mail("schourasia@ebizneeds.com", $subject, $err , $headers); 

    echo <<< HTML
   <style>
     html,body{height:100%;}
    .wrapper{min-height:100%; position:relative}
    .full{position:absolute; top:0; left:0; width:100%; height:100%;}
    </style>
   <script type="text/javascript"> 
   $(function(){
    var html = `<div class="container-fluid"><div class="wrapper"><div class="alert alert-danger">
            <h1> Oops...</h1>
            <p> Sorry, an unexpected error has occured. </p> 
            <p> We are terribly sorry for this. However, the technical team has been notified and they will 
                attend to it ASAP.  </p>
            <p>
                If you wish to restart please click here or go back. 
            </p>
           
        </div>
    
    <button data-toggle="collapse" data-target="#error" class="btn btn-danger">Show Error 
            <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
  
         <div id="error" class="collapse" style="white-space:pre-wrap;"><br>
          $err
         </div><br><br>
       
        <a href="javascript:history.go(-1)" class="btn btn-primary">Go to Previous Page</a>
        <a href="<?php echo DIR_FILES; ?>/dashboard.php" class="btn btn-success">Go to Dashboard</a><br>
</div></div></div><br>
`;
 
           jQuery('body').prepend(html);
  
            });
 </script>
HTML;



    // $redirect = "/360degrees/error.php?".http_build_query($error);
    // header( "Location: $redirect" );
}
/*
function customErrorHandler($errNo, $errString, $errFile, $errLine) {  // date_default_timezone_set('Asia');
    $backTrace = debug_backtrace();
    $functionName = $backTrace[0]['function'];
    $currentDate = date('d/m/Y: H:i:s');
    $customerrHandler = " ($currentDate) :- Error Happened ($errNo) : \t $errString\t$errFile\t at $errLine \tin function $functionName () \n";
    $fileHandler = fopen(DIR_ERROR, 'a+');
    fwrite($fileHandler, $customerrHandler);

   // header('Location: /360degrees/error.php?error='.$customerrHandler);
    $errorDisplay = '<div class="container">
        <div class="alert alert-danger">
            <h1> Oops...</h1>
            <p> Sorry, an unexpected error has occured. </p> 
            <p>  We are terribly sorry for this. However, the technical team has been notified and they will 
                attend to it ASAP.  </p>
            <p>
                If you wish to restart please click here or go back. 
            </p>
        </div>
        
        <button data-toggle="collapse" data-target="#error" class="btn btn-danger">Show Error</button>

         <div id="error" class="collapse"><br>
                <li>Error Object:  $errString  <br></li> 
                <li>Location:   $errFile<br></li>   
                <li>Line No:  $errLine  <br></li>
            </div>
    </div>';
 switch ($errNo) {
        case E_WARNING:
            echo $errorDisplay; 
            break;
            die;

        case E_ERROR:
            echo $errorDisplay; 
            break;

        case E_PARSE:
            echo $errorDisplay; 
            break; die; 

        case E_USER_ERROR:
           echo $errorDisplay; 
            break; die;

        case E_RECOVERABLE_ERROR:
            echo $errorDisplay; 
            break; die;

        case E_CORE_ERROR:
           echo $errorDisplay; 
            break; die;

        case E_CORE_WARNING:
           echo $errorDisplay; 
            break; die;

        case E_COMPILE_ERROR:
           echo $errorDisplay; 
            break; die;

        case E_COMPILE_WARNING:
           echo $errorDisplay; 
            break; die;

        default:
         echo $errorDisplay; 
            break; die;
    }
}
*/
function fatalErrorHandeler() {
    $last_error = error_get_last();
    $type = $last_error['type'];
    switch ($type) {
        case 1: /*  E_ERROR / FATAL ERROR   */
            customErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
        case 4: /*  E_PARSE / PARSE ERROR / SYNTAX ERROR  */
            customErrorHandler(E_PARSE, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
        case 16: /*  NOT FATAL ERROR PHP STARTUP   */
            customErrorHandler(E_NOTICE, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
        case 32: /*   FATAL COMPILE TIME ERROR   */
            customErrorHandler(E_STRICT, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
        case 64: /*  E COMPLILE ERROR   */
            customErrorHandler(E_CORE_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
        case 128: /*  E COMPILE WARNING   */
            customErrorHandler(E_COMPILE_WARNING, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
        case 8: /*  E COMPILE WARNING   */
            customErrorHandler(E_USER_DEPRECATED, $last_error['message'], $last_error['file'], $last_error['line']);
            break;
    }
}

// do the cleaning of variables, extra space, htmlencoding etc

/* -------------------- ERROR FUNCTION END ---------------------------- */
function cleanVar($var) {
    switch (gettype($var)) {
        case 'string':
            return trim(filter_var(preg_replace('/[^A-Za-z0-9\- \.@$&]/', ' ', $var), FILTER_SANITIZE_STRING));
            break;
        case 'array':
            foreach ($var as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        if (!empty($v[$k])) {
                            $v[$k] = filter_var(trim(preg_replace('/[^A-Za-z0-9\- \.@$&]/', ' ', $v)), FILTER_SANITIZE_STRING);
                        }
                    }
                    return $val;
                }
                if (!empty($var[$key])) {
                    $var[$key] = filter_var(trim(preg_replace('/[^A-Za-z0-9\- \.@$&]/', ' ', $val)), FILTER_SANITIZE_STRING);
                } else {
                    $var[$key] = null;
                }
            }

            return $var;
            break;
        default:
            return filter_var(trim(preg_replace('/[^A-Za-z0-9\- \.@$&]/', ' ', $var)), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            break;
    }
}

/* update so it checks that form was submitted from known source */

function wasFormSubmit() {
    if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
        // if ((!empty($_POST)) && (password_verify(SECERT_KEY, $_POST['confirmSource']))){
        return true;
    } else {
        return false;
        // header('Location: ../index.php?Err='.time());
    }
}

function getExecutionTime($do) {
    switch (strtolower($do)) {
        case 'start':
            list($usec, $sec) = explode(' ', microtime());

            return (float) $usec + (float) $sec;
        case 'end':
            list($usec, $sec) = explode(' ', microtime());

            return (float) $usec + (float) $sec;
    }
}

/* function to connect to DB, passes the connection to other functions, as required */

function dbConnect() {
    //assign the db variables
    $db = DB_NAME;
    $host = DB_HOST;
    $user = DB_USER;
    $pass = DB_PASSWORD;
    $port = DB_PORT;
    //$socket = DB_SOCKET;


    // connect to the server
    $mysqli = new mysqli($host, $user, $pass, $db, $port);

    /* check the following option, not to be included during prod server */
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
    /* if error, exist and send error to addError */
    if ($mysqli->connect_errno) {
        addError(1, $mysqli->connect_error);
    }

    return $mysqli;
}

/* to execute update queries */

function dbUpdate($sql) {
    
    if (is_array($sql) == 0) {
        $sqlarray[] = $sql;
    } else {
        $sqlarray = $sql;
    }
    $sqlCount = count($sqlarray);
    $con = dbConnect();
    $updateID = array();
    try {
        // begin a transaction
        $con->autocommit(false);
        /* commit transaction */

        foreach ($sqlarray as $key => $value) {
            if ($con->query($value)) {
                $updateID[] = $con->affected_rows;
            } else {
                trigger_error(mysqli_error($con));
            }
        }
        // if no error, commit.
        if ((!mysqli_error($con)) || (!mysqli_commit($con)) && ($sqlCount === count($updateID))) {
            $con->commit(); //mysqli_commit($con);
        } else {
            $con->rollback();
            trigger_error('Error in dbUpdate: ' . mysqli_error_list($con));
            $con->close();
        }
    } catch (Exception $e) {
        // if any error, catch the exception and rollback
        $con->rollback();
        trigger_error('Error in dbUpdate:' . $e);
    }
    /* close connection and return the result */
    $con->close();

    return $updateID;
}

/* Insert function for all insert queries, takes SQL as array and returns the insert ID as array */

function dbInsert($sql) {

    if (is_array($sql) == 0) {
        $sqlarray[] = $sql;
    } else {
        $sqlarray = $sql;
    }

    $sqlCount = count($sqlarray); //echoThis($sqlCount); die;
    $con = dbConnect();
    $insertID = array();
    try {
        // begin a transaction
        $con->autocommit(false);
        /* commit transaction */

        foreach ($sqlarray as $key => $value) {
            
            if ($con->query($value)) {
                $insertID[] = $con->insert_id;
                
            } else {
                trigger_error(mysqli_error($con));
                $con->rollback();
                $con->close();
                
            }
        }
       
        // if no error, commit.
        if ((!mysqli_error($con)) || (!mysqli_commit($con)) && ($sqlCount === count($insertID))) {
            $con->commit(); //mysqli_commit($con);
            
        } 
        
    } catch (Exception $e) {
        // if any error, catch the exception and rollback
        $con->rollback();
        trigger_error('Error in dbInsert:' . $e);
    }
    /* close connection and return the result */
    $con->close();

    return $insertID;
}

/* select function for all select statements */

function dbSelect($sql) {
    $con = dbConnect();
    $result = array();

    if (is_array($sql)) {
        $returnResult = array();
        foreach ($sql as $key => $value) {
            if ($result = $con->query($value)) {
                $returnResult[] = $result;
            } else {
                trigger_error(mysqli_error($con));
                $con->close();
            }
        }
        $con->close();

        return $returnResult;
    } else {

        if ($result = $con->query($sql)) {

            $con->close();

            return $result;
        } else {
            trigger_error(mysqli_error($con));
            $con->close();
        }
    }
}

/* key function to process all form data, takes the array mapped to each form,
 * loops through each elements, assigns value to variable with same name as form field */

function processFormData($formFields) {

    // assign a boolean to true
    $status = true;
    foreach ($formFields as $field => $value) {

        $type = explode('|', $value);

        if (isset($_POST[$field])) {
            $value = cleanVar($_POST[$field]);
        } else {
            $value = null;
        }

        if (!validateField($field, $type, $value)) {
            $status = false; // if in loop there's an invalid field, set the $status to false
        }
    }

    return $status;
}

/* key validation function, calls relevant validating function as required - string, int, email etc */

function validateField($field, $type, $value) {
    // assign a boolean to true
    global $errorArray;
    $status = true;
    switch (strtolower($type[1])) {
        case 'string': //echo($type[0]."-".$field.'=>'.$value."</br>");
            if (($type[0] == 'r') || (empty($value) === false)) {
                if (!validateString($value)) {
                    addError('string', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'float':
            if (($type[0] == 'r') || (empty($value) === false)) {
                if (!validateString($value)) {
                    addError('float', $field);
                    $status = false;
                }
            }

            return $status;
            break;
        case 'email':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateEmail($value)) {
                    addError('email', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'url':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateURL($value)) {
                    addError('url', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'int': //echo($type[0]."-".$field.'=>'.$value."</br>");
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateInt($value)) {
                    addError('int', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'arrint':
            if (($type[0] === 'r') || (!empty($value))) {
                if (!validateArrayInt($type[0], $value)) {
                    addError('int', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'date':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateDate($value)) {
                    addError('date', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'array':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateArray($value)) {
                    addError('string', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'arrdate':
            if (($type[0] == 'r') || !empty($value)) {

                if (!validateArrayDate($value)) {
                    addError('date', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'bool':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateBool($value)) {
                    addError('bool', $field);
                    $status = false;
                }
            }

            return $status;
            break;
        case 'time':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validateTime($value)) {
                    addError('time', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'img':
            $value = $_FILES[$field];
            if (($type[0] == 'r') || !empty($value['name'])) {
                if (!validateImage($_FILES[$field])) {
                    addError('imagext', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'arrimg':
            $value = $_FILES[$field];
            if (($type[0] == 'r') || !empty($value['name'])) {
                if (!validateArrayImage($_FILES[$field])) {
                    addError('imagext', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'document':
            $value = $_FILES[$field];
            if (($type[0] == 'r') || !empty($value['name'])) {
                if (!validateDocument($_FILES[$field])) {
                    addError('imagext', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'arrdocument':
            $value = $_FILES[$field];

            if (($type[0] == 'r') || !empty($value['name'])) {
                $validResult = validateArrayDocument($_FILES[$field]);
                if (!$validResult) {
                    addError('imagext');
                    $status = false;
                }
            }

            return $status;
            break;

        case 'mobile':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validatemobile($value)) {
                    addError('mobile', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'phone':
            if (($type[0] == 'r') || !empty($value)) {
                if (!validatephone($value)) {
                    addError('phone', $field);
                    $status = false;
                }
            }

            return $status;
            break;

        case 'default':
            addError('7', $field);

            return false;
            break;
    }
}

/* validation functions */
/* * ********************* */

/* validate string, check if its a string and is not empty */

function validateString($input) {

    //preg_match('/^[A-Za-z0-9_ -+]*$/', $value)
    if (!is_array($input)) {
        $input = array($input);
    }
    $input = array_filter($input);
    $status = true;
    foreach ($input as $value) {
        $d = (is_string($value) && !empty($value));
        if (!$d) {
            $status = false;
        }
    }

    return $status;
}

function validateTime($input) {
    //$is24Hours = true;
   // $seconds = false;
    $pattern = '/(2[0-4]|[01][1-9]|10):([0-5][0-9])/';
    if (preg_match($pattern, $input)) {
        return true;
    } else {
        return false;
    }
}

function validateFloat($input) {
    if (!is_array($input)) {
        $input = array($input);
    }
    $status = true;
    foreach ($input as $value) {
        $d = (is_string($value) && (preg_match('^[+-]?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?$', $value)) && !empty($value));
        if (!$d) {
            $status = false;
        }
    }

    return $status;
}

/* validate string, check if its a string and is not empty */

function validateBool($input) {
    if (!is_string($input)) {
        return (bool) $input;
    }

    switch (strtolower($input)) {
        case '1':
        case 'true':
        case 'on':
        case 'yes':
        case 'y':
            return 1;
        default:
            return 0;
    }
}

/* validate Int, check if its a string and is not empty */

function validateInt($input) {
    if (!is_array($input)) {
        $input = array($input);
    }

    $status = true;
    foreach ($input as $value) {
        $d = (is_numeric($value) && !empty($value));
        if (!$d) {
            $status = false;
        }
    }

    return $status;
}

function validateArrayInt($type, $input) {
    if (!is_array($input)) {
        $input = array($input);
    }

    $status = true;
    $input = array_filter($input);
    foreach ($input as $value) {
        if (strpos($value, ',')) {
            $arr = explode(',', $value);

            foreach ($arr as $val) {
                $d = (is_numeric($val) && !empty($val));
                if (!$d) {
                    $status = false;
                }
            }
        } else {
            if (($type === 'o') && (empty($value))) {
                $status = true;
            } else {
                $d = (is_numeric($value) && !empty($value));
                $status = $d;
            }
        }

        return $status;
    }
}

/* validate email */

function validateEmail($input) {
    return is_string($input) && filter_var($input, FILTER_VALIDATE_EMAIL) && !empty($input);
}

function validateUrl($input) {
    return is_string($input) && filter_var($input, FILTER_VALIDATE_URL) && !empty($input);
}

/* validate image */

function validateImage($input) {
    $status = true;
    $pos = strrpos($input['name'], '.');
    $ext = substr($input['name'], $pos);
    $extensionArray = array('.jpg', '.jpeg', '.png', '.gif');
    $imgSize = 512000;
    if (!(in_array($ext, $extensionArray)) or ( $input['size'] > $imgSize)) {
        $status = false;
    }

    return $status;
}

function validateArrayImage($input) {
    $status = true;
    if (!is_array($input)) {
        $input = array($input);
    }

    foreach ($input as $value) {
        $pos = strrpos($value['name'], '.');
        $ext = substr($value['name'], $pos);
        $extensionArray = array('.jpg', '.jpeg', '.png', '.gif');
        $imgSize = 512000;
        if (!(in_array($ext, $extensionArray)) or ( $input['size'] > $imgSize)) {
            $status = false;
        }
    }

    return $status;
}

function validateDocument($input) {
    $status = true;
    $pos = strrpos($input['name'], '.');
    $ext = substr($input['name'], $pos);
    $extensionArray = array('.PDF', '.doc', '.docx', '.gif', '.jpeg', '.jpg');
    $imgSize = 512000;

    if (!(in_array($ext, $extensionArray)) or ( $input['size'] > $imgSize)) {
        $status = false;
    }

    return $status;
}

function validateArrayDocument($input) {
    $status = true;
    if (!is_array($input['name'])) {
        $input = array($input);
    }

    foreach ($input['name'] as $key => $value) {
        $pos = strrpos($value, '.');
        $ext = substr($value, $pos + 1);

        $extensionArray = array('pdf', 'doc', 'docx', 'gif', 'jpeg', 'jpg');
        $imgSize = 512000;
        if ((!(in_array($ext, $extensionArray)) or ( $input['size'] > $imgSize))) {
            return false;
        } else {
            return true;
        }
    }
}

/* validate Date in format DD/MM/YYYY */

function validateDate($input) {
    if (!is_array($input)) {
        $input = array($input);
    }
    $status = true;
    foreach ($input as $value) {
        list($y, $m, $d) = preg_split('/[-\.\/ ]/', $value);
        if (!checkdate($m, $d, $y)) {
            $status = false;
        }
    }

    return $status;
}

function validateArrayDate($input) {

    if (!is_array($input)) {
        $input = array($input);
    }

    //if date is coming in an array, pop empty values, in case of
    // multiple field submissions, for e.g. vehicle meter entry
    $input = array_filter($input);

    $status = true;
    foreach ($input as $value) {
        if (strpos($value, ',')) {
            $Date = explode(',', $value);
            foreach ($Date as $D) {
                list($y, $m, $d) = preg_split('/[-\.\/ ]/', $D);
                if (!checkdate($m, $d, $y)) {
                    $status = false;
                }
            }
        } else {


            list($y, $m, $d) = preg_split('/[-\.\/ ]/', $value);

            if (!checkdate($m, $d, $y)) {
                $status = false;
            }
        }
    }

    return $status;
}

function validatemobile($input) {
    if (is_numeric($input) && strlen($input) != 10) {
        return false;
    } else {
        return true;
    }
}

function validatePhone($input) {
    if (is_numeric($input) && strlen($input) != 7) {
        return false;
    } else {
        return true;
    }
}

function validateArray($input) {
    return !empty($input) && is_array($input);
}

/* Encrypt Password and return the encrypted password */

function encryptIt($val) {
    return password_hash($val, PASSWORD_DEFAULT);
}

/* Function to store user error (validation etc) */

function renderMsg() {
    global $errorArray;

    if ((!empty($errorArray) || isset($_GET['e']))) {

        renderError();
    } elseif (isset($_GET['s'])) {
        renderSuccess();
    }
}

function addError($err = null, $field = '', $redirect = '') {

    global $errorArray;
    if (!empty($err) && !empty($redirect)) {
        if (strstr($redirect, '?')) {
            $redirect = $redirect . '&e=' . $err;
        } else {
            $redirect = $redirect . '?e=' . $err;
        }
    }

    $errorArray[$field] = $err;

    //if redirect option is set
    if (!empty($redirect)) {
        header("location: $redirect");
        exit();
    }
}

function returnErrMsg($errorArray) {
    global $errorArray;
    global $errMsg;

    $errHolder = array();

    $errMsg[0] = 'Session expired, please login again!';
    $errMsg[1] = "Database Error : Couldn't connect to dataase at this time. Try again later!";
    $errMsg[2] = 'COOKIES are disabled in your browser . Please turn on COOKIES and try again later.';
    /* Login   */
    $errMsg[5] = 'Login Error : Invalid username/email. Please enter a valid user/email and try again ';
    $errMsg[6] = 'Login Error : Invalid password. Please enter a valid password and try again !!!';
    $errMsg[7] = 'Login Error : Authentication Failed. Your account has been disabled. Please contact to administrator !!!';
    $errMsg[8] = 'Username already exist! Please use retrieve password option, if you are not able to login. ';
    $errMsg[11] = 'Invalid Login credentials..! contact administrator for assistance';
    $errMsg[12] = 'Please Upload file with valid extensions (PDF, .doc, .docx etc)';
    $errMsg[13] = 'Please Upload file with size less than 500 kb';
    $errMsg[14] = 'File Uploded unsuccessfully';
    $errMsg[17] = 'Duplicate Entry';
    $errMsg[18] = 'MySQL Insert Error';
    $errMsg['email'] = 'Please enter a valid E-mail ID!';
    $errMsg['url'] = 'Please enter a valid URL !';
    $errMsg['phone'] = 'Please enter a valid Phone Number!';
    $errMsg['mobile'] = 'Please enter a valid Mobile Number!';
    $errMsg['string'] = 'Please enter a valid value in highlighed field(s).';
    $errMsg['int'] = 'Please enter numbers only!';
    $errMsg['date'] = 'Please enter valid date only!';
    $errMsg['bool'] = 'Please select a valid option!';
    $errMsg['float'] = 'Please enter a valid value!';
    $errMsg['studentdetails'] = 'Sorry no student record found! Please check and enter again!';
    $errMsg['CollectedFeedetails'] = 'Sorry No Record of Fee Collection exist.. Please check and enter again..!';
    $errMsg['feedueerror'] = 'Invalid search.. Make a Proper search!';
    $errMsg['feedatevalidation'] = ' Sorry No Record Found! Please check the details and enter Again...!';
    $errMsg['reportcustomerroor'] = ' Sorry No Record Found! For The Given Details...! Please check and enter Again...!';
    $errMsg['time'] = ' Invalid Time Format. Please specify the time in a valid 24 hours format.';
    $errMsg['imagext'] = 'Please upload file with valid extension';
    $errMsg['imagsize'] = 'Image size should be less than 500 kb';
    $errMsg['custom'] = 'Unauthorized Access';


    if (!is_array($errorArray)) {
        $input = array($errorArray); // echoThis($input); die;
    }

    $input = $errorArray;

    foreach ($input as $field => $err) {
        if (!isset($errMsg[$err]) && !empty($err)) {
            // if the error code is not empty and error message has been provided, than add it to custom error array.
            $errMsg['customErr'] = $err;
            $errHolder[] = $errMsg['customErr'];
        } else {
            $errHolder[$field] = $errMsg[$err];
        }
    }

    return $errHolder;
}

function renderError() {
    global $errorArray;
    global $errMsg;

    $errDisplay = $errMsg = $errField = array();
    if (isset($_GET['e'])) {

        $errorArray[] = cleanVar($_GET['e']);
    }

    if (count($errorArray) != 0) {
        $size = sizeof($errorArray);
        $errorArray = returnErrMsg($errorArray);

        foreach ($errorArray as $field => $err) {
            if (!empty($field)) {
                $errField[] = $field;
            }
        }


        $errDisplay = implode('</li><li>', array_unique($errorArray));
        $errField = json_encode($errField);

        /* if no field is passed, which should mean its not a form process type of error,
         * don't display the below, its a simple javascript which is used to highlight
         * the error input fields
         */

        if (!empty($field)) {
            echo <<<HTML
                        <script type="text/Javascript">
                        var json = $errField;
                        $(document).ready(function () {
                          $.each(json,function (index,value) {
                             $("label[for='"+value+"']").css("color", "#FF0000");
                             $('#div'+value).removeClass('hidden').addClass('show');
                             if($("input[id='"+value+"']").is("input")){ $("input[id='"+value+"']").attr('class', "form-control alert-danger");}
                             if($("select[id='"+value+"']").is("select")){ $("select[id='"+value+"']").attr('class', "form-control alert-danger");}
                          });
                        });                                
                        </script>
HTML;
        }
    }

    echo <<<HTML
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert" href="#">×</a>
            <strong class="alert-heading">The following error(s) were found:</strong>
            <ul><li>$errDisplay</li></ul>
			</div>
HTML;
}

function renderSuccess() {
    $successMsg = array();
    $successMsg[0] = '';
    $successMsg[1] = ' Institute added successfully !';
    $successMsg[2] = ' Institute updated successfully !';
    $successMsg[3] = ' Academic session added successfully !';
    $successMsg[4] = ' Academic session updated successfully !';
    $successMsg[5] = ' Collection Type added successfully  !';
    $successMsg[6] = ' Collection Type updated successfully  !';
    $successMsg[7] = ' Collection item successfully deleted !';
    $successMsg[8] = ' User successfully added!';
    $successMsg[9] = ' User updated successfully !';
    $successMsg[10] = ' Fee details added successfully !';
    $successMsg[11] = ' Fee details updated successfully !';
    $successMsg[12] = ' Fee details deleted successfully !';
    $successMsg[13] = ' Fee rule added successfully !';
    $successMsg[14] = ' Fee rule updated successfully !';
    $successMsg[15] = ' Fee rule deleted successfully !';
    $successMsg[16] = ' Other Fee added successfully !';
    $successMsg[17] = ' Other Fee updated successfully !';
    $successMsg[18] = ' Other Fee deleted successfully !';
    $successMsg[19] = ' Class Master details added successfully !';
    $successMsg[20] = ' Class Master details updated successfully !';
    $successMsg[21] = ' Class Master details deleted successfully !';
    $successMsg[22] = ' Student details successfully added !';
    $successMsg[23] = ' Student details successfully updated !';
    $successMsg[24] = ' Student details successfully deleted !';
    $successMsg[25] = ' Parent details successfully added !';
    $successMsg[26] = ' Parent details successfully updated !';
    $successMsg[27] = ' Parent details successfully deleted !';
    $successMsg[28] = ' Student medical details successfully added !';
    $successMsg[29] = ' Student medical details successfully updated !';
    $successMsg[30] = ' Student medical details successfully deleted !';
    $successMsg[31] = ' Student fee rule details successfully added !';
    $successMsg[32] = ' Student fee rule details successfully updated !';
    $successMsg[33] = ' Vehicle details successfully added !';
    $successMsg[34] = ' Vehicle details successfully updated !';
    $successMsg[35] = ' Vehicle details successfully deleted !';
    $successMsg[36] = ' Driver details successfully added !';
    $successMsg[37] = ' Driver details successfully updated !';
    $successMsg[38] = ' Driver details successfully deleted !';
    $successMsg[39] = ' Route details successfully added !';
    $successMsg[40] = ' Route details successfully updated !';
    $successMsg[41] = ' Route details successfully deleted !';
    $successMsg[42] = ' Data imported successfully !';
    $successMsg[43] = ' Student Document successfully uploaded !';
    $successMsg[44] = ' Student Profile successfully created !';
    $successMsg[45] = ' Logout Successfully !';
    $successMsg[46] = ' Pickup point details added successfully !';
    $successMsg[47] = ' Pickup point details updated successfully!';
    $successMsg[48] = ' Subjects added successfully!';
    $successMsg[49] = ' Subjects updated successfully!!';
    $successMsg[50] = ' Subjects deleted successfully!';
    $successMsg[51] = ' Student Document deleted successfully!';
    $successMsg[52] = ' Vehicle Travel Data Successfuly Added';
    $successMsg[53] = ' Vehicle Fuel Data Successfuly Added';

    if (isset($_GET['s'])) {
        $msgID = cleanVar($_GET['s']);

        if (!is_numeric($msgID)) {
            $msgID = 0;
        }

        echo <<<HTML
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert" href="#">×</a>
				<strong class="alert-heading">Success</strong>
				<ul><li>$successMsg[$msgID]</li></ul>
			</div>
HTML;
    }
}

function validUser() {
    //get the current page, to be used to check where to redirected user.
    
    
    $pageName = strtolower(basename($_SERVER['PHP_SELF']));
    if($pageName == 'download.php'){
       return false;
    }
    
    if (session_status() == PHP_SESSION_NONE) {
        @session_start();
    }

    //check if valid cookies exists

    if (isset($_COOKIE[COOKIE_NAME])) {
        //if a valid cookies exists, creat a new session. 
        $status = createSession();
    }
    
    if ((array_key_exists('login', $_SESSION)) && isset($_SESSION['userGroup']) && $_SESSION['userGroup'] != '') {
        //if the user is logged in and returns to login  page, redirect the user to dashboard.

        if ($pageName == 'index.php') {
            header('Location:' . DIR_FILES . '/dashboard.php');
            exit();
        }
        // check the role of the request
        /* if (!empty($role)){
          $status = checkUser();
          return $status;
          } */
        //if the request is not coming from login (index) page, returns true is the user is logged in.
        return true;
    } else {
 
        // return false as use is not logged in, for other functions
        // return false;
        // if the user is  not logged in, redirect to login page.
        if ($pageName != 'index.php') {
            addError(0, null, DIR_BASE . 'index.php?e=0');
            //header('Location: /360/index.php?e=4');
            // exit();
        }
 
        return false;
    }
   
}

/* * ********************************
 * validate if the user is allowed in the group (area) requested
 */

function createSession() {
    $status = false;
    $username = $_COOKIE[COOKIE_NAME];
    $sql = "SELECT userid, username, roleid, instsessassocid 
            FROM `tbluser` WHERE `username` = '$username' AND status = 1";

    $result = dbSelect($sql);
    //the result should return only one user, if more returned than lock the account
    // and verify 

    if (mysqli_num_rows($result) == 1) {
        //if user is found, create all the session required for the system. 
        while ($rows = mysqli_fetch_assoc($result)) {
            $_SESSION['userid'] = $rows['userid'];
            $_SESSION['login'] = $rows['username'];
            $_SESSION['userGroup'] = $rows['roleid'];
            $_SESSION['instsessassocid'] = $rows['instsessassocid'];
            $_SESSION['feestructure'] = getClassFeesStructure(null);
        }

        /* it will store the information about the user who is logged in */
        if (logUser('Insert')) {
            
        } else {
            addError(0, null, DIR_BASE);
        }

        // all is good, return true
        $status = true;
    } else {
        // if more than one user detected, call addError
        addError("More than one user detected, account disabled.");
    }
    return $status;
}

function checkUser() {

    //define role levels
    $accessGroup = array('2' => 'files', '3' => 'parent', '1' => 'student');
    $currentGroup = $accessGroup[$_SESSION['userGroup']];
    if (preg_match('/' . $currentGroup . '\//', $_SERVER['PHP_SELF'])) {
        return true;
    }

    return false;
}

/* * ******************************************************************************************
 * Funcion for logout functionality. When user click over logout it end the session data
 * and also removes the cookies data itself. Function is revised with bug fixes and cookies
 * Re-written by : Abhishek K. Sharma Dated : 20-SEP-2015
 * ****************************************************************************************** */

function logOut() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
   
    // update the logged list for log out of that session/userid
   $status = logUser('Update');
 
    if (isset($_COOKIE[COOKIE_NAME])) {
        setcookie(COOKIE_NAME, null, time() - 3600, '/');
    }
    
    if ($status){
    session_destroy();
    session_write_close();
    header('Location:' . DIR_BASE . 'index.php?s=45');
    exit();
    }
}

/** Breadcrumb function, looks at the URL and makes the trail, ignores folders that are not necessary
 *  Modified By: Sanjay Kumar
 * date: 05 Sept 2016.
 */
function breadCrumb() {
    $page = bcPage();
    $folderBasicPage = array(
        'Master' => 'files/master/addInstitute.php',
        'Student' => 'files/student/studentDashboard.php',
        'Transport' => 'files/transport/addPickUpPoint.php',
        'Fees' => 'files/fees/feeCollection.php',
        'Reports' => 'reports/dailyReport.php',
        'Studentservices' => 'studentservices/issueTC.php',
        'Admin' => 'files/admin/importStudent.php',
        'Shortcuts' => '#',
    );

    // folder to ignore and other variables
    $filterFolders = array('360degrees', 'files');

    if ($location = substr(dirname($_SERVER['PHP_SELF']), 1)) {
        $dirlist = explode('/', $location);
    } else {
        $dirlist = array();
    }
    /* update the array with non required folders * */
    echo '<ul class="breadcrumb"><li><a style="color:#fff;" href=' . DIR_BASE . '>Home</a></li>';
    foreach ($dirlist as $k => $v) {
        if (!in_array($v, $filterFolders)) {
            echo '<li><a href=' . DIR_BASE . $folderBasicPage[ucfirst($v)] . '>' . ucfirst($v) . '</a></li>';
        }
    }
    $str = "<li>$page</li></ul>";
    if (validUser()) {
        $str = '<li>' . $page . '</li>
                  <li class="dropdown pull-right">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Change Session<span class="caret"></span>
                    </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">2015-16</a></li>
                            <li><a href="#">2014-15</a></li>
                            <li><a href="#">2013-14</a></li>
                        </ul>
                      </li><li class="pull-right">' . getSessionName() . '</li> 
                   <div class="pull-right">User:&nbsp; <span style="color:#fff;" class="text-success">' . $_SESSION['login'] . '</span></div>
               </ul>';
    }

    echo $str;
}

function editForm($fieldName) {
    if (isset($_GET['editid']) && is_numeric(($_GET['editid']))) {
        echo 'me';
    }
}

/* if form validation is wrong, returns the field value for ease of use * */

function submitFailFieldValue($fieldName) {
    if ((wasFormSubmit()) && (isset($_POST[$fieldName]))) {
        if (!is_array($_POST[$fieldName])) {
            return $_POST[$fieldName];
        }

        return implode(', ', $_POST[$fieldName]);
    }
}

function getClientIP() {
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        return $_SERVER['REMOTE_ADDR'];
    }

    if (getenv('HTTP_X_FORWARDED_FOR')) {
        return getenv('HTTP_X_FORWARDED_FOR');
    }

    if (getenv('HTTP_CLIENT_IP')) {
        return getenv('HTTP_CLIENT_IP');
    }

    return getenv('REMOTE_ADDR');
}

function populateSectionCheckbox($value = null) {
    $sql = "SELECT `sectionid`, `sectionname` FROM `tblsection` WHERE `status` = '1'";
    // connection to DB
    $result = dbSelect($sql);
    if ($result) {
        return checkBoxSection($result);
    }
}

function testSql(){
   
    $sql = "SELECT 1 FROM `tbluser` WHERE `username` = '$username' LIMIT 1"; 
    
    return $sql; 
}
function returnSql($name) {
    /* if form was submitted, include the process functions file */
    //if (wasFormSubmit()) {
    //  include_once 'processFunctions.php';
    //}
    // array of all the SQL statements here
    $instsessassocid = $_SESSION['instsessassocid'];
    $sql = array();
    $sql['months'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, 
                 `collectionname`, `status` FROM `tblmastercollection`
                  WHERE `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                  WHERE `mastercollectiontype` = 'Months');",
    );

    $sql['academicsessionid'] = array(
        'academicsessionid',
        'sessionname',
        'SELECT t1.sessionname,t1.academicsessionid,t1.sessionstartdate,t1.sessionenddate, t1.status '
        . 'FROM tblacademicsession as t1 LEFT JOIN tblinstsessassoc as t2 ON t1.academicsessionid=t2.academicsessionid '
        . "WHERE t1.deleted!=1 AND t2.instituteid='$instsessassocid' AND t1.status= 1 AND deleted!=1",
    );

    $sql['instituteid'] = array(
        'instituteid',
        'institutename',
        "SELECT `instituteid`, `institutename` FROM `tblinstitute` WHERE `status` = '1';",
    );

    $sql['classname'] = array(
        'classid',
        'classname',
        'SELECT `classid`,`classname`, `classdisplayname` FROM `tblclassmaster` WHERE `status` = 1 AND `instsessassocid`= ' . $instsessassocid . ' ',
    );

    $sql['selectizeClassName'] = array(
        'classid',
        'classname',
        'SELECT * FROM `tblclassmaster` WHERE `classid` NOT IN (SELECT `classid` FROM `tblclsecassoc`);',
    );

    $sql['statename'] = array(
        'stateid',
        'statename',
        'SELECT `stateid`,`statename` FROM `tblstate` WHERE 1; ',
    );

    $sql['role'] = array(
        'RoleId',
        'RoleName',
        'SELECT `RoleId`, `RoleName` FROM `tblroles` WHERE 1; ',
    );

    $sql['busid'] = array(
        'vehicleid',
        'vehicletitle',
        'SELECT vehicleid,vehicletitle,platenumber FROM tblvehicle WHERE status=1 and deleted=0 ',
    );

    $sql['pickuppointname'] = array(
        'pickuppointid',
        'pickuppointname',
        'SELECT `pickuppointid`, `pickuppointname` FROM `tblpickuppoint` WHERE status=1 
           AND deleted=0 ORDER BY pickuppointname,pickuppointid',
    );
    $sql['drivername'] = array(
        'driverid',
        'drivername',
        "SELECT driverid,CONCAT(driverfirstname , ' ',drivermiddlename, ' ' ,driverlastname) as drivername 
                                                 FROM tbldrivers WHERE status=1 AND deleted=0 
                                                 AND instsessassocid = $instsessassocid  ORDER BY drivername , driverid",
    );

    $sql['gender'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, `status` 
           FROM `tblmastercollection` WHERE `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
           WHERE `mastercollectiontype` = 'gender');",
    );

    $sql['studenttype'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, 
           `mastercollectiontypeid`, `collectionname`, `status` 
            FROM `tblmastercollection`
            WHERE `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
            WHERE `mastercollectiontype` = 'student type');",
    );

    $sql['religion'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
           `status` FROM `tblmastercollection` WHERE 
            `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` 
                                            FROM `tblmastercollectiontype`  
                                            WHERE `mastercollectiontype` = 'Religion');",
    );

    $sql['bloodgroup'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
                                                `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` =(SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                                WHERE `mastercollectiontype` = 'bloodgroup');",
    );

    $sql['category'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
                                                `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                                WHERE `mastercollectiontype` = 'category');",
    );

    $sql['qualification'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`,
                                            `status`FROM `tblmastercollection`  WHERE `mastercollectiontypeid` = 
                                            (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                            WHERE `mastercollectiontype` = 'qualification');",
    );

    $sql['occupation'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
        `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
        WHERE `mastercollectiontype` = 'occupation');",
    );

    $sql['income'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
           `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` = 
                                            (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                            WHERE `mastercollectiontype` = 'income');",
    );

    $sql['relation'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`,
            `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` = 
                (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                    WHERE `mastercollectiontype` = 'relation') AND `tblmastercollection`.`status` = 1 ;",
    );

    $sql['result'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`,
                                            `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` = 
                                            (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                            WHERE `mastercollectiontype` = 'Result');",
    );

    $sql['countryname'] = array(
        'countryid',
        'countryname',
        'SELECT `countryid`,`countryname`  FROM `tblcountry` WHERE 1;',
    );

    $sql['cityname'] = array(
        'cityid',
        'cityname',
        'SELECT `cityid`,`cityname` FROM `tblcity` WHERE 1; ',
    );

    $sql['currentsuburb'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
                                            `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` =(SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                            WHERE `mastercollectiontype` = 'suburbs' AND `deleted` != 1);",
    );

    $sql['studentdocs'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
                                            `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid`=
                                            (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                            WHERE `mastercollectiontype` = 'student docs');",
    );

    $sql['feecollectionmode'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
                                            `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid`=
                                            (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                            WHERE `mastercollectiontype` = 'FeeCollectionMode');",
    );

    $sql['sectionname'] = array(
        'sectionid',
        'sectionname',
        'SELECT `sectionid`,`sectionname`, `status` FROM `tblsection` WHERE  `status` = 1;',
    );

    $sql['subjectname'] = array(
        'subjectid',
        'subjectname',
        'SELECT `subjectid`,`subjectname`, `status` FROM `tblsubjects` WHERE  `status` = 1;',
    );

    $sql['feecomponentname'] = array('feestructureid',
        'feecomponentid', "SELECT `feestructureid`, `feecomponentid` 
                                            FROM `tblfeestructure` WHERE `status` = 1 AND `instsessassocid` = $_SESSION[instsessassocid];",
    );

    $sql['feecomponentnameshow'] = array(
        'feestructureid',
        'feecomponentid',
        'amount',
        'SELECT t1.feestructureid, t1.feecomponentid, t2.amount  FROM 
                                                `tblfeestructure`AS t1 LEFT JOIN `tblfeestructuredetails` As t2 
                                                ON t1.componentid = t2.feestructureid LIMIT 0,3;',
    );

    $sql['feecomponents'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`,
                                                `collectionname`, `status` FROM `tblmastercollection`
                                                WHERE `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                                WHERE `mastercollectiontype` = 'Fee Components');",
    );

    $sql['feeRuleShowSelect'] = array(
        'SELECT t1.feerulename, t1.feeruletypeid, t1.feeruleremarks, t2.feeruletype, 
                                                t2.feeruleamount,t2.feerulemodeid FROM `tblfeerule` AS t1 LEFT JOIN 
                                                `tblfeeruledetail` AS t2 ON t2.feeruleid = t1.feeruleid LIMIT 0,5 ;',
    );

    $sql['feerule'] = array(
        'feeruleid',
        'feerulename',
        "SELECT t1.feerulename, t1.feeruleid, t1.feeruleremarks, t2.feeruletype, 
                                                t2.feeruleamount,t2.feerulemodeid 
                                                FROM `tblfeerule` AS t1 LEFT JOIN 
                                                `tblfeeruledetail` AS t2 ON t2.feeruleid = t1.feeruleid 
                                                WHERE t1.feerulestatus=1 AND t1.deleted!=1
                                                AND t1.instsessassocid = $instsessassocid 
                                                GROUP BY t1.feeruleid ;",
    );

    $sql['username'] = array(
        '',
        '', 'SELECT `username` FROM `tbluser` WHERE `username` = ',
    );

    $sql['otherfeecomponent'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`,
                                                `collectionname`, `status` FROM `tblmastercollection` WHERE 
                                                `mastercollectiontypeid` = (SELECT `mastercollectiontypeid` 
                                                FROM `tblmastercollectiontype` 
                                                WHERE `mastercollectiontype` = 'fee discount mode');",
    );

    $sql['feedepositeperiod'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`,
                                                `collectionname`, `status` FROM `tblmastercollection` 
                                                 WHERE `mastercollectiontypeid` = 
                                                (SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                                WHERE `mastercollectiontype` = 'Fee Frequency');",
    );

    $sql['feecollectionmode'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT `mastercollectionid`, `mastercollectiontypeid`, `collectionname`, 
                                                    `status` FROM `tblmastercollection` WHERE `mastercollectiontypeid` = (
                                                    SELECT `mastercollectiontypeid` FROM `tblmastercollectiontype` 
                                                    WHERE `mastercollectiontype` = 'Fee Collection Mode'
							);",
    );

    $sql['studentdetailsshow'] = array(
        'SELECT t1.scholarnumber, t1.firstname , t1.lastname , t2.lastname 
                                            FROM  `tblfeestructure`AS t1 LEFT JOIN `tblfeestructure` As t2 
                                            ON t1.componentid = t2.feestructureid LIMIT 0,3;',
    );

    $sql['employee'] = array(
        'employeeid',
        'firstname',
        'SELECT `employeeid`,`firstname`,`middlename`,`lastname`, `status` '
        . '                                 FROM `tblemployee` WHERE  `status` = 1',
    );

    // sql for fee component name, currently used in selectize in fee Master and rules
    $sql ['feecomponent'] = array(
        'feecomponentid',
        'feecomponent',
        "SELECT  `feecomponentid`, `feecomponent` FROM `tblfeecomponent` 
                                             WHERE `status` = 1 AND `instsessassocid` = $instsessassocid;",
    );

    $sql['document'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT mastercollectionid,collectionname FROM tblmastercollection as 
                                            T1 LEFT JOIN tblmastercollectiontype  as T2 ON 
                                            T1.mastercollectiontypeid=T2.mastercollectiontypeid 
                                            WHERE T2.mastercollectiontype='Documents'" );
    
    $sql['exammaster'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT mastercollectionid,collectionname FROM tblmastercollection as 
                                            T1 LEFT JOIN tblmastercollectiontype  as T2 ON 
                                            T1.mastercollectiontypeid=T2.mastercollectiontypeid 
                                            WHERE T2.mastercollectiontype='Exam Master'" );
    
    $sql['examcomponent'] = array(
        'mastercollectionid',
        'collectionname',
        "SELECT mastercollectionid,collectionname FROM tblmastercollection as 
                                            T1 LEFT JOIN tblmastercollectiontype  as T2 ON 
                                            T1.mastercollectiontypeid=T2.mastercollectiontypeid 
                                            WHERE T2.mastercollectiontype='Exam Components'" );

    if (isset($sql[$name])) {
        return $sql[$name];
    }
    trigger_error("SQL Statement not found, administrator has been informed. Error in:$name");
}

function populateSelect($sqlName, $value = null) {
    $sql = returnSql($sqlName);
    $result = dbSelect($sql[2]);

    if ($result) {
        $selID = $sql[0];
        $selValue = $sql[1];

        if ($value != $selID) {
            $options = '<option selected="selected" value=""> - Select One - </option>';
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row[$selID];
            $name = ucwords($row[$selValue]);
            if ($value == $id) {
                $selected = ' selected = "selected"';
            } else {
                $selected = '';
            }
            $options .= "<option value=\"{$id}\"{$selected}>$name</option> ";
        }
        return $options;
    }
}

function populateCheckBox($sqlName, $fieldName, $value = null, $returnAs = '') {
    $sql = returnSql($sqlName);
    $result = dbSelect($sql[2]);
    $options = $returnAs = $name = "";

    if ($result) {
        $selID = $sql[0];
        $selValue = $sql[1];

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row[$selID];
            $name = ucwords($row[$selValue]);
            if (is_array($value)) {
                if (in_array($id, $value)) {
                    $checked = ' checked = "checked"';
                } else {
                    $checked = '';
                }
            } else {
                if ($value == $id) {
                    $checked = ' checked = "checked"';
                } else {
                    $checked = '';
                }
            }

            if (is_array($returnAs)) {
                $options[] = "\n<label class=\"checkbox-inline\">\n<input type=\"checkbox\" name=\"$fieldName\" id=\"$name\" value=\"$id\"{$checked}> $name \n</label><br>";
            } else {
                $options .= "\n <label class=\"checkbox-inline\">\n<input type=\"checkbox\" name=\"$fieldName\" id=\"$name\" value=\"$id\"{$checked}> $name \n</label><br>";
            }
        }

        return $options;
    }
}

function getFeeInst($feeRuleId) {
    $instsessassocid = cleanVar($_SESSION['instsessassocid']);

    if (isset($_REQUEST['sid']) && !empty($_REQUEST['sid']) && is_numeric(($_REQUEST['sid']))) {
        $studentId = cleanVar($_REQUEST['sid']);
        $sqlInst = "SELECT  t4.duedate
              FROM  tblfeerule as t1, 
              tblfeeruledetail as t2 ,
              tblfeestructure as t3 , 
              tblfeestructuredetails as t4, 
              tblclsecassoc as t5 ,
              tblstudentacademichistory as t6
                WHERE   t1.feeruleid = t2.feeruleid 
                AND t2.feecomponentid=t3.feecomponentid 
                AND t3.feestructureid=t4.feestructureid 
                AND t3.classid=t5.classid 
                AND t5.clsecassocid=t6.clsecassocid 
                AND  t1.feerulestatus=1 
                AND t6.studentid = $studentId
                AND t3.instsessassocid = $instsessassocid 
                AND t1.feeruleid=$feeRuleId 
                GROUP BY t4.duedate 
                ORDER BY t4.duedate ";

        //echoThis($sqlInst);die;
        $resInst = dbSelect($sqlInst);
        if (mysqli_num_rows($resInst) > 0) {
            while ($row = mysqli_fetch_assoc($resInst)) {
                $instArray[] = $row;
            }

            return $instArray;
        } else {
            return null;
        }
    }
}

function populateFeeRuleCheckBox($sqlName, $fieldName, $value = null, $returnAs = '') {
    $sql = returnSql($sqlName);
    $result = dbSelect($sql[2]);
    $options = $returnAs;
    $studentId = cleanVar($_GET['sid']);

    if ($result) {
        $x = 1;
        $selID = $sql[0];
        $selValue = $sql[1];

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row[$selID];
            $name = strtoupper($row[$selValue]);
            $checked = '';
            $style = 'visibility:hidden';
            $feeInstallments = getFeeInst($id);
            if (!empty($feeInstallments) || $feeInstallments != null || $feeInstallments != 0) {
                if (is_array($value)) {
                    if (in_array($id, $value)) {
                        $checked = ' checked = "checked"';
                        $style = 'visibility:visible';
                    } else {
                        $checked = '';
                        $style = 'visibility:hidden';
                    }
                } else {
                    if ($value == $id) {
                        $checked = ' checked = "checked"';
                        $style = 'visibility:visible';
                    } else {
                        $checked = '';
                        $style = 'visibility:hidden';
                    }
                }
            }

            if (is_array($returnAs)) {
                $options[] = "\n<div class='panel panel-default'><div class='panel-body'>Hi </div></div>";
                //$options[] = "\n<label class=\"checkbox-inline\">\n<input type=\"checkbox\" name=\"$fieldName\" id=\"$name\" value=\"$id\"{$checked}> $name \n</label></div><div class=\"col-lg-6\">Installment Details</div><br>";
            } else {
                $options .= "<div class='panel panel-default'>
                                <div class='panel-body'>
                                    <div class=\"col-lg-4\">
                                        <label  class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$fieldName\" id=\"feerule-$id\" value=\"$id\"{$checked} onchange=\"showInstallments(this.id);\"> $name
                                    </div> 
                                <div class=\"col-lg-8\" id=\"feeinst-$id\" style=\"$style\">";


                foreach ($feeInstallments as $key1 => $value1) {
                    $instChecked = checkInstallmentAssoc($studentId, $id, $value1['duedate']);

                    if ($instChecked) {
                        $instCheckedAtt = "checked='checked'";
                    } else {
                        $instCheckedAtt = '';
                    }
                    $installmentNo = rtrim(getInstallmentNumber('', $value1['duedate']), ',');
                    $options .= "<span class=\"text-success\"><input name=\"feeInst[$id][]\" type=\"checkbox\" value=\"$value1[duedate]\" class=\"checkbox-inline\" $instCheckedAtt /> <b>$installmentNo</b> </span>";
                }

                $options .= '</div></div></div>';
                //  $options .= "\n <label class=\"checkbox-inline\">\n<input type=\"checkbox\" name=\"$fieldName\" id=\"$name\" value=\"$id\"{$checked}> $name \n</label><br>";
            }
            ++$x;
        }

        return $options;
    }
}

function checkInstallmentAssoc($studentId, $feeRuleId, $installment) {
    $sqlInstCheck = " SELECT installment,status 
          FROM tblstudfeeruleassoc as t1, 
          tblstudfeeruleinstasssoc as t2 
         WHERE t1.studfeeruleassocid=t2.studfeeruleassocid 
         AND t1.studentid = $studentId 
         AND feeruleid = $feeRuleId 
        AND associationstatus=1 
        AND t2.installment = '$installment' ";

    $resSqlInstCheck = dbSelect($sqlInstCheck);

    if (mysqli_num_rows($resSqlInstCheck) > 0) {
        $rowInstAssoc = mysqli_fetch_assoc($resSqlInstCheck);

        return $rowInstAssoc['status'];
    } else {
        return 0;
    }
}

function echoThis($val) {
    echo "<pre>---------------------\n\n";
    print_r($val);
    echo "\n\n---------------------</pre>";
}

function randUsername() {
    return str_shuffle('myemailaccount') . '@gmail.com';
}

function createPassword() {
    return encryptIt('blah');
}

/* render the classes, along with amount and due date to be used in feeMaster */

function feeMasterClasses() {
    $result = populateCheckBox('classname', 'classname[]', '', array());
    $dblQuote = '"';
    $strReturn = '';
    if ($result) {
        foreach ($result as $item) {
            $strReturn .= "                
                            <div class={$dblQuote}row{$dblQuote}>
                            <div class={$dblQuote}col-lg-2{$dblQuote}>
                              <label for={$dblQuote}classname{$dblQuote}>Class</label><br>$item</div>
                                  <div class={$dblQuote}col-lg-3{$dblQuote}>
                                  <label for={$dblQuote}amount{$dblQuote}> Amount </label>
                                  <input type={$dblQuote}text{$dblQuote} name={$dblQuote}amount[]{$dblQuote} id={$dblQuote}amount{$dblQuote} class={$dblQuote}form-control{$dblQuote} >

                                   <div class={$dblQuote}hidden{$dblQuote} id={$dblQuote}divamount{$dblQuote}><code>Amount per date in Rupees is required.</code></div>
                              </div>

                            <div class={$dblQuote}col-lg-4{$dblQuote}>
                                <label for={$dblQuote}duedate{$dblQuote}> Due Date(s) </label>
                                <input type={$dblQuote}text{$dblQuote} name={$dblQuote}duedate[]{$dblQuote} id={$dblQuote}duedate{$dblQuote}  >

                                <div class={$dblQuote}hidden{$dblQuote} id={$dblQuote}divduedate{$dblQuote}><code>Due date of payment is required.</code></div>
                            </div>
                            </div>

                            ";
        }
    }

    return $strReturn;
}

/* converts a string containing datestring in dd/mm/yyyy into mysql acceptable date as yyyy/mm/dd and return the same */

function convertDate($dateString) {   //echo(date("d/m/Y", strtotime($dateString))); die;
    $date = DateTime::createFromFormat('d/m/Y', $dateString);
    return $date->format('Y-m-d');
}

function getValidateDate($dateString) {
    $date = DateTime::createFromFormat('Y-m-d', $dateString);

    return $date->format('d-m-Y');
}

/* render the classes, along with amount and due date to be used in feeMaster */

function getJSON($name) {
    $sql = returnSql($name);
    $strReturn = array();

    $result = dbSelect($sql[2]);
    while ($r = mysqli_fetch_assoc($result)) {
        $strReturn[] = $r;
    }

    return json_encode($strReturn);
}

function getSelectizeData($dataDetails) {
    $data = array();
    $data['feeMaster'] = array(
        'dataName' => 'classname',
        'valueField' => 'classid',
        'labelField' => 'classname',
        'searchField' => 'classdisplayname',
        'selectizeFieldName' => '#classname',
        'addInputize' => '#duedate,#amount',
    );

    $data['classMaster'] = array(
        'dataName' => 'selectizeClassName',
        'valueField' => 'classid',
        'labelField' => 'classname',
        'searchField' => 'classdisplayname',
        'selectizeFieldName' => '#classname',
        'addInputize' => '#classname,#sectionname,#subjectid,#examstartdate,#examenddate',
    );
    
     $data['classStructure'] = array(
        'dataName' => 'selectizeClassName',
        'valueField' => 'classid',
        'labelField' => 'classname',
        'searchField' => 'classdisplayname',
        'selectizeFieldName' => '#classname',
        'addInputize' => '#classname,#sectionname,#subjectid,#examdate,#marks,#examname',
    );

    $data['addRoute'] = array(
        'dataName' => 'pickuppointname',
        'valueField' => 'pickuppointid',
        'labelField' => 'pickuppointname',
        'searchField' => 'pickuppointname',
        'selectizeFieldName' => '#pickuppointname', 'addInputize' => '',
    );

    if ((!empty($dataDetails)) && (array_key_exists($dataDetails, $data))) {
        return $data[$dataDetails];
    } else {
        return null;
    }
}

function initSelectize($dataDetails) {

    $dataDetails = getSelectizeData($dataDetails);

    if ($dataDetails !== null) {
        foreach ($dataDetails as $key => $value) {
            ${$key} = $value;
        }
        $data = getJSON($dataName);

        echo <<<JS
        \n  
                <script type="text/javascript">
            function addSelectize(num) {
                var data = $data; 
                var json = [];
                json.push(num);
                $(document).ready(function () {
                    $.each(json, function (index, value) {
                        $(value).selectize({
                            hideSelected: true,
                            persist: false,
                            duplicates: true,
                            delimiter: ',',
                            options: data,
                            valueField: '$valueField',
                            labelField: '$labelField',
                            searchField: '$searchField',
                            create: false                    
                        });

                    });
                });
            }
            \n
JS;

        //DELETE SHORTLY 
        //$addInputize = "";
        if ($addInputize !== '') {
            initInputize();
            echo "\n addInputize('$addInputize'); ";
        }

        echo "
	    \n 
	      addSelectize('$selectizeFieldName');       
		\n </script>";
    }
}

function initInputize() {
    echo <<<JS
\n
        function addInputize(num){
            var json = [];
            json.push(num);
            $(document).ready(function () {
            $.each(json, function (index, value) {
            $(value).selectize({
                       delimiter: ',',
                       duplicates: true,
                        create: function (input) {
                            return {
                                value: input,
                                text: input
                            }
                        }
                    });
                });
            });    
        }
    \n
JS;
}

function monthRangeSelect($name = 'month', $selected = null) {
    $academicsessionid = $_SESSION['academicsessionid'];
    $sql = "SELECT `sessionstartdate`, `sessionenddate` FROM `tblacademicsession` WHERE `academicsessionid` = $academicsessionid ";
    $result = dbSelect($sql);
    $row = mysqli_fetch_assoc($result);
    $sessionStart = date('Y', strtotime($row['sessionstartdate']));
    $sessionEnd = date('Y', strtotime($row['sessionenddate']));

    $dd = '<select class="form-control" name="' . $name . '" id="' . $name . '">';

    $months = array(
        0 => '-Select One-',
        1 => 'january',
        2 => 'february',
        3 => 'march',
        4 => 'april',
        5 => 'may',
        6 => 'june',
        7 => 'july',
        8 => 'august',
        9 => 'september',
        10 => 'october',
        11 => 'november',
        12 => 'december',
    );
    /*     * * the current month ** */
    $selected = is_null($selected) ? date('n', time()) : $selected;

    for ($i = 0; $i <= 12; ++$i) {
        if ($i >= 4 && $i <= 9) {
            $dd .= "<option value=\"{$sessionStart}-0{$i}-01\" ";
        } elseif ($i >= 10 && $i <= 12) {
            $dd .= "<option value=\"{$sessionStart}-{$i}-01\" ";
        } elseif ($i >= 1 && $i <= 3) {
            $dd .= "<option value=\"{$sessionEnd}-0{$i}-30\" ";
        } elseif ($i == 0) {
            $dd .= '<option value=0';
        }
        if ($i == $selected) {
            $dd .= ' selected';
        }
        /*         * * get the month ** */
        $dd .= '>' . $months[$i] . '</option>';
    }
    $dd .= '</select>';

    return $dd;
}

function sendMessage($details) {

    //Your authentication key
    $authKey = '79906AZaSpnKoh3G154e71a61';

    //Multiple mobiles numbers separated by comma
    $mobileNumber = $details['sendernumber'];

    //Sender ID,While using route4 sender id should be 6 characters long.
    $senderId = '777777';

    //Your message to send, Add URL encoding here.
    $message = urlencode($details['message']);

    //Define route
    $route = 'default';
    //Prepare you post parameters
    $postData = array(
        'authkey' => $authKey,
        'mobiles' => $mobileNumber,
        'message' => $message,
        'sender' => $senderId,
        'route' => $route,
    );

    //API URL
    $url = 'http://api.msg91.com/sendhttp.php';

    // init the resource
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
            //,CURLOPT_FOLLOWLOCATION => true
    ));

    //Ignore SSL certificate verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    //get response
    $output = curl_exec($ch);

    //Print error if any
    if (curl_errno($ch)) {
        echo 'error:' . curl_error($ch);
    }

    curl_close($ch);
    if (!empty($output)) {
        return true;
    } else {
        return false;
    }
}

function sendEmail($details) {
    if (!empty($details)) {
        $to = $details['recieveremail'];
        $subject = $details['subjectinfo'];
        $message = $details['message'];
        $from = $details['senderemail'];
    }

    if (mail($to, $subject, $message, $from)) {
        return true;
    } else {
        return false;
    }
}

// function for generating the New Receipt Number for Fee Collection //
// function for generating the New Receipt Number for Fee Collection //

function GenerateRecieptNumber($instituteabbr, $sessionname) {

    // Declaring & Initialising a variable with a 6 digit number used for first collection for initiating the reciept No.

    $number = 111110;

    $sql = 'SELECT  `receiptid` FROM `tblfeecollection` 
             WHERE `feecollectionid` = (SELECT max(`feecollectionid`) FROM `tblfeecollection`)';

    $result = dbSelect($sql);

    if (mysqli_num_rows($result) > 0) {
        $fetchRecieptNo = mysqli_fetch_assoc($result);
        if ($fetchRecieptNo['receiptid'] == null || empty($fetchRecieptNo['receiptid'])) {
            $newRecieptNo = 111111;
        } else {
            $recieptArray = explode('/', $fetchRecieptNo['receiptid']);
            $newRecieptNo = $recieptArray[2] + 1;
        }
        $newRecieptNo = $instituteabbr . '/' . $sessionname . '/' . $newRecieptNo;

        return $newRecieptNo;
    } else {
        $newRecieptNo = $instituteabbr . '/' . $sessionname . '/' . 111111;

        return $newRecieptNo;
    }
}

// This function Fee Reciept No For T.C purpose
function GenerateTCRecieptNumber() {
    $sql = 'SELECT  `recieptno` FROM `tblstudtc` WHERE `tcid` = (SELECT max(`tcid`) FROM  `tblstudtc`) ';

    $result = dbSelect($sql);

    if (mysqli_num_rows($result) > 0) {
        $fetchRecieptNo = mysqli_fetch_assoc($result);
        if ($fetchRecieptNo['recieptno'] == null || empty($fetchRecieptNo['recieptno'])) {
            $newRecieptNo = 111111;
        } else {
            $recieptArray = explode('/', $fetchRecieptNo['recieptno']);
            $newRecieptNo = $recieptArray[3] + 1;
        }

        return $newRecieptNo;
    } else {
        $newRecieptNo = 111111;

        return $newRecieptNo;
    }
}

// This function Fee Reciept No For Refund  purpose
function GenerateRefundReciept() {
    $sql = 'SELECT  `feerefundrecieptno` FROM `tblfeerefund` wHERE `feerefundid` = (SELECT max(feerefundid) FROM `tblfeerefund`) ';
    $result = dbSelect($sql);

    if (mysqli_num_rows($result) > 0) {
        $fetchRecieptNo = mysqli_fetch_assoc($result);

        if ($fetchRecieptNo['feerefundrecieptno'] == null || empty($fetchRecieptNo['feerefundrecieptno'])) {
            $newRecieptNo = 111111;
        } else {
            $recieptArray = explode('/', $fetchRecieptNo['feerefundrecieptno']);
            $newRecieptNo = $recieptArray[3] + 1;
        }

        return $newRecieptNo;
    } else {
        $newRecieptNo = 111111;

        return $newRecieptNo;
    }
}

// This function converts the Amount (Indian Currency) into Words (4566=> Four Thousand five hundred fifty six)

function convertNum2Words($no) {
    $words = array('0' => '', '1' => 'one', '2' => 'two', '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six', '7' => 'seven', '8' => 'eight', '9' => 'nine', '10' => 'ten', '11' => 'eleven', '12' => 'twelve', '13' => 'thirteen', '14' => 'fourteen', '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen', '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty', '30' => 'thirty', '40' => 'fourty', '50' => 'fifty', '60' => 'sixty', '70' => 'seventy', '80' => 'eighty', '90' => 'ninty', '100' => 'hundred ', '1000' => 'thousand', '100000' => 'lakh', '10000000' => 'crore');

    if ($no == 0) {
        return ' ';
    } else {
        $novalue = '';
        $highno = $no;
        $remainno = 0;
        $value = 100;
        $value1 = 1000;
        while ($no >= 100) {
            if (($value <= $no) && ($no < $value1)) {
                $novalue = $words["$value"];
                $highno = (int) ($no / $value);
                $remainno = $no % $value;
                break;
            }
            $value = $value1;
            $value1 = $value * 100;
        }
        if (array_key_exists("$highno", $words)) {
            return ucwords($words["$highno"] . ' ' . $novalue . ' ' . convertNum2Words($remainno));
        } else {
            $unit = $highno % 10;
            $ten = (int) ($highno / 10) * 10;

            return ucwords($words["$ten"] . ' ' . $words["$unit"] . ' ' . $novalue . ' ' . convertNum2Words($remainno));
        }
    }
}

/* * *****************************************************************
 * writeToFile function, simple function which takes file path and the data to write to it
 * return count, number of rows, once done.
 * ****************************************************************** */

function writeToFile($file, $data) {

    //echoThis($data); die;
    $fp = fopen($file, 'w');
    $cnt = 0;
    foreach ($data as $rows) {
        fputcsv($fp, array($rows));
        ++$cnt;
    }
    fclose($fp);

    return $cnt;
}

function sqlRowCount($dbObj) {
    
    $rowCount = mysqli_num_rows($dbObj);
    echoThis($rowCount);die;
    return $rowCount;
}

/* * **************************************************************************************************
 *  Function is to check and load previously saved cookie data into session variables after
 * validating login credentials. If the cookies exists over any system it will executes automatically
 * Written by : Abhishek K. Sharma
 * *************************************************************************************************** */

function loadCookie() {

    if (isset($_COOKIE[COOKIE_NAME])) {

        $cookieData = explode('&', $_COOKIE[COOKIE_NAME]);
        $cookieDataUser = explode('=', $cookieData[0]);
        $cookieDataPass = explode('=', $cookieData[1]);

        $sql = 'SELECT userid,username,password,roleid FROM tbluser WHERE userid=' . $cookieDataUser[1];

        if (sqlRowCount($sql) > 0) {
            $result = mysqli_fetch_assoc(dbSelect($sql));
        }

        if (strcmp($result['password'], $cookieDataPass[1])) {
            session_start();
            $_SESSION['userid'] = $result['userid'];
            $_SESSION['login'] = encryptIt($result['username']);
            $_SESSION['userGroup'] = $result['roleid'];
            $_SESSION['session_name'] = getSessionName();

            if (logUser($result['userid'], session_id(), $_SERVER['REMOTE_ADDR'], 'INSERT')) {
                header('Location: /360/files/dashboard.php?t=' . serialize(time()));
            } else {
                addError(0, null, '/360/index.php');
            }
        }
    }
}


/* this function maintain the log of user logged in or logged out */

function logUser($action) {
     
    /* get user id from the session */
    $userid = $_SESSION['userid'];   
    /* get ip address of logged in system */
    $ip = $_SERVER['REMOTE_ADDR'];
  
    /* insert query , executed when user is logged in */
    $Insert = "INSERT INTO tbluserlogged SET userid = '$userid', phpsessid = '".session_id()."', 
                ip_address = '$ip', logged_in = CURRENT_TIMESTAMP"; 
    
    /* update query executed when used logged out form the system */
    $Update = "UPDATE tbluserlogged SET logged_out = NOW() WHERE userid = '$userid' AND phpsessid = '". session_id()."'";
    
    /* assigning function name dynamically to the variable 
     * like dbUpdate / dbInsert 
     */
    $doAction = "db$action"; 
    
    /* passing values to the function dynamically */
    $doSql = "${$action}";  
 
    /* call_user_func call the function dynamically 
     * first parameter function name and second parameter is the function values 
     */
    return call_user_func($doAction, $doSql);
}


/* * ******************************************************************************************
 *  Function to extract the maximum id or last inserted record id from the specified table.
 * function required to parameters first one is table name and then second one is coloumn name
 * Created by : Abhishek K. Sharma  Dated : 12-SEP-2015
 * ****************************************************************************************** */

function getMaxId($tblName, $colName) {
    $sqlSelect = mysqli_fetch_assoc(dbSelect('SELECT max(' . $colName . ') AS maxid FROM ' . $tblName));
    if ($sqlSelect['maxid'] <= 0) {
        $sqlSelect['maxid'] = 1;
    }

    return $sqlSelect['maxid'];
}

/* * ***********************************************************************************************
 * Function for pagination with Ajax  functionality. it return the set of anchored page nos with ajax
 * call
 * Written By : Abhishek K. Sharma  Date : 18-SEP-2015 *
 * ************************************************************************************************ */

function getPaginationAJAX($count, $currentPage) {
    $page = $currentPage;
    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * 5;
    $adjacents = '2';
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($count / ROW_PER_PAGE);
    $lpm1 = $lastpage - 1;
    $pagination = '';

    if ($lastpage > 1) {
        $pagination .= "<div id='pagination' style='float:right'><ul class='pagination pagination-lg'>";
        if ($page > 1) {
            $pagination .= '<li><a href="#Page=' . ($prev) . "\" onClick='changePagination(" . ($prev) . ");'>" . '&laquo; Previous&nbsp;&nbsp;</a></li>';
        } else {
            $pagination .= "<li><span class='disabled'>&laquo; Previous&nbsp;&nbsp;</span></li>";
        }
        if ($lastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $lastpage; ++$counter) {
                if ($counter == $page) {
                    $pagination .= "<li><span class='current'>$counter</span></li>";
                } else {
                    $pagination .= '<li><a href="#Page=' . ($counter) . "\" onClick='changePagination(" . ($counter) . ");'>$counter</a></li>";
                }
            }
        } elseif ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); ++$counter) {
                    if ($counter == $page) {
                        $pagination .= "<span class='current'>$counter</span>";
                    } else {
                        $pagination .= '<li><a href="#Page=' . ($counter) . "\" onClick='changePagination(" . ($counter) . ");'>$counter</a></li>";
                    }
                }

                $pagination .= '...';
                $pagination .= '<li><a href="#Page=' . ($lpm1) . "\" onClick='changePagination(" . ($lpm1) . ");'>$lpm1</a></li>";
                $pagination .= '<li><a href="#Page=' . ($lastpage) . "\" onClick='changePagination(" . ($lastpage) . ");'>$lastpage</a></li>";
            } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<li><a href=\"#Page=\"1\"\" onClick='changePagination(1);'>1</a></li>";
                $pagination .= "<li><a href=\"#Page=\"2\"\" onClick='changePagination(2);'>2</a></li>";
                $pagination .= '...';
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; ++$counter) {
                    if ($counter == $page) {
                        $pagination .= "<span class='current'>$counter</span>";
                    } else {
                        $pagination .= '<li><a href="#Page=' . ($counter) . "\" onClick='changePagination(" . ($counter) . ");'>$counter</a></li>";
                    }
                }
                $pagination .= '..';
                $pagination .= '<li><a href="#Page=' . ($lpm1) . "\" onClick='changePagination(" . ($lpm1) . ");'>$lpm1</a></li>";
                $pagination .= '<li><a href="#Page=' . ($lastpage) . "\" onClick='changePagination(" . ($lastpage) . ");'>$lastpage</a></li>";
            } else {
                $pagination .= "<li><a href=\"#Page=\"1\"\" onClick='changePagination(1);'>1</a></li>";
                $pagination .= "<li><a href=\"#Page=\"2\"\" onClick='changePagination(2);'>2</a></li>";
                $pagination .= '..';
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; ++$counter) {
                    if ($counter == $page) {
                        $pagination .= "<span class='current'>$counter</span>";
                    } else {
                        $pagination .= '<li><a href="#Page=' . ($counter) . "\" onClick='changePagination(" . ($counter) . ");'>$counter</a></li>";
                    }
                }
            }
        }
        if ($page < $counter - 1) {
            $pagination .= '<li><a href="#Page=' . ($next) . "\" onClick='changePagination(" . ($next) . ");'>Next &raquo;</a></li>";
        } else {
            $pagination .= "<li><span class='disabled'>Next &raquo;</span></li>";
        }

        $pagination .= '</div>';
    }

    return $pagination;
}

/* * *****************************************************************************
 * Function for generating page nos with anchor link over the page, it requires
 * following parameters :
 *  $count              = Total no of records / rows / data
 *  $recordsPerPage     = Desired no of records over the page
 * Written by : Abhishek K. Sharma Dated : 02-OCT-2015
 * ***************************************************************************** */

function getPagination($count, $recordsPerPage) {
    require_once 'Pagination.class.php';

    $page = isset($_GET['page']) ? ((int) $_GET['page']) : 1;

    // instantiate; set current page; set number of records
    $pagination = (new Pagination());
    $pagination->setCurrent($page);
    $pagination->setTotal($count);

    // grab rendered/parsed pagination markup
    $markup = $pagination->parse();
    echo $markup;
}

function checkUserGroup() {
    if (isset($_SESSION['userGroup'])) {
        if ($_SESSION['userGroup'] != 3) {
            return false;
        } else {
            return true;
        }
    }
}

/* * *******************************************************************************************
 * Function for updating record status in database. User can make active or inactive of any records they want.
 * Written by : Abhishek K. Sharma
 * ******************************************************************************************* */

function statusUpdate($tblName, $currentState, $condition) {
    if ($currentState == 0) {
        $stateUpdate = 1;
    } else {
        $stateUpdate = 0;
    }

    $statusString = 'UPDATE ' . cleanVar($tblName) . ' SET status=' . cleanVar($stateUpdate) . ' , dateupdated=CURRENT_TIMESTAMP WHERE ' . $condition;
    $result = dbUpdate($statusString) or die('Update Error');

    if ($result) {
        return true;
    } else {
        return false;
    }
}

/* * *****************************************************************************
 * Function for uploading of Image and Documents within the application, it requires
 * the following parameters
 *  $imgArray    = $_FILES global array post with the form
 *  $imgPath     = Full path where to upload
 *  $imgName     = Desired FILENAME after uploading
 *  $type        = Type of uploaded item like image or document
 * Written by : Abhishek K. Sharma Dated : 02-OCT-2015
 * ***************************************************************************** */

function uploadImage($imgArray, $imgPath, $imgName, $type) {
    if (isset($imgArray)) {
        $fieldName = array_keys($imgArray);

        $extValidate = validFileExtension($imgArray[$fieldName[0]]['name'], $type);
        $sizeValidate = validFileSize($imgArray[$fieldName[0]]['tmp_name'], $type);

        if ($extValidate) {
            if ($sizeValidate) {
                $uploadImgName = $imgPath . $imgName;
                $copyResult = copy($imgArray[$fieldName[0]]['tmp_name'], $uploadImgName);
                if (!$copyResult) {
                    addError(14, 'Image');

                    return false;
                } else {
                    return $imgName . '.' . $extValidate;
                }
            } else {
                addError(13, $imgArray[$fieldName[0]]);

                return false;
            }
        } else {
            addError(12, $imgArray[$fieldName[0]]);

            return false;
        }
    }
}

/* * *****************************************************************************
 * Function for validating the file extension within the application, generally it
 * called from the uploadImage function and also can use separately, it requires
 * the following parameters
 *  $fileName    = Name of the desired file which extension to be checked
 *  $type        = Type of the file whethere it is image or document
 * Written by : Abhishek K. Sharma Dated : 02-OCT-2015
 * ***************************************************************************** */

function validFileExtension($fileName, $type) {
    $imgExt = strtolower(substr($fileName, (int) strpos($fileName, '.') + 1, 4));
    switch ($type) {
        case 'image': $imgExtArray = array('jpg', 'jpeg', 'gif', 'bmp', 'png', 'tif');
            break;
        case 'document': $imgExtArray = array('pdf', 'doc', 'docx', 'jpeg', 'jpg', 'gif');
            break;
        default: $imgExtArray = array('jpg', 'jpeg', 'gif');
            break;
    }

    if (in_array($imgExt, $imgExtArray)) {
        return $imgExt;
    } else {
        return 0;
    }
}

/* * *****************************************************************************
 * Function for validating the file size within the application, generally it
 * called from the uploadImage function and also can use separately, it requires
 * the following parameters
 *  $fileName    = Name of the desired file which size to be checked
 *  $type        = Type of the file whethere it is image or document
 * Written by : Abhishek K. Sharma Dated : 02-OCT-2015
 * ***************************************************************************** */

function validFileSize($fileName, $type) {
    if (isset($fileName)) {
        switch ($type) {
            case 'image': $validSize = 307200;
                break;
            case 'document': $validSize = 5242880;
                break;
            default: $validSize = 102400;
                break;
        }

        $fileSize = filesize($fileName);
        if ($fileSize <= $validSize) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getClassSectionAssocId($classId, $sectionid) {
    if (isset($classId) && isset($sectionid)) {
        $sqlString = "SELECT clsecassocid FROM tblclsecassoc WHERE classid='" . $classId . "' AND sectionid='" . $sectionid . "'";
        $sqlResult = dbSelect($sqlString);
        if ($sqlResult) {
            $row = mysqli_fetch_assoc($sqlResult);

            return $row['clsecassocid'];
        } else {
            return false;
        }
    }

    return false;
}

//Function to convert a given amount in proper Indian currency order
// returns the value along with rupees sign. 
// DEVELOPMENT NOTE: 
// Need to revisit for future deverlopment! 
//

function formatCurrencypdf($val, $symbol = '&#x20B9;', $r = 2) {
    $symbol = "<p style='font-family: Ruppe;'>`";
    $n = $val;
    $c = is_float($n) ? 1 : number_format($n, $r);
    $d = '.';
    $t = ',';
    $sign = ($n < 0) ? '-' : '';

    $i = $n = number_format(abs($n), $r);
    $j = (($j = strlen($i)) > 3) ? $j % 3 : 0;

    $currency = $symbol . ' ' . $sign . ($j ? substr($i, 0, $j) + $t : '') . preg_replace('/(\d{3})(?=\d)/', "$1" + $t, substr($i, $j)) . "</p>";

    return $currency;
}

function formatCurrency($val, $symbol = '&#x20B9;', $r = 2) {

    $n = $val;
    $c = is_float($n) ? 1 : number_format($n, $r);

    $d = '.';
    $t = ',';
    $sign = ($n < 0) ? '-' : '';

    $i = $n = number_format(abs($n), $r);

    $j = (($j = strlen($i)) > 3) ? $j % 3 : 0;

    $currency = $symbol . ' ' . $sign . ($j ? substr($i, 0, $j) + $t : '') . preg_replace('/(\d{3})(?=\d)/', "$1" + $t, substr($i, $j));

    return $currency;
}

function renderHeaderLinks($roleType) {
    $menu = array('Master' => array(
            'Institute' => 'addInstitute.php',
            'Add User' => 'addUser.php',
            'Academic Year' => 'addAcademicYear.php',
            'Class Master' => 'classMaster.php',
            'Class Structure' => 'classStructure.php',
            'Subject' => 'addSubject.php',
            'Collection' => 'collectionType.php',
            'User' => 'User.php',
            'Fees' => 'feeMaster.php',
            'Fee Rule' => 'feeRule.php',
            'Other Fee' => 'otherFees.php',
            'Class' => 'classMaster.php',
        ),
        'Student' => array(
            'Student' => 'studentDashboard.php',
            'Create Student' => 'studentPersonal.php?mode=complete',
            'Quick Registration' => 'quickStudent.php',
        ),
        'Transport' => array(
            'Vehicle Dashboard' => 'vehicleDashboard.php',
            'Vehicle' => 'addVehicle.php',
            'Driver' => 'addDriver.php',
            'Pick Up Point' => 'addPickUpPoint.php',
            'Route' => 'addRoute.php',
            'Mileage Entry' => 'mileageEntry.php',
            'Fuel Entry' => 'fuelEntry.php'
        ),
        'Communication' => array(
            'Notification' => 'sendNotification.php',
        ),
        'Fees' => array(
            'Fee Collect' => 'feeCollection.php',
            'Cheque Management' => 'chequemanagement.php',
            'Bank Fees' => 'bankTransactions.php',
        ),
        'Reports' => array(
            'Daily Report' => 'dailyReport.php',
            'Fee Due' => 'feeDueIndex.php',
            'Fee Collected' => 'collectedFeeIndex.php',
            'Transport' => 'studentTransportIndex.php',
            'TC Issued' => 'studentTCReport.php',
            'Fee Refund' => 'feeRefund.php',
            'Bank CSV' => 'bankcsvreport.php',
            'Student Status' => 'studentstatus.php',
            'Student Fee Rule' => 'studentfeerulereport.php',
            'Adjusted Fee Report' => 'adjustedFeeReport.php',
        ),
        'Student Services' => array('TC' => 'issueTC.php'),);
    $navbarIcons = array(
        'Master' => 'admin-icon.jpg',
        'Student' => 'student-icon.jpg',
        'Transport' => 'transport-icon.jpg',
        'Communication' => 'notifications-icon.jpg',
        'Fees' => 'feecollection-icon.jpg',
        'Reports' => 'report-icon.jpg',
        'Student Services' => 'student-services-icon.jpg',);
    $role = array(
        'Admin' => array('Master' => 'Add User,Institute,Academic Year,Class Master,Class Structure,Subject,Collection,Fees,Fee Rule,Other Fee',
            'Student' => 'Student,Create Student,Quick Registration',
            'Transport' => 'Mileage Entry,Fuel Entry,Vehicle Dashboard,Vehicle,Driver,Pick Up Point,Route',
            'Fees' => 'Fee Collect,Cheque Management,Bank Fees',
            'Reports' => 'Daily Report,Fee Due,Fee Collected,Transport,TC Issued,Fee Refund,Bank CSV,Student Status,Student Fee Rule,Adjusted Fee Report',
            'Student Services' => 'TC',),
        'Front' => array('Master' => 'Add User,Collection,Fees,Fee Rule,Other Fee',
            'Student' => 'Student,Create Student,Quick Registration',
            'Transport' => 'Mileage Entry,Fuel Entry,Vehicle Dashboard,Vehicle,Driver,Pick Up Point,Route',
            'Fees' => 'Fee Collect,Cheque Management,Bank Fees',
            'Reports' => 'Daily Report,Fee Due,Fee Collected,Transport,TC Issued,Fee Refund,Bank CSV,Student Status,Student Fee Rule,Adjusted Fee Report',
            'Student Services' => 'TC',),
        'Student' => array('Student' => 'Student'),
    );
    switch ($roleType) {
        case 3:
            $type = 'Admin';
            break;
        case 2:
            $type = 'Front';
            break;
        case 1:
            $type = 'Student';
            break;
        default:
            $type = 'Student';
            break;
    }
    $topLinks = array_keys($role[$type]);
    $navBar = ' <ul class="nav navbar-nav">
                    <li style="text-align: center"> 
                        <a href="#about"><img  src="' . DIR_ASSET . '/images/about-icon.jpg" alt="About Us" width="50"></a>
                    </li>
                    
                    <li style="text-align: center" >
                        <a href="#contact"><img src="' . DIR_ASSET . '/images/contact-icon.jpg" alt="Contact Us" width="50"></a>
                   </li>';
    foreach ($topLinks as $key => $value) {
        if ($value != 'Reports' && $value != 'Student Services') {
            $filePath = DIR_BASE . 'files/';
        } else {
            $filePath = DIR_BASE . '';
        }
        $innerLinks = explode(',', $role[$type][$value]);
        $iconPath = DIR_ASSET . '/images/' . $navbarIcons[$value];
        $navBar .= ' <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="' . $iconPath . '" alt="Notification" width="50">
                    </br><center><span id = "caretmenu" class="caret"></span></center></a>
                    <ul class="dropdown-menu" role="menu">
                    ';
        foreach ($innerLinks as $innerKey => $innerValue) {
            $linkPath = $filePath . str_replace(' ', '', strtolower($value)) . '/' . $menu[$value][$innerValue];
            $navBar .= '<li><a href="' . $linkPath . '">' . $innerValue . ' </a></li>';
        }
        $navBar .= '</ul></li>';
    }
    $navBar .= '<li><a href="' . DIR_FILES . '/logout.php">
                <img src="' . DIR_ASSET . '/images/logout-icon.jpg" alt="Logout" width="50"></a></li>
              </ul>';
    return $navBar;
}

/*
 * This function converts integral values to its respective roman number
 */

function romanNumerals($num) {
    $n = intval($num);
    $res = '';

    /*     * * roman_numerals array  ** */
    $roman_numerals = array(
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,);

    foreach ($roman_numerals as $roman => $number) {
        /*         * * divide to get  matches ** */
        $matches = intval($n / $number);
        /*         * * assign the roman char * $matches ** */
        $res .= str_repeat($roman, $matches);
        /*         * * substract from the number ** */
        $n = $n % $number;
    }
    /*     * * return the res ** */
    return $res;
}

/*
 * This function return all prefix for appending with along using intssessassocid
 */

function scholarnoabbr() {
    $branchabbrev = array(
        '1' => 'CASSHAJ-',
        '2' => 'CACHBJ-',
        '3' => 'CARTJ-',
        '4' => 'CAPOJ-',
        '5' => 'CAPAJ-',
        '6' => 'CAKHJ-',
        '7' => 'CAPMJ-',
        '8' => 'CABNJ-',
    );

    if (array_key_exists($_SESSION['instsessassocid'], $branchabbrev)) {
        return $branchabbrev[$_SESSION['instsessassocid']];
    }
}

function getInstallmentNumber($classid, $duedate) {
    $intsessassocid = $_SESSION['instsessassocid'];
    $sql = "  SELECT DISTINCT(t2.duedate)
                FROM `tblfeestructure` as t1,
                `tblfeestructuredetails` as t2

                WHERE t1.instsessassocid = '$intsessassocid'
                AND t1.feestructureid = t2.feestructureid
                ";
    if (!empty($classid)) {
        $sql .= " AND t1.classid = '$classid'";
    }
    $i = 1;
    $returnStr = '';

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $installmentArray[$row['duedate']] = romanNumerals($i);
            ++$i;
        }
        if (is_array($duedate)) {
            foreach ($duedate as $key => $value) {
                if (array_key_exists($value, $installmentArray)) {
                    $returnStr .= $installmentArray[$value] . ',';
                }
            }
            //$returnStr = rtrim($returnStr, ",");
            return $returnStr;
        } elseif (is_string($duedate)) {
            $duedate = explode(',', $duedate);
            foreach ($duedate as $key => $value) {
                if (array_key_exists($value, $installmentArray)) {
                    $returnStr .= $installmentArray[$value] . ',';
                }
            }
            //$returnStr = rtrim($returnStr, ",");
            return $returnStr;
        } else {
            if (array_key_exists($duedate, $installmentArray)) {
                return $installmentArray[$duedate];
            }
        }
    }
}

/*
 * This function return the complete fee structure
 * for individual classe's along with the duedate and amount
 */

function getClassFeesStructure($month) {

    $where = '';
    if (!empty($month) && is_numeric($month)) {
        $where = " AND MONTH(t3.duedate) = '$month'";
    }

    $sql = "SELECT t1.classid, t1.classname, t4.feecomponent, t3.duedate, t3.amount

            FROM `tblclassmaster` as t1,
            `tblfeestructure` AS t2,
            `tblfeestructuredetails` AS t3,
            `tblfeecomponent` AS t4

            WHERE t1.classid =  t2.classid
            AND t2.instsessassocid = $_SESSION[instsessassocid]
            AND t2.feestructureid = t3.feestructureid
            AND t2.feecomponentid = t4.feecomponentid
            $where

            ORDER BY t1.classid, t4.feecomponentid, t3.duedate ASC
            ";

    $result = dbSelect($sql);
    $totalInstAmt = 0;
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $feedetails[$row['classname']][$row['duedate']][$row['feecomponent']] = $row['amount'];
        }

        return $feedetails;
    } else {
        return 0;
    }
}

function empMenu($roleType) {
    $menu = array(
        'Master' => array(
            'Institute' => '../../master/addInstitute.php',
            'Academic Year' => '../../master/addAcademicYear.php',
            'Collection' => '../../master/collectionType.php',
            'Departments' => 'addDepartment.php',
            'Add Positions' => 'addPositions.php',
            'Salary Structure' => 'salaryStructure.php',
        ),
        'Employee' => array(
            'Add Employee' => 'addEmployee.php',
            'Search Employee' => 'searchEmployee.php',
        ),
    );

    $navbarIcons = array(
        'Employee' => 'user-14.png',
        'Master' => 'settings-3.png',
    );

    $role = array(
        'Admin' => array(
            'Master' => 'Institute,Academic Year,Collection,Departments,Add Positions,Salary Structure',
            'Employee' => 'Add Employee,Search Employee',
        ),
    );

    switch ($roleType) {
        case 3:
            $type = 'Admin';
            break;
        case 2:
            $type = 'Front';
            break;
        case 1:
            $type = 'Student';
            break;
        default:
            $type = 'Student';
            break;
    }

    $topLinks = array_keys($role[$type]);
    $navBar = ' <ul class="nav navbar-nav">
                   
                    <li style="text-align: center"> 
                        <a href="#about"><img src="' . DIR_ASSET . '/images/aboutus.png" alt="About Us" width="50"></a>
                    </li>
                    
                    <li style="text-align: center" >
                        <a href="#contact"><img src="' . DIR_ASSET . '/images/message.png" alt="Contact Us" width="50"></a>
                   </li>';

    foreach ($topLinks as $key => $value) {
        if ($value != 'Reports' && $value != 'Student Services') {
            $filePath = DIR_BASE . 'files/employee/';
        } else {
            $filePath = DIR_BASE . '';
        }

        $innerLinks = explode(',', $role[$type][$value]);
        $iconPath = DIR_ASSET . '/images/' . $navbarIcons[$value];

        $navBar .= ' <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="' . $iconPath . '" alt="Notification" width="50">
                    </br><center><span class="caret"></span></center></a>
                    <ul class="dropdown-menu" role="menu"></li>
                    
                    ';

        foreach ($innerLinks as $innerKey => $innerValue) {
            $linkPath = $filePath . str_replace(' ', '', strtolower($value)) . '/' . $menu[$value][$innerValue];

            $navBar .= '<li><a href="' . $linkPath . '">' . $innerValue . ' </a></li>';
        }
        $navBar .= '</ul></li>';
    }
    $navBar .= '<li><a href="' . DIR_FILES . '/logout.php">
                <img src="' . DIR_ASSET . '/images/power.png" alt="Logout" width="50"></a></li>
              </ul>';

    return $navBar;
}

/* This function check the role of the user and
 * prevents unauthorized login to a certain using url
 * Made by : Sanjay Kumar Chaurasia
 * Date: 22 July 2016
 */

function checkRole($bcpage) {


    $roleType = $_SESSION['userGroup'];
    $bcpage = bcPage();

    switch ($roleType) {
        case 3:
            $type = 'Admin';
            break;
        case 2:
            $type = 'Front';
            break;
        case 1:
            $type = 'Student';
            break;
        default:
            $type = 'Student';
            break;
    }
    $menu = array('Master' => array(
            'Institute' => 'addInstitute.php',
            'Academic Year' => 'addAcademicYear.php',
            'Subject' => 'addSubject.php',
            'Class Master' => 'classMaster.php',
            'Class Structure' => 'classStructure.php',
            'Collection' => 'collectionType.php',
            'User' => 'User.php',
            'Fees' => 'feeMaster.php',
            'Fee Rule' => 'feeRule.php',
            'Other Fee' => 'otherFees.php',
            'Class' => 'classMaster.php',
        ),
        'Student' => array(
            'Student' => 'studentDashboard.php',
            'Create Student' => 'studentPersonal.php?mode=complete',
            'Quick Registration' => 'quickStudent.php',
        ),
        'Transport' => array(
            'Vehicle' => 'addVehicle.php',
            'Driver' => 'addDriver.php',
            'Pick Up Point' => 'addPickUpPoint.php',
            'Route' => 'addRoute.php',
        ),
        'Communication' => array(
            'Notification' => 'sendNotification.php',
        ),
        'Fees' => array(
            'Fee Collect' => 'feeCollection.php',
            'Cheque Management' => 'chequemanagement.php',
            'Bank Fees' => 'bankTransactions.php',
        ),
        'Reports' => array(
            'Fee Due' => 'feeDueIndex.php',
            'Fee Collected' => 'collectedFeeIndex.php',
            'Transport' => 'studentTransportIndex.php',
            'TC Issued' => 'studentTCReport.php',
            'Fee Refund' => 'feeRefund.php',
            'Bank CSV' => 'bankcsvreport.php',
            'Student Status' => 'studentstatus.php',
            'Student Fee Rule' => 'studentfeerulereport.php',
            'Adjusted Fee Report' => 'adjustedFeeReport.php',
        ),
        'Student Services' => array('TC' => 'issueTC.php'),);

    $navbarIcons = array(
        'Master' => 'admin-icon.jpg',
        'Student' => 'student-icon.jpg',
        'Transport' => 'transport-icon.jpg',
        'Communication' => 'notifications-icon.jpg',
        'Fees' => 'feecollection-icon.jpg',
        'Reports' => 'report-icon.jpg',
        'Student Services' => 'student-services-icon.jpg',);

    $role = array(
        'Admin' => array('Master' => 'Institute,Academic Year,Subject,Collection,Fees,Fee Rule,Other Fee',
            'Student' => 'Student,Create Student,Quick Registration',
            'Transport' => 'Vehicle,Driver,Pick Up Point,Route',
            'Fees' => 'Fee Collect,Cheque Management,Bank Fees',
            'Reports' => 'Fee Due,Fee Collected,Transport,TC Issued,Fee Refund,Bank CSV,Student Status,Student Fee Rule,Adjusted Fee Report',
            'Student Services' => 'TC',),
        'Front' => array('Master' => 'Collection,Fees,Fee Rule,Other Fee',
            'Student' => 'Student,Create Student,Quick Registration',
            'Transport' => 'Vehicle,Driver,Pick Up Point,Route',
            'Fees' => 'Fee Collect,Cheque Management,Bank Fees',
            'Reports' => 'Fee Due,Fee Collected,Transport,TC Issued,Fee Refund,Bank CSV,Student Status,Student Fee Rule,Adjusted Fee Report',
            'Student Services' => 'TC',),
        'Student' => array('Student' => 'Student'),
    );

    $flag = 0;
    foreach ($role[$type] as $key => $value) {
        $comp = explode(',', $value);
        foreach ($comp as $k => $val) {
            if ($menu[$key][$val] == $bcpage) {
                $flag = 1;
            }
        }
    }

    if ($flag != 1) {
        addError('2', '', 'dashboard.php');
        exit();
    }
}

// Call process function of respective page by including "processfunction.php"
// chdck by sanjay kumar

if (wasFormSubmit()) {
    include_once 'processFunctions.php';
}

/*
 * Function hover list to displaying more option like
 * Edit / delete / status on mouse hover on button
 * Made by: Sanjay Kumar
 * Date: 03 Sept 2016
 */

function hoverList($sid, $status, $id) {
    $pagename = basename($_SERVER['PHP_SELF']); /* get the name of the page from which function is called */
    $pagename = chop($pagename, '.php');      /* remove the .php part from the page name */

    if ($status == 1) {/* it shows the status, if status is 1 then icon will be of green else red */
        $style = 'style="color:#000"';
    } else {
        $style = 'style="color:red"';
    }

    $page = array(
        'addAcademicYear' => array(
            'Update' => "addAcademicYear.php?edid=$sid&mode=edit",
            'Delete' => "addAcademicYear.php?delid=$sid",
            'Status' => "addAcademicYear.php?status=$status&sid=$sid",
        ),
        'addSubject' => array(
            'Update' => "addSubject.php?edid=$sid&mode=edit",
            'Delete' => "addSubject.php?delid=$sid",
            'Status' => "addSubject.php?status=$status&sid=$sid",
        ),
        'collectionType' => array(
            'Add New Element' => "collectiontypeNew.php?edid=$sid&mode=add&type=head",
            'Update' => "collectionType.php?edid=$sid&mode=edit&type=head",
            'Delete' => "collectionType.php?delid=$sid&type=head&type=head&page=$id>",
            'Status' => "collectionType.php?status=$status&sid=$sid&type=head",
        ),
        'feeMaster' => array(
            'Update' => "feeMaster.php?&mode=edit&classid=$sid&feecomponentid=$status",
            'Delete' => "feeMaster.php?&mode=delete&classid=$sid&delid=$status&feestructureid=$id",
        ),
        'feeRule' => array(
            'Update' => "feeRule.php?edid=$sid",
            'Delete' => "feeRule.php?delid=$sid",
            'Status' => "feeRule.php?sid=$sid&status=$status",
        ),
        'otherFees' => array(
            'Update' => "otherFees.php?edid=$sid",
            'Delete' => "otherFees.php?delid=$sid",
            'Status' => "otherFees.php?status=$status&sid=$sid",
        ),
        'studentDashboard' => array(
            'Pay Fees' => "onclick=\"popUp('../fees/feeCollectionProcessing.php?studentid=$sid&pop-up=y')\"",
            'Update' => "studentPersonal.php?sid=$sid&mode=edit",
            'Status' => "studentDashboard.php?sid=$sid&status=$status&page=$id",
            'Delete' => "studentDashboard.php?delid=$sid&page=$id",
        ),
    );
    $icons = array(
        'Update' => 'fa fa-pencil-square-o fa-2x',
        'Delete' => 'fa fa-trash-o fa-2x',
        'Status' => 'fa fa-check-square-o fa-2x',
        'Pay Fees' => 'fa fa-inr fa-2x',
        'Add New Element' => 'fa fa-plus fa-2x'
    );

    $html = '<div class="hovereffect">
                <a href="#" onclick="return false;" class="button">More options</a>
                    <div class="overlay">
                        <p class="icon-links">';
    /* key is action type edit / delete */
    /* value is hyperlink to the action */
    foreach ($page[$pagename] as $key => $value) {
        if (strpos($value, 'popUp')) {
            $href = 'href="#" ' . $value;
        } else {
            $href = "href=\"$value\"";
        }
        $iconpath = $icons[$key];
        $html .= "<a $href>
            <button class=\"btn btn-sm btn-round\" style=\"background-color: #fff;\">            
                    <span class=\"$iconpath\" aria-hidden=\"true\" $style
                    data-toggle=\"tooltip\" title=\"$key\"></span></button></a>
    ";
    }
    $html .= ' </p> </div> </div> 
';

    return $html;
}

/* function to return the name of the page in breadcrumb
 * Made by: Sanjay Kumar
 * Date: 05 Sept 2016
 */

function bcPage() {
    $pagename = chop(basename($_SERVER['PHP_SELF']), '.php');
    $pages = array(
        'index' => 'Login',
        'dashboard' => 'Dashboard',
        'addInstitute' => 'Add Institute',
        'addAcademicYear' => 'Add Academic Year',
        'addSubject' => 'Add Subject',
        'collectionType' => 'Master Collection Type',
        'feeMaster' => 'Fee Structure',
        'feeRule' => 'Fee Rule',
        'otherFees' => 'Other Fees',
        'studentDashboard' => 'Student Dashboard',
        'studentPersonal' => 'Student Profile',
        'quickStudent' => 'Quick Registration',
        'vehicleDashboard' => 'Vehicle Dashboard',
        'mileageEntry' => 'Vehicle Mileage Entry',
        'fuelEntry' => 'Vehicle Fuel Entry',
        'addVehicle' => 'Add new Vehicle',
        'addDriver' => 'Driver Registration',
        'addPickUpPoint' => 'Add New Pickup Point',
        'addRoute' => 'Add Route',
        'feeCollection' => 'Fee Collection',
        'chequemanagement' => 'Cheque Management',
        'bankTransactions' => 'Bank Transaction',
        'dailyReport' => 'Daily Transactiion Report',
        'feeDueIndex' => 'Fee Due Report',
        'collectedFeeIndex' => 'Fee Collection Report',
        'studentTransportIndex' => 'Student Transport',
        'studentTCReport' => 'Student TC Report',
        'feeRefund' => 'Fee Refund Report',
        'bankcsvreport' => 'Bank CSV Report',
        'studentstatus' => 'Student Status Report',
        'studentfeerulereport' => 'Student Fee Rule Report',
        'adjustedFeeReport' => 'Adjusted Fee Report',
        'issueTC' => 'Issue TC',
        'studentParent' => 'Student Parent',
        'studentMedical' => 'Student Medical',
        'studentFees' => 'Student Fees Rule',
        'studentDocument' => 'Student Documents',
        'studentFeeDetails' => 'Student Fee Details',
        'feeCollectionProcessing' => 'Fee Collection Processing',
        'loadScholarData' => 0,
    );

    if (!isset($pages[$pagename])) {
        $pages[$pagename] = ' ';
    }

    return $pages[$pagename];
}

/* this function gives the all dates between
 * two dates 
 * 
 */

function createDateRangeArray($strDateFrom, $strDateTo) {
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    $aryRange = array();

    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

    if ($iDateTo >= $iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}

function displayGoogleGraph($data) {
    //echoThis($data);
    if (isset($data)) {
        /* $graphArray = array(
          "xData" =>Denotes values on x Axis,
          "yData" => Denotes values on y Axis,
          "xAxis" => "Date Range",
          "yAxis" => "Average [KM]",
          "title" => "Grapth Title",
          "entity" => "for which line");
         */
        $JS = "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
   <script type=\"text/javascript\">
     google.charts.load('current', {packages: ['corechart','line']});  
   </script>
<script language=\"JavaScript\">
function drawChart() {
   // Define the chart to be drawn.
   var data = new google.visualization.DataTable();
   data.addColumn('string', '$data[xAxis]');
   data.addColumn('number', '$data[entity]');
   data.addRows([
      ";
        foreach ($data['xData'] as $key => $value) {
            $JS .= '[' . $data['yData'][$key] . ',' . (int) $value . '],';
        }

        $JS .="]);
   
   // Set chart options
   var options = {
      chart: {
         title: '$data[title]',
         subtitle: ''
      },   
      hAxis: {
         title: '$data[xAxis]',         
      },
      
      vAxis: {
         title: '$data[yAxis]',        
      }, 
      
      'width':$data[width],
      'height':$data[height]
   };

   // Instantiate and draw the chart.
   var chart = new google.charts.Line(document.getElementById('container'));
   chart.draw(data, options);
}
google.charts.setOnLoadCallback(drawChart);
</script>
";
        return $JS;
    } else {
        return "<p class=\"h5\" style=\"color: red;text-align: center;\">Graph could not be loaded, incomplete data</p>";
    }
}

/* THIS FUNCTION GIVES THE CURRENT SESSION NAME LIKE 2016-17
 * USED IN BREADCRUM
 * MADE BY: Sanjay Kumar
 * Date: 13/10/2016
 */

function getSessionName() {
    $sql = " SELECT t3.sessionname

                from tbluser as t1,
                tblinstsessassoc as t2,
                tblacademicsession as t3

                where t1.instsessassocid = t2.instsessassocid AND
                 t2.academicsessionid = t3.academicsessionid AND
                 t1.instsessassocid = $_SESSION[instsessassocid] GROUP BY t3.academicsessionid";

    $result = dbSelect($sql);
    if (mysqli_num_rows($result) > 0) {
        while ($rows = mysqli_fetch_assoc($result)) {
            $sessionname = $rows;
        }
        return $sessionname['sessionname'];
    }
}

/* *************************************************************************
 * This function is used to round off the number to next five or previous 5
 * for ex: if number is 13 => 10, if number is 18 => 20
 * Made By; Sanjay Kumar Chaurasia
 * 
 * *************************************************************************/
function roundOff($number){
    /* find the reminder of the number, in case of 0, it will return 0 */
    $round = fmod($number, 5);
    
    // if reminder is 2.5 or > 2.5 then it round off to next 5
    if($round == 2.5 || $round > 2.5 ){
        return ceil($number / 5.0 ) * 5.0;
    }
    // if reminder is < 2.5 then it round off to previos 5
    else{
        return floor($number / 5.0 ) * 5.0;
    }
}
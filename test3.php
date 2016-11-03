<?php

  require_once "config/config.php";
  require_once DIR_FUNCTIONS;
  require_once VIEW_HEADER;
/*
$str = array(1,2,3);
$str = serialize($str);
$str = base64_encode($str);
$str = unserialize(base64_decode($str));
echoThis($str);
*/
 $errortype = array (
                E_ERROR              => 'Error',
                E_WARNING            => 'Warning',
                E_PARSE              => 'Parsing Error',
                E_NOTICE             => 'Notice',
                E_CORE_ERROR         => 'Core Error',
                E_CORE_WARNING       => 'Core Warning',
                E_COMPILE_ERROR      => 'Compile Error',
                E_COMPILE_WARNING    => 'Compile Warning',
                E_USER_ERROR         => 'User Error',
                E_USER_WARNING       => 'User Warning',
                E_USER_NOTICE        => 'User Notice',
                E_STRICT             => 'Runtime Notice',
                E_USER_DEPRECATED   => 'User Depricated Error',
                E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
                );
                echoThis($errortype);die;
?>
<?php 
  /* ------------------------------------------------------------------------- -- 
    function listFolderFiles($dir){
    // $excludeArray = array('nbproject');

    $allowedFolder = array(
    'student' => array('create','view','reports'),
    'fees' =>  array('collect', 'others' => array('cheque','bank','paytm'),'reports'),
    'master' => array('create'),
    'transport' => array('create','view','maintainence','reports'),
    'reports' => 'financial'
    );
    echoThis($allowedFolder);
    die;
    $ffs = scandir($dir);
    foreach ($ffs as $key => $value){
    if($value == '.git' || $value == 'PHPExcel-1.8' || $value == 'asset' || $value == 'config' || $value == 'fpdf' || $value == 'html2pdf'){
    unset($ffs[$key]);
    }
    }

    echo '<ol>';
    foreach($ffs as $ff){
    if($ff != '.' && $ff != '..'){
    echo '<li>'.$ff;

    if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
    echo '</li>';
    }
    }
    echo '</ol>';
    }

    //listFolderFiles("/home/sanjay/webdev/360degrees/");

    // echoThis(basename((dirname(__FILE__))));

    function html_escape($html_escape) {
    $html_escape = cleanVar($html_escape);
    return $html_escape;
    }


    $ctitle = "<AS<html>DFA 5 SD'sa 12F!@#$%^ &*():'`.\"/[]>";

    function removeSpecialChar($ctitle) {
    //  $string = addslashes($ctitle);
    $string = serialize($ctitle);
    $string = addslashes($string);
    $string = str_replace("'","\'", $ctitle); // Removes special chars.
    $string = trim(filter_var(preg_replace('/[^A-Za-z0-9-\\\]/', '', $string), FILTER_SANITIZE_STRING));
    echoThis($string);
    }

    echo removeSpecialChar(html_entity_decode($ctitle));

    /*
    function DirLineCounter( $dir , $result = array('lines_html' => false, 'files_count' => false, 'lines_count' => false ), $complete_table = true )
    {

    $file_read = array( 'php', 'html', 'js', 'css' );
    $dir_ignore = array();
    $scan_result = scandir( $dir );
    foreach ( $scan_result as $key => $value ) {
    if ( !in_array( $value, array( '.', '..' ) ) ) {
    if ( is_dir( $dir . DIRECTORY_SEPARATOR . $value ) ) {
    if ( in_array( $value, $dir_ignore ) ) {
    continue;
    }
    $result = DirLineCounter( $dir . DIRECTORY_SEPARATOR . $value, $result, false );
    }
    else {
    $type = explode( '.', $value );
    $type = array_reverse( $type );
    if( !in_array( $type[0], $file_read ) ) {
    continue;
    }
    $lines = 0;
    $handle = fopen( $dir . DIRECTORY_SEPARATOR . $value, 'r' );
    while ( !feof( $handle ) ) {
    if ( is_bool( $handle ) ) {
    break;
    }
    $line = fgets( $handle );
    $lines++;
    }
    fclose( $handle );
    $result['lines_html'][] = '<tr><td>' . $dir . '</td><td>' . $value . '</td><td>' . $lines . '</td></tr>';
    $result['lines_count'] = $result['lines_count'] + $lines;
    $result['files_count'] = $result['files_count'] + 1;

    }
    }
    }

    if ( $complete_table ) {
    $lines_html = implode('', $result['lines_html']) . '<tr><td></td><td style="border: 1px solid #222">Files Total: ' . $result['files_count'] . '</td><td style="border: 1px solid #222">Lines Total: ' . $result['lines_count'] . '</td></tr>';
    return '<table><tr><td style="width: 60%; background-color:#ddd;">Dir</td><td style="width: 30%; background-color:#ddd;">File</td><td style="width: 10%; background-color:#ddd;">Lines</td></tr>' . $lines_html . '</table>';

    }
    else {
    return $result;
    }
    }

    echo DirLineCounter( '.' );
   */
?>
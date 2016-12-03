<?php

 /*
* Developer: Prateek Mathur (pmathur@ebizneeds.com) | www.ebizneeds.com.au
* Page details here:
* Updates here: Return ajax response called from dashboard.php to display complete studentails details along with
*               Complete fee dues and collected fees
*/

require_once "../config/config.php";
require_once DIR_FUNCTIONS;
require_once '../lib/reportfunctions.php';

if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'due'){
    $feeDue        = getDueFees_dashboard("dashboard");
    $returnArray = formatCurrency($feeDue);
    echo json_encode($returnArray);
}
elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'collection'){
    $collectedFeeDetails = feeCollectionReport('', "dashboard");
    $collectedFee  =  array_sum(array_column($collectedFeeDetails, "feeamount"));
    $collectedFee  += array_sum(array_column($collectedFeeDetails, "otherfeeamount"));
    $collectedFee  = number_format($collectedFee, 2, '.', '');
    $returnArray = formatCurrency($collectedFee);
    echo json_encode($returnArray);
}
    




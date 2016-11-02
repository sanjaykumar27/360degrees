<?php
/*
 * This file is code to gather data from database and generate pagination and return result;
 * File Created and Written by Abhishek Kumar Sharma
 *Date Created : 18 SEP 2015 11.30
 */
include_once "../../config/config.php";
include_once "../../lib/functions.php";

$qryString="SELECT `instituteid`, `institutename`, `instituteaddress1` FROM `tblinstitute` WHERE deleted!=1";
$qryResult=  dbSelect($qryString);

extract($_REQUEST);

if (isset($action) && $action=='delete') {
    $deleteString="UPDATE tblinstitute ";
    dbUpdate($updateString);
}

$pageId=(int) ($pageId==0 ? 1 : $pageId);
$sno=(($pageId-1) * ROW_PER_PAGE)+1;

$qryString="SELECT instituteid,institutename,instituteaddress1 FROM tblinstitute WHERE deleted!=1"
        . " ORDER BY instituteid LIMIT ".($pageId-1) * ROW_PER_PAGE   .",". ROW_PER_PAGE * $pageId;

$result =  dbSelect($qryString);
$pagination=  getPagination(mysqli_num_rows($qryResult), ROW_PER_PAGE, $pageId);

$htmlOutput='';
$dataGrid   = '<table class="table table-condensed table-hover" >'
            . '<thead>
                    <tr class="info">
                        <th>SNo.</th>
                        <th>Inst Name</th>
                        <th>Inst Address</th>
                        <th>Update</th>
                        <th>Delete</th>
                        <th>Status</th>
                    </tr>
                </thead>';

while ($data=$result->fetch_row()) {
    $dataGrid.='<tr>
                    <td>'.$sno.'</td>
                    <td>'.$data[1].'</td>
                    <td>'. $data[2].'</td>
                    <td> <a href="addInstitute.php?edid='.$data[0].'"><span class="glyphicon glyphicon-pencil" ></span></a></td>
                    <td> <a href="addInstitute.php?delid='.$data[0].'"><span class="glyphicon glyphicon-trash"></span></a></td>
                    <td> <a href="#">Active</a></td>
                </tr>
               ';
    $sno++;
}
$dataGrid.='</table>';
$htmlOutput.= $dataGrid.$pagination.' </div><div class="clearfix"></div>';
echo $htmlOutput;

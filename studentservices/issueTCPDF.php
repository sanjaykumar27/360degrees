<?php 
  ob_start();
    include('printTC.php');
    $content = ob_get_clean();
    require_once('../html2pdf/html2pdf.class.php');
    $html2pdf = new HTML2PDF();
    $html2pdf->WriteHTML($content); // in $content you put your content to have in pdf

    $html2pdf->Output('example.pdf');

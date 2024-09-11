<?php
/**
 * Description of zip
 * @author oracle
 */

class zip {
    //put your code here
}


$zip = new ZipArchive();
$files = glob("../images/*.{jpg,gif,png,pdf}", GLOB_BRACE);
$filename ='zip.zip';
$zip->open($filename, ZipArchive::CREATE);
foreach ($files as $file) {
    $zip->addFile($file);
}

if($zip->close()){echo '1';}
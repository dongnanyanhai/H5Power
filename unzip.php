<?php
$zip = new ZipArchive;
if ($zip->open('H5Pcms.zip') === TRUE) {
    $zip->extractTo('/data/wwwroot/jeepbj.appsbank.cn/');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
?>
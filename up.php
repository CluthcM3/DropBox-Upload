<?php

$path = 'mmktest.txt';
$get = fopen($path, 'rb');
$size = filesize($path);
$api = 'sl.AxoSaIM625S3TQnDHaA6HOa_ccPebfqKn26_v2hUGtZTqiEOxg5-QytfjnkY4H0jFf4aMvxih2dr7OHfxaJkZJ0ndWdX2aqtojISESI95_mZeL20pabkowqnd-MNPzBlioWp0zUY7Kmo';
$cheader = array("Authorization: Bearer $api",'Dropbox-API-Arg: {"path":"/test/'.$path.'", "mode":"add"}','Content-Type: application/octet-stream');


$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,'https://content.dropboxapi.com/2/files/upload');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($ch, CURLOPT_PUT,true);
curl_setopt($ch,CURLOPT_INFILE, $get);
curl_setopt($ch,CURLOPT_INFILESIZE, $size);
curl_setopt($ch,CURLOPT_HTTPHEADER, $cheader);
$response = curl_exec($ch);

$js = json_decode($response);
$https = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($https === 401)
{
    echo "Upload Gagal\n";
} else
{
    echo "File $js->name Sukses Upload ke dlm $js->path_display\nSize : ";
}
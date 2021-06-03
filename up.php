<?php

class dropbox
{
    var string $create = 'path'; // default
    var string $path = 'regex.php'; // Nama file yg ingin di upload
    var string $api = 'Up1HVmYFkB4AAAAAAAAAAQA7EH6sqMqBgjE6_aLqvnvDHBMSQIgGJ3RxPF7oGVAO'; // apikey dropbox

    /* Connver detik ke waktu */
    function convert($isec)
    {
        $second = $isec % 60;
        $minutes = intval($isec/60);
        $hours = intval($minutes/60);
        $minutes = $minutes%60;

        return date('H:i:s', mktime($hours,$minutes,$second));
    }

    /* mengubah size menjadi kb mb gb */
    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function upload()
    {

        $createfolder = $this->create; // untuk buat folder di dropbox
        $time_start = microtime(true);
        $path = $this->path;
        $get = fopen($path, 'rb');
        $size = filesize($path);


        $cheader = array("Authorization: Bearer {$this->api}",'Dropbox-API-Arg: {"path":"/'.$createfolder.'/'.$path.'", "mode":"add"}','Content-Type: application/octet-stream');


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
            echo "Response 401 | Upload Gagal\n";

        } else if ($https === 409)
        {
            echo "Response 409 | Upload Gagal";

        }
        else
        {

            echo "File $js->name Sukses Upload ke dlm $js->path_display\n";
            echo "Size : ".$this->formatSizeUnits($size);

        }

    }



}

$time_start = microtime(true);
$dropbox = new dropbox();
if (!file_exists($dropbox->path)) die("File ".$dropbox->path." not found\n");

echo "Sabar gann lg di proses upload\n\n";
$dropbox->upload();
echo "\nWaktu : ".$dropbox->convert(microtime(true)-$time_start) . PHP_EOL;

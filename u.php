<?php

$target_dir = "/tmp/uploads";
$target_file = $target_dir . basename($_FILES["i"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["i"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "Filen är ingen bild.";
        $uploadOk = 0;
    }
}


if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Bara bilder tack!";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Något gick fel..";

} else {

    if (move_uploaded_file($_FILES["i"]["tmp_name"], $target_file)) {

        //döper om filen för jag litar inte på dig
        rename($target_file, $target_dir . '/0.jpg');

        //ta bort alla EXIF på alla filer i uploads-mappen. Dessutom vill vi inte ha en kopia.
        shell_exec('exiftool -all="" -overwrite_original_in_place "' . $target_dir .'" *');

        //ladda upp bilden till img.bi över Tor, ta också bort Proxychains's output
        $img = "proxychains imgbi-client -i '" . $target_dir . "'/0.jpg | sed 's/ProxyChains.*//g'";

        $file_dropfile=($target_dir . '/0.jpg');
        $ch = curl_init("https://dropfile.to/upload");
        curl_setopt($ch, CURLOPT_POSTFIELDS,
               array('file'=>"@$file_dropfile"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $postResult = curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($postResult);

        echo '<pre>';
        passthru($img);
        print $obj->{'url'};
        echo '</pre>';


        //Skriv över allting i uploads-mappen, just in case
        shell_exec('find "' . $target_dir . '" -type f -name "*" -exec srm -ll -z {} \;');
        //Du kan rensa minnet också, men det tar lite längre tid. Du kan lägga det som ett 
        //cronjob eller nått annars. På min VPS med 512Mb i RAM så tar det 2-3 sekunder att
        //rensa hela minnet, och det ger utökad integritet. 
        //shell_exec('sdmem -ll -z -f -v');

        
    } else {
        echo "error";
    }
}
?>


<?php

function upload()
{
    $file = $_FILES['pp'];
    $extension = strtolower(substr(strrchr($file['name'], "."), 1));
    if ($extension == 'jpeg')
        $extension = 'jpg';

    if (!in_array($extension, array('png', 'jpg', 'bmp', 'gif'))) {
        return array(false, "Type d'image non supporté");
    }

    $taille = filesize($file['tmp_name']);
    if ($taille > 20000000 || $file["error"] == 2) {
        return array(false, 'Image trop grosse, la limite est 20Mo');
    }

    $salt = random_int(0, 1000000000);
    if (move_uploaded_file($file['tmp_name'], "img/upload/photo_profil$salt.$extension")) {
        return array(true, $extension, $salt);
    }
    return array(false, "L'upload du fichier a échoué");
}

function open($src, $extension)
{
    switch ($extension) {
        case 'bmp':
            $img = imagecreatefromwbmp($src);
            break;
        case 'gif':
            $img = imagecreatefromgif($src);
            break;
        case 'jpg':
            $img = imagecreatefromjpeg($src);
            break;
        case 'png':
            $img = imagecreatefrompng($src);
            break;
    }
    return $img;
}

function crop($pp, $w, $h){
    $length = min($w, $h);
    if ($w==$length){
        $x=0;
        $y = ($h - $length)/2;
    }
    else{
        $y=0;
        $x = ($w - $length)/2;
    }
    $pp = imagecrop($pp, ['x' => $x, 'y' => $y, 'width' => $length, 'height' => $length]);
    return array($pp, $length);
}

function save($img, $dst, $extension)
{
    switch ($extension) {
        case 'bmp':
            imagewbmp($img, $dst);
            break;
        case 'gif':
            imagegif($img, $dst);
            break;
        case 'jpg':
            imagejpeg($img, $dst);
            break;
        case 'png':
            imagepng($img, $dst);
            break;
    }
}


function overlay()
{
    $arr = upload();
    if (!$arr[0]) {
        return $arr;
    }
    //upload réussi
    $extension = $arr[1];
    $salt = $arr[2];

    $src_pp = "img/upload/photo_profil$salt.$extension";
    $pp = open($src_pp, $extension);
    list($w, $h) = getimagesize($src_pp);

    //transformer en un carré, on écrase l'ancienne image
    list($pp, $length) =   crop($pp, $w, $h);

    $src_over = "img/overlay.png";
    $overlay = open($src_over, 'png');
    list($w, $h) = getimagesize($src_over); //ici $w==$h

    imagecopyresampled($pp, $overlay, 0, 0, 0, 0, $length, $length, $w, $h);

    save($pp, $src_pp, $extension);
    return array(true, $src_pp);
}



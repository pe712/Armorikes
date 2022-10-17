<?php
require("fun.php");

if (array_key_exists('pp', $_FILES)) {
    $arr = overlay();
    if (!$arr[0]){
        echo $arr[1]; //message d'erreur
    }
    else{
        $src_pp = $arr[1];
        echo <<<FIN
        <h2>
            <a href="$src_pp" download>Télécharger</a><br>
            <img src="$src_pp" alt="photo profil avec overlay" style="width: 400px;">
            <p>Je ne suis pas satisfait du montage, je souhaite réaliser l'overlay moi même: <a href="img/overlay.png" download>Télécharger l'overlay</a></p>
        </h2>
        FIN;
    }
} else {
?>

    <form enctype="multipart/form-data" action="fusion.php" method="post">
        <div class="mb-3">
            <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
            <h2><label for="pp" class="form-label">photo de profil</label></h2><br>
            <input type="file" class="form-control" name="pp" id="pp" />
        </div>
        <br>
        <button type="submit">Envoyer et appliquer l'overlay</button>
    </form>
<?php
}


//make sure to modify upload size & post size
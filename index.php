<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Çoklu fotoğraf yükleme</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row  pt-5">
        <form action="index.php" method="post" enctype="multipart/form-data">
            <input type="file" name="resim[]" multiple="multiple">
            <button type="submit" name="fotoGonder" class="btn btn-primary">Gönder</button>

        </form>

        <?php
        function resizeImage($resourceType,$image_width,$image_height){
            //istediğiniz kadar yükseklik genişlik verebilirsiiz sadece name ile aynı miktarda olduğuna dikkat edin yoksa karışılık olacaktır.
            $resizeWidht = array(375,475,236,50,230,475,236,80);
            $resizeHeight = array(375,475,236,50,230,630,314,100);
            $resimboyutadet = count($resizeHeight);
            $imageLayer = array();
            for ($i = 0; $i < $resimboyutadet; $i++) {
             $imageLayer[$i] = imagecreatetruecolor($resizeWidht[$i], $resizeHeight[$i]);
             imagecopyresampled($imageLayer[$i], $resourceType, 0, 0, 0, 0, $resizeWidht[$i], $resizeHeight[$i], $image_width, $image_height);
             }
            return $imageLayer;
        }
        function resizeImageName(){
            $resizeWidht = array(375,475,236,50,230,475,236,80);
            $resizeHeight = array(375,475,236,50,230,630,314,100);
            $resimboyutadet = count($resizeHeight);
            $imageLayerName = array();
            for ($i = 0; $i < $resimboyutadet; $i++) {
                $imageLayerName[$i] = $resizeWidht[$i]."x".$resizeHeight[$i];
               }
            return $imageLayerName;
        }
        if(isset($_POST['fotoGonder'])){
            $imageProcess = 0;
            if(is_array($_FILES)) {
                $resim_sayisi = count($_FILES['resim']['name']); //kaç tane resim geldiğini öğrendik
                for ($i = 0; $i < $resim_sayisi; $i++) {
                    $fileName = $_FILES['resim']['tmp_name'][$i];
                    $sourceProperties = getimagesize($fileName);
                    // resim isimleri aynı olmasın diye random sayı atadık
                    $resizeFileName = Rand (10, 100000);
                    $uploadPath = "./upload/";
                    $fileExt = pathinfo($_FILES['resim']['name'][$i], PATHINFO_EXTENSION);
                    $uploadImageType = $sourceProperties[2];
                    $sourceImageWidth = $sourceProperties[0];
                    $sourceImageHeight = $sourceProperties[1];
                    switch ($uploadImageType) {
                        case IMAGETYPE_JPEG:
                            $resourceType = imagecreatefromjpeg($fileName);
                            //ilgili fonksiyona gidip resime boyut veriyoruz
                            $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                            $resimkaydetmesayi = count($imageLayer);
                            // ilgili fonk.'a gidip resim boyutlarına göre isimleri çekiyoruz
                            $pixcelName = resizeImageName();
                            for ($t = 0; $t < $resimkaydetmesayi; $t++) {
                                if($t == $t){
                                    // döngü ile fotoğrafları istenen boyutta kaydettik
                                    imagejpeg($imageLayer[$t], $uploadPath . $resizeFileName ."resize-". $pixcelName[$t].'.' . $fileExt);
                                }
                            }
                            break;
                        case IMAGETYPE_PNG:
                            $resourceType = imagecreatefrompng($fileName);
                            for ($t = 0; $t < 2; $t++) {
                                if($t == 0){
                                    $imageLayer = resizeImage($resourceType, $sourceImageWidth, $sourceImageHeight);
                                    imagepng($imageLayer, $uploadPath .  $resizeFileName . '.' . $fileExt);
                                }elseif($t == 1){
                                    $imageLayer = resizeImage1($resourceType, $sourceImageWidth, $sourceImageHeight);
                                    imagepng($imageLayer, $uploadPath .  $resizeFileName . '.' . $fileExt);
                                }
                            }
                            break;
                        default:
                            $imageProcess = 0;
                            break;
                    }
                    // orjinal halini kaydettik
                    move_uploaded_file($fileName, $uploadPath . $resizeFileName . "." . $fileExt);
                }
            }

        }
        ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>
</html>
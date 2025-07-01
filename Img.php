<?php
// Manipulate images
// Requires Imagick
class Img {

    // Resize and extent image
    static function proc($url, $w, $h) {
        $url = file_get_contents($url);
        $img = new Imagick();
        $img->readImageBlob($url);
        $img->setImageFormat("png");
        $img->scaleImage($w, $h, true);
        $b = new ImagickPixel('transparent');
        $img->setImageBackgroundColor($b);
        $imw = $img->getImageWidth();
        $imh = $img->getImageHeight();
        $exw = -($w/2)+($imw/2);
        $exh = -($h/2)+($imh/2);
        $img->extentImage($w,$h,$exw,$exh);
        $blb = $img->getImagesBlob();
        $img->clear();$img->destroy();
        return $blb;
    }
}

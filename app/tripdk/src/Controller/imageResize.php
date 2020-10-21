<?php


namespace App\Controller;


class imageResize
{
    /**
     * Resize an image and keep the proportions
     * @param string $filename
     * @return false|resource
     */
    function resizeImage($filename)
    {
        $image = imagecreatefromjpeg($filename);

        list($orig_width, $orig_height) = getimagesize($filename);

        $exif = exif_read_data($filename);

        $list = $this->imageSizeCheck($orig_width, $orig_height);
        $width = $list[0];
        $height = $list[1];

        if(!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 6:
                    $portrait = true;
                    $width = 1494;
                    $height = 708;
                    break;
            }
        }

        $image_p = imagecreatetruecolor($width, $height);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0,
            $width, $height, $orig_width, $orig_height);

        if($portrait){
            $image_p = imagerotate($image_p,-90,0);
        }

        return $image_p;
    }

    function imageSizeCheck($width, $height){
        if ($width > $height) {
            $width = 708;
            $height = 335;
        } else if ($width == $height) {
            $width = 708;
            $height = 708;
        } else {
            $width = 708;
            $height = 1494;
        }
        return [$width,$height];
    }
}

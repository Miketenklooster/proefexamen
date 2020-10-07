<?php


namespace App\Controller;


class imageResize
{
    /**
     * Resize an image and keep the proportions
     * @param string $filename
     * @param integer $max_width
     * @param integer $max_height
     * @return false|resource
     */
    function resizeImage($filename)
    {
        list($orig_width, $orig_height) = getimagesize($filename);

        if ($orig_width > $orig_height) {
            $width = 708;
            $height = 335;
        } else if ($orig_width == $orig_height) {
            $width = 708;
            $height = 708;
        } else {
            $width = 708;
            $height = 1494;
        }

        $image_p = imagecreatetruecolor($width, $height);

        $image = imagecreatefromjpeg($filename);

        imagecopyresampled($image_p, $image, 0, 0, 0, 0,
            $width, $height, $orig_width, $orig_height);

        return $image_p;
    }
}

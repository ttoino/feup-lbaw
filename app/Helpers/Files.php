<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class Files {
    public static function convertToWebp(File|UploadedFile $file, int $size, float|int $aspect_ratio = 0): bool {
        $image = imagecreatefromstring($file->getContent());

        if ($image === false)
            return false;

        $original_width = imagesx($image); // width of the original image
        $original_height = imagesy($image); // height of the original image
        $original_aspect_ratio = $original_width / $original_height;

        if ($aspect_ratio <= 0)
            $aspect_ratio = $original_aspect_ratio;

        if ($aspect_ratio > 1) {
            $dest_width = min($size, $original_width);
            $dest_height = $dest_width / $aspect_ratio;
        } else {
            $dest_height = min($size, $original_height);
            $dest_width = $dest_height * $aspect_ratio;
        }

        if ($original_aspect_ratio > $aspect_ratio) {
            $src_width = $original_height * $aspect_ratio;
            $src_height = $original_height;
            $offset_x = ($original_width - $src_width) / 2;
            $offset_y = 0;
        } else {
            $src_width = $original_width;
            $src_height = $original_width / $aspect_ratio;
            $offset_x = 0;
            $offset_y = ($original_height - $src_height) / 2;
        }

        $resized = imagecreatetruecolor($dest_width, $dest_height);
        imagecopyresized($resized, $image, 0, 0, $offset_x, $offset_y, $dest_width, $dest_height, $src_width, $src_height);

        return imagewebp($resized, $file->path());
    }
}
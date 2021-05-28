<?php 

namespace App\Services;

use Illuminate\Http\UploadedFile as File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadFileService {

    public function uploadFileToFolder(string $folder, File $file): string {
        $name = $file->hashName();

        if (substr($file->getMimeType(), 0, 5) == 'image') {
            $im = Image::make($file->getPathname());
            $width = $im->width();
            $height = $im->height();

            if ($width > 1200) {
                $scale = 1200 / $width;
                $width = ceil($width * $scale);
                $height = ceil($height * $scale);
                $im->resize($width, $height);
                $file = (string) $im->encode();
                $folder .= '/' . $name;
            }
        }

        return Storage::put($folder, $file) ? $name : null;
    }
    
}
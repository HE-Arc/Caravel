<?php 

namespace App\Services;

use Illuminate\Http\UploadedFile as File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadFileService {

    /**
     * Upload file and resize if needed
     * @param string $folder specify the folder where to put the image 
     * @param UploadedFile $file file to uplaod 
     * @param int $size set size to -1 to prevent resizing and squaring
     * @param bool $sqaureIt true to square image 
     * 
     */
    public function uploadFileToFolder(string $folder, File $file, $size = 250, $squareIt = true): string {
        $name = $file->hashName();

        if (substr($file->getMimeType(), 0, 5) == 'image') {
            $im = Image::make($file->getPathname());

            if ($size != -1) {
                if ($squareIt) {
                    $im->resize($size, $size);
                } else {
                    $im->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
            }
        }

        return Storage::put($folder, $file) ? $name : null;
    }
    
}
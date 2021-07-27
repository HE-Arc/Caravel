<?php

namespace App\Services;

use Illuminate\Http\UploadedFile as File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


/**
 * This class is a service to manage file upload 
 */
class UploadFileService
{

    /**
     * Upload file and resize needed (if it's an image) 
     * @param string $folder specify the folder where to put the image 
     * @param File $file file to uplaod 
     * @param int $size set size to -1 to prevent resizing and squaring
     * @param bool $sqaureIt true to square image 
     * @param string $disk specify storage point
     * @return string relative path to the file
     * 
     */
    public function uploadFileToFolder(string $folder, File $file, $size = 250, $squareIt = true, $disk = 'public_uploads'): string
    {
        $name = $file->hashName();

        if (substr($folder, -1) != '/') $folder .= '/';

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
                $file = (string) $im->encode();
                $folder .= $name;
                return Storage::disk($disk)->put($folder, $file) ? $folder : null;
            }
        }

        return Storage::put($folder, $file) ? $folder . $name : null;
    }
}

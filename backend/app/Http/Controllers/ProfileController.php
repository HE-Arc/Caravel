<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\UploadFileService;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    
    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request, UploadFileService $fileService)
    {
        if($request->hasfile('picture')){
            $this->user->setProfilePic($fileService->uploadFileToFolder(config('caravel.users.pictureFolder'), $request->file('picture')));
        }  

        // update = fill + save 
        $this->user->update($request->all());

        return response()->json($this->user);
    }

}

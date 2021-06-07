<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\UploadFileService;

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
        $data = $request->validated();

        if($request->hasfile('picture')){
            $image = $fileService->uploadFileToFolder(config('caravel.users.pictureFolder'), $data['picture']);
            $this->user->setProfilePic($image);
            unset($data['picture']);
        }

        // update = fill + save 
        $this->user->update($data);

        return response()->json($this->user);
    }

}

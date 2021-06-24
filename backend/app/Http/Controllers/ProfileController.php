<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Notifications\Action;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Profiler\Profile;

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

        if ($request->hasfile('picture')) {
            $image = $fileService->uploadFileToFolder(config('caravel.users.pictureFolder'), $data['picture']);
            $this->user->setProfilePic($image);
            unset($data['picture']);
        }

        // update = fill + save 
        $this->user->update($data);

        return response()->json($this->user);
    }

    public function getNotifications(Request $request)
    {
        return $request->has('all') ? $this->user->notifications : $this->user->unreadNotifications;
    }

    public function registerFCMToken(ProfileRequest $request)
    {
        $data = $request->validated();
        if (isset($data['fcm'])) {
            $this->user->addFcmToken($data['fcm']);
        }
    }

    public function deleteFCMToken(ProfileRequest $request)
    {
        $data = $request->validated();
        if (isset($data['fcm'])) {
            $this->user->removeFcmToken($data['fcm']);
        }
    }

    public function markAsRead(ProfileRequest $request)
    {
        $data = $request->validated();
        if (isset($data['notif_id'])) {
            $id = $data['notif_id'];

            $notification = $this->user->unreadNotifications()->find($id);
            if ($notification) $notification->markAsRead();
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\UploadFileService;
use Illuminate\Http\Request;

/**
 * This classe is used to manage user profile's settings 
 */
class ProfileController extends Controller
{

    /**
     * Update the profile
     *
     * @param   ProfileRequest   $request
     * @param   UploadService   $fileService
     * @return  \Illuminate\Http\Response
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

    /**
     * This function retrieve user's notifications
     * @param   Request $request
     * @return  \Illuminate\Http\Response
     */
    public function getNotifications(Request $request)
    {
        return $request->has('all') ? $this->user->notifications : $this->user->unreadNotifications;
    }

    /**
     * This function register user's FCM Token
     * @param   ProfileRequest  $request
     */
    public function registerFCMToken(ProfileRequest $request)
    {
        $data = $request->validated();
        if (isset($data['fcm'])) {
            $this->user->addFcmToken($data['fcm']);
        }
    }

    /**
     * This function delete user's FCM token
     * @param   ProfileRequest $request
     */
    public function deleteFCMToken(ProfileRequest $request)
    {
        $data = $request->validated();
        if (isset($data['fcm'])) {
            $this->user->removeFcmToken($data['fcm']);
        }
    }

    /**
     * This function is used to mark notification as read
     * @param   ProfileRequest  $request
     */
    public function markAsRead(ProfileRequest $request)
    {
        $data = $request->validated();

        if (isset($data['notifs'])) {
            $ids = $data['notifs'];

            foreach ($ids as $id) {
                $notification = $this->user->unreadNotifications()->find($id);
                if ($notification) $notification->markAsRead();
            }
        }
    }
}

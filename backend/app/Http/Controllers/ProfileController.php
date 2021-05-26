<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Timezonelist;
use File;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit',['groups' => (auth()->user()->groups()->get()->count()??'0'),
                                    'tasks' => (auth()->user()->tasks()->get()->count()??'0'),
                                    'comments' => (auth()->user()->comments()->get()->count()??'0'),]);
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        if(strlen($request->get('name'))>150){
            return back()->withErrors(['name' => __('You are not allowed to have a so long name (more than 150 characters).')]);
        }
        if($request->hasfile('picture')){
            if(isset(auth()->user()->picture) && File::exists(public_path(public_path(auth()->user()->picture)))){
                File::delete(public_path(auth()->user()->picture));
            }
            if($request->file('picture')->getSize()>2048000){   //images > 2MB will be blurred 
                $filenamePicture = $this->FileNameAndSave($request->file('picture'),75);
            }
            else{
                $filenamePicture = $this->FileNameAndSave($request->file('picture'));
            }
            auth()->user()->picture=$filenamePicture;
            auth()->user()->save();
            auth()->user()->update($request->except('picture'));
        }  
        else{
            auth()->user()->update($request->all());
            auth()->user()->update($request->all());
        }     
        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        if (auth()->user()->id == 1) {
            return back()->withErrors(['not_allow_profile' => __('You are not allowed to change the password for a default user.')]);
        }

        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }

    private function FileNameAndSave($picture,$quality=90){
        //the filename is the hasName of this picture inside the public folder for pictures (defined in the config)
        $filename = config('caravel.users.pictureFolder').$picture->hashName();
        $filenamePicture = public_path($filename);
        Image::make($picture)->fit(250,250)->save($filenamePicture,$quality);
        return $filename;
    }

    public function deletePicture(){
        if(isset(auth()->user()->picture)){
            if(File::exists(public_path(auth()->user()->picture)))
                File::delete(public_path(auth()->user()->picture));
            auth()->user()->picture=null;
            auth()->user()->save();
            return back()->withStatus(__('Picture successfully deleted.'));
        }
        else {
            return back()->withStatus(__('Picture already deleted.'));
        }
    }
}

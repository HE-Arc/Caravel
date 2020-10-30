<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $group 
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, $group)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|max:4096'
        ]);
        if ($validator->passes()) {
            $folder = config('smartmd.files.root') . '/groups\/' . $group;
            $temp = $request->file('image');
            $fileName = '[' . $temp->getClientOriginalName() . ']';
            $name = $temp->hashName();
            if (substr($temp->getMimeType(), 0, 5) == 'image') {
                $fileName = '!' . $fileName;
                $im = Image::make($temp->getPathname());
                $width = $im->width();
                $height = $im->height();
                if ($width > 1200) {
                    $scale = 1200 / $width;
                    $width = ceil($width * $scale);
                    $height = ceil($height * $scale);
                    $im->resize($width, $height);
                    $temp = (string) $im->encode();
                    $folder .= '/'.$name;
                }
            } 

            Storage::put($folder, $temp);
            
            return response()->json(
                [
                    'path' => route('groups.files', ['group' => $group, 'file' => $name]),
                    'name' => $fileName,
                    'message' => 'File uploaded'
                ]
            );
        }
        return response()->json(['message' => $validator->errors()->first()],400);
    }

        /**
     * upload.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $group 
     * @return \Illuminate\Http\Response
     */
    public function getFile(Request $request, $group, $file) {
        $folder = config('smartmd.files.root') . '/groups\/' . $group . '/';
        return response()->file(Storage::path($folder . $file));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('groups.tasks.index', $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

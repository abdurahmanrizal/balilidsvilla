<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;
use App\Models\Gallery;
use App\Models\SocialMedia;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Gallery::get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('thumbnail', function($data) {
                if($data->type != 'video') {
                    return '<img src="/uploads/gallery/photo/' . $data->url . '?version=' . microtime() . '"" class="img-fluid img-thumbnail">';
                }else {
                    return '<div class="embed-responsive embed-responsive-1by1">
                        <iframe class="embed-responsive-item" src="'.$data->url.'"></iframe>
                    </div>';
                }
            })
            ->addColumn('type', function($data) {
                if($data->type == 'photo') {
                    return '<span class="badge badge-primary text-center"><i class="fas fa-camera"></i> PHOTO</span>';
                }else {
                    return '<span class="badge badge-danger text-center"><i class="fas fa-video"></i> VIDEO</span>';
                }
            })
            ->addColumn('action', function($data) {
                 $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-gallery mb-2" data-id="'.$data->id.'">
                     <i class="fas fa-edit"></i> Edit
                 </button><br>';
                 $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-gallery" data-id="'.$data->id.'">
                     <i class="fas fa-trash"></i> Delete
                 </button>';
                 return $btn;
            })

            ->rawColumns(['thumbnail','type','action'])
            ->make(true);
        }
        return view('admin.gallery.index');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            if(empty($request->photo)) {
                $rules = [
                    'type'           => 'required|string',
                    'video'          => 'required|string',
                ];
                $messages = [
                    'required' => ':attribute required cant empty',
                    'integer'  => ':attribute must be a number'
                ];
            }else {
                $rules = [
                    'type'           => 'required|string',
                    'photo'          => 'required|max:2000|mimes:png,jpg,jpeg',
                ];
                $messages = [
                    'required' => ':attribute required cant empty',
                    'integer'  => ':attribute must be a number',
                    'max'      => ':attribute must be max size 2 Mb',
                    'mimes'    => ':attribute must be png,jpg, or jpeg'
                ];
            }

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {
                if(empty($request->photo)) {
                    $item_gallery = new Gallery;
                    $item_gallery->url = $request->video;
                    $item_gallery->type = $request->type;
                    $item_gallery->author_id = Auth::user()->id;

                    $store_item_gallery = $item_gallery->save();

                    if(!$store_item_gallery) {
                        return response()->json([
                            'code'    => 500,
                            'success' => false,
                            'message' => 'Something went wrong with our system'
                        ]);
                    }else {
                        store_log($request->ip(), Browser::browserName(), 'gallery','store',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully add gallery'
                        ]);
                    }
                }else {
                    if ($file = $request->file('photo')) {

                        if (!file_exists(public_path("uploads/gallery/photo"))) {
                            mkdir(public_path("uploads/gallery/photo"), 0777, true);
                        }

                        $destinationPath = public_path("uploads/gallery/photo");

                        $fileName = Str::random(40) . '.jpg';

                        $file->move($destinationPath, $fileName);

                        $item_gallery = new Gallery;
                        $item_gallery->url = $fileName;
                        $item_gallery->type = $request->type;
                        $item_gallery->author_id = Auth::user()->id;

                        $store_item_gallery = $item_gallery->save();

                        if(!$store_item_gallery) {
                            return response()->json([
                                'code'    => 500,
                                'success' => false,
                                'message' => 'Something went wrong with our system'
                            ]);
                        }else {
                            store_log($request->ip(), Browser::browserName(), 'gallery','store',Auth::user()->id);
                            return response()->json([
                                'code' => 200,
                                'success' => true,
                                'message' => 'Successfully add gallery'
                            ]);
                        }
                    }
                }

            }

        }
    }

    public function show(Request $request)
    {
        if($request->ajax()) {
            $checking_item_gallery = Gallery::find($request->id);
            if(empty($checking_item_gallery)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Data item gallery not found'
                ]);
            }else {
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => 'Data item gallery found',
                    'data'    => $checking_item_gallery
                ]);
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()) {
            $checking_item_gallery = Gallery::find($request->id);

            if(empty($checking_item_gallery)) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data item gallery not found'
                ]);
            }else {
                $input = $request->all();

                if(empty($request->photo)) {
                    $rules = [
                        'type'           => 'required|string',
                        'video'          => 'required|string',
                    ];
                    $messages = [
                        'required' => ':attribute required cant empty',
                        'string'  => ':attribute must be a string type'
                    ];
                }else {
                    $rules = [
                        'type'           => 'required|string',
                        'photo'          => 'required|max:2000|mimes:png,jpg,jpeg',
                    ];
                    $messages = [
                        'required' => ':attribute required cant empty',
                        'string'  => ':attribute must be a string type',
                        'max'      => ':attribute must be max size 2 Mb',
                        'mimes'    => ':attribute must be png,jpg, or jpeg'
                    ];
                }

                $validator = Validator::make($input, $rules, $messages);

                if ($validator->fails()) {
                    return response()->json([
                        'code'    => 302,
                        'success' => false,
                        'message' => $validator->messages()
                    ]);
                }else {
                    if(empty($request->photo)) {
                        $checking_item_gallery->update([
                            'url' => $request->video
                        ]);
                        store_log($request->ip(), Browser::browserName(), 'gallery','update',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully update gallery'
                        ]);
                    }else {
                        if ($file = $request->file('photo')) {

                            if (file_exists(public_path("uploads/gallery/photo/".$checking_item_gallery->url))) {
                                unlink(public_path("uploads/gallery/photo/".$checking_item_gallery->url));
                            }

                            $destinationPath = public_path("uploads/gallery/photo");

                            $fileName = Str::random(40) . '.jpg';

                            $file->move($destinationPath, $fileName);

                            $checking_item_gallery->update([
                                'url' => $fileName
                            ]);

                            store_log($request->ip(), Browser::browserName(), 'gallery','update',Auth::user()->id);
                            return response()->json([
                                'code' => 200,
                                'success' => true,
                                'message' => 'Successfully update gallery'
                            ]);

                        }
                    }

                }
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $checking_item_gallery = Gallery::find($request->id);

            if(empty($checking_item_gallery)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data item gallery not found'
                ]);
            }else {
                $checking_item_gallery->delete();
                store_log($request->ip(), Browser::browserName(), 'gallery','delete',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Successfully delete item gallery'
                ]);
            }
        }
    }

    public function moreGallery()
    {
       $galleries = Gallery::all();
       return view('layouts.client.gallery',['galleries' => $galleries, 'social_medias' => $this->socialMedia()]);
    }

    private function socialMedia()
    {
        $social_medias = SocialMedia::all();
        return $social_medias;
    }
}

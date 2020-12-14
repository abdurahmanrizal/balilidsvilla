<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Banner::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function($data) {
                    if(!file_exists(public_path("uploads/banner/photo/".$data->url))) {
                        return '-';
                    }
                    return '<img src="/uploads/banner/photo/' . $data->url . '?version=' . microtime() . '" alt="" class="img-thumbnail">';
                })
                ->addColumn('action', function($data) {
                        $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-banner mb-2" data-id="'.$data->id.'">
                            <i class="fas fa-edit"></i> Edit
                        </button><br>';
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-banner" data-id="'.$data->id.'">
                            <i class="fas fa-trash"></i> Delete
                        </button>';
                        return $btn;
                })
                ->rawColumns(['thumbnail','action'])
                ->make(true);
        }
        return view('admin.banner.index');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'photo'          => 'required|max:2000|mimes:png,jpg,jpeg',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'max'      => ':attribute must be max size 2 Mb',
                'mimes'    => ':attribute must be png,jpg, or jpeg'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {

                if ($file = $request->file('photo')) {

                    if (!file_exists(public_path("uploads/banner/photo"))) {
                        mkdir(public_path("uploads/banner/photo"), 0777, true);
                    }

                    $destinationPath = public_path("uploads/banner/photo");

                    $fileName = Str::random(40) . '.jpg';

                    $file->move($destinationPath, $fileName);

                    $banner = new Banner;
                    $banner->url = $fileName;

                    $store_banner = $banner->save();

                    if(!$store_banner) {
                        return response()->json([
                            'code'    => 500,
                            'success' => false,
                            'message' => 'Something went wrong with our system'
                        ]);
                    }else {
                        store_log($request->ip(), Browser::browserName(), 'banner','store',Auth::user()->id);
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

    public function show(Request $request)
    {
        if($request->ajax()) {
            $banner = Banner::find($request->id);
            if(empty($banner)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Banner not found'
                ]);
            }else {
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => 'Banner found',
                    'data'    => $banner
                ]);
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()) {
            $banner = Banner::find($request->id);

            if(empty($banner)) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data banner post not found'
                ]);
            }else {
                $input = $request->all();

                $rules = [
                    'photo'          => 'required|max:2000|mimes:png,jpg,jpeg',
                ];


                $messages = [
                    'required' => ':attribute required cant empty',
                    'max'      => ':attribute must be max size 2 Mb',
                    'mimes'    => ':attribute must be png,jpg, or jpeg'
                ];

                $validator = Validator::make($input, $rules, $messages);

                if ($validator->fails()) {
                    return response()->json([
                        'code'    => 302,
                        'success' => false,
                        'message' => $validator->messages()
                    ]);
                }else {
                    if ($file = $request->file('photo')) {

                        if (file_exists(public_path("uploads/banner/photo/".$banner->url))) {
                            unlink(public_path("uploads/banner/photo/".$banner->url));
                        }

                        $destinationPath = public_path("uploads/banner/photo");

                        $fileName = Str::random(40) . '.jpg';

                        $file->move($destinationPath, $fileName);

                        $banner->update([
                            'url'         => $fileName
                        ]);

                        store_log($request->ip(), Browser::browserName(), 'banner','update',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully update banner'
                        ]);

                    }
                }
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $banner = Banner::find($request->id);

            if(empty($banner)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data banner not found'
                ]);
            }else {
                $banner->delete();
                store_log($request->ip(), Browser::browserName(), 'banner','delete',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Successfully delete banner'
                ]);
            }
        }
    }
}

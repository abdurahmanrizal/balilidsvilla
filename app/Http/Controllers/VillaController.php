<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resort;
use App\Models\ResortItemGallery;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;

class VillaController extends Controller
{
    public function index(Request $request)
    {
       if($request->ajax()) {
           $data = Resort::whereHas('category', function($query){
               $query->where('name','villa');
           })->get();
           return Datatables::of($data)
           ->addIndexColumn()
           ->addColumn('price', function($data) {
               return 'IDR ' . number_format($data->price, 0, ',', '.') . ' / night';
           })
           ->addColumn('location', function($data) {
               if($data->location != '') {
                   return $data->location;
               }else {
                   return '<p class="text-center">-</p>';
               }
           })
           ->addColumn('action', function($data) {
                $btn = '<button type="button" class="btn btn-outline-success btn-sm show-villa mb-2" data-id="'.$data->id.'">
                    <i class="fas fa-eye"></i> Detail
                </button><br>';
                $btn .= '<button type="button" class="btn btn-outline-success btn-sm gallery-villa mb-2" data-id="'.$data->id.'">
                    <i class="fas fa-book"></i> Gallery
                </button><br>';
                $btn .= '<button type="button" class="btn btn-outline-warning btn-sm edit-villa mb-2" data-id="'.$data->id.'">
                    <i class="fas fa-edit"></i> Edit
                </button><br>';
                $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-villa" data-id="'.$data->id.'">
                    <i class="fas fa-trash"></i> Delete
                </button>';
                return $btn;
           })

           ->rawColumns(['price','action','location','description'])
           ->make(true);
       }
       return view('admin.villas.index');
    }

    public function show(Request $request)
    {
        if($request->ajax()) {
            $show_villa = Resort::find($request->id);

            if(empty($show_villa)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data villa not found'
                ]);
            }
            return response()->json([
                'code'    => 200,
                'success' => true,
                'message' => 'Data villa found',
                'data'    => $show_villa
            ]);
        }
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'name'           => 'required|string',
                'description'    => 'required|string',
                'price'          => 'required|integer',
                'location'       => 'required|string'
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'integer'  => ':attribute must be a number'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {
                $resort_item = new Resort;
                $resort_item->name = $request->name;
                $resort_item->category_id = 1; // to villa
                $resort_item->description = $request->description;
                $resort_item->price = $request->price;
                $resort_item->location = $request->location;

                $store_resort_item = $resort_item->save();

                if(!$store_resort_item) {
                    return response()->json([
                        'code'    => 500,
                        'success' => false,
                        'message' => 'Something went wrong with our system'
                    ]);
                }
                store_log($request->ip(), Browser::browserName(), 'villa','store',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'success to add villa on our database'
                ]);
            }
        }
    }

    public function update(Request $request)
    {
        if($request->ajax()) {
            $data_villa = Resort::find($request->edit_id);
            if(empty($data_villa)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Data villa not found'
                ]);
            }

            $input = $request->all();

            $rules = [
                'name'           => 'required|string',
                'description'    => 'required|string',
                'price'          => 'required|integer',
                'location'       => 'required|string'
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'integer'  => ':attribute must be a number'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {

                $data_villa->update([
                    'name'        => $request->name,
                    'description' => $request->description,
                    'price'       => $request->price,
                    'location'    => $request->location
                ]);

                store_log($request->ip(), Browser::browserName(), 'villa','update',Auth::user()->id);

                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'success to update villa on our database'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $data_villa = Resort::find($request->id);

            if(empty($data_villa)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Data villa not found'
                ]);
            }

            Resort::destroy($request->id);

            store_log($request->ip(), Browser::browserName(), 'villa','delete',Auth::user()->id);

            return response()->json([
                'code'    => 200,
                'status'  => true,
                'message' => 'Successfully delete data villa'
            ]);
        }
    }

    public function gallery(Request $request)
    {
        if($request->ajax()) {
            $data = ResortItemGallery::where('resort_item_id', $request->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function($data) {
                    if($data->type == 'photo') {
                        return '<img src="/uploads/villa/photo/' . $data->url . '?version=' . microtime() . '" alt="" class="img-thumbnail">';
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
                        $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-gallery-villa" data-id="'.$data->id.'">
                            <i class="fas fa-edit"></i> Edit Gallery
                        </button>';
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-gallery-villa ml-2" data-id="'.$data->id.'">
                            <i class="fas fa-trash"></i> Delete Gallery
                        </button>';
                        return $btn;
                })
                ->rawColumns(['thumbnail','action','type'])
                ->make(true);
        }
        $data_villa = Resort::find($request->id);
        return view('admin.villas.gallery',['villa_id' => $request->id,'data_villa' => $data_villa]);
    }

    public function galleryStore(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            if(empty($request->photo)) {
                $rules = [
                    'name_villa'     => 'required|integer',
                    'type'           => 'required|string',
                    'video'          => 'required|string',
                ];
                $messages = [
                    'required' => ':attribute required cant empty',
                    'integer'  => ':attribute must be a number'
                ];
            }else {
                $rules = [
                    'name_villa'     => 'required|integer',
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
                    $resort_item_gallery = new ResortItemGallery;
                    $resort_item_gallery->resort_item_id = $request->name_villa;
                    $resort_item_gallery->url = $request->video;
                    $resort_item_gallery->type = $request->type;

                    $store_item_gallery = $resort_item_gallery->save();

                    if(!$store_item_gallery) {
                        return response()->json([
                            'code'    => 500,
                            'success' => false,
                            'message' => 'Something went wrong with our system'
                        ]);
                    }else {
                        store_log($request->ip(), Browser::browserName(), 'villa/gallery','store',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully add gallery'
                        ]);
                    }
                }else {
                    if ($file = $request->file('photo')) {

                        if (!file_exists(public_path("uploads/villa/photo"))) {
                            mkdir(public_path("uploads/villa/photo"), 0777, true);
                        }

                        $destinationPath = public_path("uploads/villa/photo");

                        $fileName = Str::random(40) . '.jpg';

                        $file->move($destinationPath, $fileName);

                        $resort_item_gallery = new ResortItemGallery;
                        $resort_item_gallery->resort_item_id = $request->name_villa;
                        $resort_item_gallery->url = $fileName;
                        $resort_item_gallery->type = $request->type;

                        $store_item_gallery = $resort_item_gallery->save();

                        if(!$store_item_gallery) {
                            return response()->json([
                                'code'    => 500,
                                'success' => false,
                                'message' => 'Something went wrong with our system'
                            ]);
                        }else {
                            store_log($request->ip(), Browser::browserName(), 'villa/gallery','store',Auth::user()->id);
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

    public function galleryEdit(Request $request)
    {
        if($request->ajax()) {
            $checking_item_gallery = ResortItemGallery::find($request->id);
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

    public function galleryUpdate(Request $request)
    {
        if($request->ajax()) {
            $checking_item_gallery = ResortItemGallery::find($request->id);

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
                        'name_villa'     => 'required|integer',
                        'type'           => 'required|string',
                        'video'          => 'required|string',
                    ];
                    $messages = [
                        'required' => ':attribute required cant empty',
                        'integer'  => ':attribute must be a number'
                    ];
                }else {
                    $rules = [
                        'name_villa'     => 'required|integer',
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
                        $checking_item_gallery->update([
                            'url' => $request->video
                        ]);
                        store_log($request->ip(), Browser::browserName(), 'villa/gallery','update',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully update gallery'
                        ]);
                    }else {
                        if ($file = $request->file('photo')) {

                            if (file_exists(public_path("uploads/villa/photo/".$checking_item_gallery->url))) {
                                unlink(public_path("uploads/villa/photo/".$checking_item_gallery->url));
                            }

                            $destinationPath = public_path("uploads/villa/photo");

                            $fileName = Str::random(40) . '.jpg';

                            $file->move($destinationPath, $fileName);

                            $checking_item_gallery->update([
                                'url' => $fileName
                            ]);

                            store_log($request->ip(), Browser::browserName(), 'villa/gallery','update',Auth::user()->id);
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

    public function galleryDestroy(Request $request)
    {
        if($request->ajax()) {
            $checking_item_gallery = ResortItemGallery::find($request->id);

            if(empty($checking_item_gallery)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data item gallery not found'
                ]);
            }else {
                $checking_item_gallery->delete();
                store_log($request->ip(), Browser::browserName(), 'villa/gallery','delete',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Successfully delete item gallery'
                ]);
            }
        }
    }

    public function postVilla($id)
    {
        $villa = Resort::with(['resort_item_gallery','category'])->find($id);

        if(empty($villa)) {
            abort(404);
        }else{
            return view('layouts.client.post',[
                'villa'         => $villa,
                'villas'        => $this->all_villa($id),
                'social_medias' => $this->socialMedia(),
                'count_gallery' => count($villa->resort_item_gallery)
            ]);
        }
    }

    private function all_villa($id)
    {
        $villas = Resort::with('resort_gallery')->whereHas('category', function($query) use ($id){
            $query->where('name', 'villa');
        })->where('id','!=',$id)->get();
        return $villas;
    }

    private function socialMedia()
    {
        $social_medias = SocialMedia::all();
        return $social_medias;
    }


}

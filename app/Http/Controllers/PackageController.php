<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Resort;
use App\Models\PackageGallery;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Package::get();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($data) {
                return $data->name;
            })
            ->addColumn('attribute_package', function(Package $package) {
                return $package->resort_items->map(function($rs){
                    return $rs->name;
                })->implode(' + ');
            })
            ->addColumn('price', function($data) {
                return 'IDR ' . number_format($data->price, 0, ',', '.');
            })
            ->addColumn('action', function($data) {
                 $btn = '<button type="button" class="btn btn-outline-success btn-sm gallery-package mb-2" data-id="'.$data->id.'">
                     <i class="fas fa-book"></i> Gallery
                 </button><br>';
                 $btn .= '<button type="button" class="btn btn-outline-warning btn-sm edit-package mb-2" data-id="'.$data->id.'">
                     <i class="fas fa-edit"></i> Edit
                 </button><br>';
                 $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-package" data-id="'.$data->id.'">
                     <i class="fas fa-trash"></i> Delete
                 </button>';
                 return $btn;
            })

            ->rawColumns(['attribute_package','name','price','action'])
            ->make(true);
        }
        $resorts = Resort::get(['id','name']);
        return view('admin.packages.index', ['resorts' => $resorts]);
    }

    public function show(Request $request)
    {
        if($request->ajax()) {
            $show_package = Package::find($request->id);

            $resorts = $show_package->resort_items;
            $data_resort    = [];

            foreach($resorts as $resort) {
                $data_resort[] = $resort->id;
            }

            if(empty($show_package)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data package not found'
                ]);
            }

            return response()->json([
                'code'    => 200,
                'success' => true,
                'message' => 'Data package found',
                'data'    => $show_package,
                'item_resorts' => $data_resort
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
                'price'          => 'required|integer'
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
                $package_item = new Package;
                $package_item->name = $request->name;
                $package_item->description = $request->description;
                $package_item->price = $request->price;

                $store_package_item = $package_item->save();

                if(!$store_package_item) {
                    return response()->json([
                        'code'    => 500,
                        'success' => false,
                        'message' => 'Something went wrong with our system'
                    ]);
                }

                $package = Package::find($package_item->id);

                $item_packages = $request->attribute_package;

                $sync_item_packages = [];

                foreach($item_packages as $item_package) {
                    $sync_item_packages[] = [
                        'resort_id' => $item_package
                    ];
                }

                $package->resort_items()->attach($sync_item_packages);


                store_log($request->ip(), Browser::browserName(), 'packages','store',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'success to add package on our database'
                ]);
            }
        }
    }

    public function update(Request $request)
    {
        if($request->ajax()) {
            $data_package = Package::find($request->edit_id);
            if(empty($data_package)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Data activity not found'
                ]);
            }

            $input = $request->all();

            $rules = [
                'name'           => 'required|string',
                'description'    => 'required|string',
                'price'          => 'required|integer',
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

                $data_package->update([
                    'name'        => $request->name,
                    'description' => $request->description,
                    'price'       => $request->price
                ]);

                $item_attribute_packages = $request->attribute_package;

                $sync_edit_packages = [];

                foreach($item_attribute_packages as $item_attribute_package) {
                    $sync_item_packages[] = [
                        'resort_id' => $item_attribute_package
                    ];
                }
                $data_package->resort_items()->sync($sync_item_packages);

                store_log($request->ip(), Browser::browserName(), 'packages','update',Auth::user()->id);

                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'success to update package on our database'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $data_package = Package::find($request->id);

            if(empty($data_package)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Data package not found'
                ]);
            }

            Package::destroy($request->id);

            $data_package->resort_items()->detach($request->id);

            store_log($request->ip(), Browser::browserName(), 'packages','delete',Auth::user()->id);

            return response()->json([
                'code'    => 200,
                'status'  => true,
                'message' => 'Successfully delete data activity'
            ]);
        }
    }

    public function gallery(Request $request)
    {
        if($request->ajax()) {
            $data = PackageGallery::where('package_id', $request->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function($data) {
                    if($data->type == 'photo') {
                        return '<img src="/uploads/package/photo/' . $data->url . '?version=' . microtime() . '" alt="" class="img-thumbnail">';
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
                        $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-gallery-package mb-2" data-id="'.$data->id.'">
                            <i class="fas fa-edit"></i> Edit Gallery
                        </button><br>';
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-gallery-package" data-id="'.$data->id.'">
                            <i class="fas fa-trash"></i> Delete Gallery
                        </button>';
                        return $btn;
                })
                ->rawColumns(['thumbnail','action','type'])
                ->make(true);
        }
        $data_package = Package::find($request->id);
        return view('admin.packages.gallery',['package_id' => $request->id,'data_package' => $data_package]);
    }

    public function galleryStore(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            if(empty($request->photo)) {
                $rules = [
                    'name_package'   => 'required|integer',
                    'type'           => 'required|string',
                    'video'          => 'required|string',
                ];
                $messages = [
                    'required' => ':attribute required cant empty',
                    'integer'  => ':attribute must be a number'
                ];
            }else {
                $rules = [
                    'name_package'   => 'required|integer',
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
                    $package_gallery = new PackageGallery;
                    $package_gallery->package_id = $request->name_package;
                    $package_gallery->url = $request->video;
                    $package_gallery->type = $request->type;

                    $store_item_gallery = $package_gallery->save();

                    if(!$store_item_gallery) {
                        return response()->json([
                            'code'    => 500,
                            'success' => false,
                            'message' => 'Something went wrong with our system'
                        ]);
                    }else {
                        store_log($request->ip(), Browser::browserName(), 'package/gallery','store',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully add gallery'
                        ]);
                    }
                }else {
                    if ($file = $request->file('photo')) {

                        if (!file_exists(public_path("uploads/package/photo"))) {
                            mkdir(public_path("uploads/package/photo"), 0777, true);
                        }

                        $destinationPath = public_path("uploads/package/photo");

                        $fileName = Str::random(40) . '.jpg';

                        $file->move($destinationPath, $fileName);

                        $package_gallery = new PackageGallery;
                        $package_gallery->package_id = $request->name_package;
                        $package_gallery->url = $fileName;
                        $package_gallery->type = $request->type;

                        $store_item_gallery = $package_gallery->save();

                        if(!$store_item_gallery) {
                            return response()->json([
                                'code'    => 500,
                                'success' => false,
                                'message' => 'Something went wrong with our system'
                            ]);
                        }else {
                            store_log($request->ip(), Browser::browserName(), 'package/gallery','store',Auth::user()->id);
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
            $checking_item_gallery = PackageGallery::find($request->id);
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
            $checking_item_gallery = PackageGallery::find($request->id);

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
                        'name_package'   => 'required|integer',
                        'type'           => 'required|string',
                        'video'          => 'required|string',
                    ];
                    $messages = [
                        'required' => ':attribute required cant empty',
                        'integer'  => ':attribute must be a number'
                    ];
                }else {
                    $rules = [
                        'name_package'   => 'required|integer',
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
                        store_log($request->ip(), Browser::browserName(), 'package/gallery','update',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully update gallery'
                        ]);
                    }else {
                        if ($file = $request->file('photo')) {

                            if (file_exists(public_path("uploads/package/photo/".$checking_item_gallery->url))) {
                                unlink(public_path("uploads/package/photo/".$checking_item_gallery->url));
                            }

                            $destinationPath = public_path("uploads/package/photo");

                            $fileName = Str::random(40) . '.jpg';

                            $file->move($destinationPath, $fileName);

                            $checking_item_gallery->update([
                                'url' => $fileName
                            ]);

                            store_log($request->ip(), Browser::browserName(), 'package/gallery','update',Auth::user()->id);
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
            $checking_item_gallery = PackageGallery::find($request->id);

            if(empty($checking_item_gallery)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data item gallery not found'
                ]);
            }else {
                $checking_item_gallery->delete();
                store_log($request->ip(), Browser::browserName(), 'package/gallery','delete',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Successfully delete item gallery'
                ]);
            }
        }
    }

    public function postPackage($id)
    {
        $package = Package::with(['resort_items','package_galleries'])->find($id);

        if(empty($package)) {
            abort(404);
        }else{
            return view('layouts.client.package',[
                'package'          => $package,
                'packages'         => $this->all_package($id),
                'social_medias'    => $this->socialMedia(),
                'count_gallery'    => count($package->package_galleries)
            ]);
        }
    }

    private function all_package($id)
    {
        $packages = Package::with(['resort_items','package_gallery'])
        ->where('id','!=', $id)->get();
        return $packages;
    }

    private function socialMedia()
    {
        $social_medias = SocialMedia::all();
        return $social_medias;
    }
}

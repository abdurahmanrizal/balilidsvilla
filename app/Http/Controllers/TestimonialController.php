<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Testimonial::all();
                return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('photo', function($data) {
                    return '<img src="/uploads/testimonial/photo/' . $data->url . '?version=' . microtime() . '"" class="img-fluid img-thumbnail">';
                })
                ->addColumn('action', function($data) {
                    if($data->priority == '0') {
                        $btn = '<button type="button" class="btn btn-outline-success btn-sm priority-testimoni mb-2" data-id="'.$data->id.'">
                            <i class="fas fa-star"></i> Priority
                        </button><br>';
                    }else {
                        $btn = '<button type="button" class="btn btn-outline-danger btn-sm unpriority-testimoni mb-2" data-id="'.$data->id.'">
                            <i class="fas fa-star"></i> Unpriority
                        </button><br>';
                    }

                    $btn .= '<button type="button" class="btn btn-outline-warning btn-sm edit-testimoni mb-2" data-id="'.$data->id.'">
                        <i class="fas fa-edit"></i> Edit
                    </button><br>';
                    $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-testimoni" data-id="'.$data->id.'">
                        <i class="fas fa-trash"></i> Delete
                    </button>';
                    return $btn;
                })
                ->rawColumns(['photo','action'])
                ->make(true);
        }
        return view('admin.testimonial.index');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'name'        => 'required|string',
                'position'    => 'required|string',
                'testimonial' => 'required|string',
                'photo'       => 'required|max:2000|mimes:png,jpg,jpeg',
            ];
            $messages = [
                'required' => ':attribute required cant empty',
                'string'   => ':attribute must be alphabet or number',
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

                    if (!file_exists(public_path("uploads/testimonial/photo"))) {
                        mkdir(public_path("uploads/testimonial/photo"), 0777, true);
                    }

                    $destinationPath = public_path("uploads/testimonial/photo");

                    $fileName = Str::random(40) . '.jpg';

                    $file->move($destinationPath, $fileName);

                    $item_testimonial = new Testimonial;
                    $item_testimonial->url = $fileName;
                    $item_testimonial->name = $request->name;
                    $item_testimonial->position = $request->position;
                    $item_testimonial->testimonial = $request->testimonial;
                    $item_testimonial->priority = '0'; // tidak priority

                    $store_item_testimonial = $item_testimonial->save();

                    if(!$store_item_testimonial) {
                        return response()->json([
                            'code'    => 500,
                            'success' => false,
                            'message' => 'Something went wrong with our system'
                        ]);
                    }else {
                        store_log($request->ip(), Browser::browserName(), 'testimonial','store',Auth::user()->id);
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
            $input = $request->all();

            $rules = [
                'id'        => 'required|integer',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'integer'  => ':attribute can number'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {
                $testimonial = Testimonial::find($request->id);

                if(empty($testimonial)) {
                    return response()->json([
                        'code'    => 404,
                        'success' => false,
                        'message' => 'Testimonial not found'
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'data'    => $testimonial
                ]);
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            if(!empty($request->photo)) {
                $rules = [
                    'id'          => 'required|integer',
                    'name'        => 'required|string',
                    'position'    => 'required|string',
                    'testimonial' => 'required|string',
                    'photo'       => 'required|max:2000|mimes:png,jpg,jpeg',
                ];

                $messages = [
                    'required' => ':attribute required cant empty',
                    'id'       => ':attribute must be number',
                    'string'   => ':attribute must be alphabet or number',
                    'max'      => ':attribute must be max size 2 Mb',
                    'mimes'    => ':attribute must be png,jpg, or jpeg'
                ];
            }else {
                $rules = [
                    'id'          => 'required|integer',
                    'name'        => 'required|string',
                    'position'    => 'required|string',
                    'testimonial' => 'required|string',
                ];

                $messages = [
                    'required' => ':attribute required cant empty',
                    'id'       => ':attribute must be number',
                    'string'   => ':attribute must be alphabet or number'
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
                $testimonial = Testimonial::find($request->id);

                if(empty($testimonial)) {
                    return response()->json([
                        'code' => 404,
                        'success' => false,
                        'message' => 'testimonial not found'
                    ]);
                }

                if ($request->file('photo') != "") {

                    $file = $request->file('photo');

                    if (!file_exists(public_path("uploads/testimonial/photo"))) {
                        mkdir(public_path("uploads/testimonial/photo"), 0777, true);
                    }

                    $destinationPath = public_path("uploads/testimonial/photo");

                    $fileName = Str::random(40) . '.jpg';

                    $file->move($destinationPath, $fileName);

                    $testimonial->update([
                        'name'        => $request->name,
                        'position'    => $request->position,
                        'testimonial' => $request->testimonial,
                        'url'         => $fileName
                    ]);


                }else {
                    $testimonial->update([
                        'name'        => $request->name,
                        'position'    => $request->position,
                        'testimonial' => $request->testimonial,
                    ]);
                }

                store_log($request->ip(), Browser::browserName(), 'testimonial','update',Auth::user()->id);

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Successfully update testimonial'
                ]);
            }

        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'id'        => 'required|integer',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'integer'  => ':attribute can number'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {
                $testimonial = Testimonial::find($request->id);

                if(empty($testimonial)) {
                    return response()->json([
                        'code'    => 404,
                        'success' => false,
                        'message' => 'Testimonial not found'
                    ]);
                }

                $testimonial->delete();

                store_log($request->ip(), Browser::browserName(), 'testimonial','delete',Auth::user()->id);

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Testimonial success to be deleted'
                ]);
            }
        }
    }

    public function priority(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'id'        => 'required|integer',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'integer'  => ':attribute can number'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {
                $testimonial = Testimonial::find($request->id);

                if(empty($testimonial)) {
                    return response()->json([
                        'code'    => 404,
                        'success' => false,
                        'message' => 'Testimonial not found'
                    ]);
                }

                $testimonial->update([
                    'priority' => '1'
                ]);

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Testimonial success to be update'
                ]);
            }
        }
    }

    public function unpriority(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'id'        => 'required|integer',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'integer'  => ':attribute can number'
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {
                $testimonial = Testimonial::find($request->id);

                if(empty($testimonial)) {
                    return response()->json([
                        'code'    => 404,
                        'success' => false,
                        'message' => 'Testimonial not found'
                    ]);
                }

                $testimonial->update([
                    'priority' => '0'
                ]);

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Testimonial success to be update'
                ]);
            }
        }
    }
}

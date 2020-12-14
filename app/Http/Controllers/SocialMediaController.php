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
use App\Models\SocialMedia;

class SocialMediaController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = SocialMedia::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('item_social_media', function($data) {
                    if($data->item_social_media == 'facebook') {
                        $item = '<span class="badge badge-primary">Facebook</span>';
                    }elseif($data->item_social_media == 'whatsapp') {
                        $item = '<span class="badge badge-success">Whatsapp</span>';
                    }elseif($data->item_social_media == 'instagram') {
                        $item = '<span class="badge badge-danger">Instagram</span>';
                    }else{
                        $item = '-';
                    }
                    return $item;
                })
                ->addColumn('action', function($data) {
                    $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-social-media mb-2" data-id="'.$data->id.'">
                        <i class="fas fa-edit"></i> Edit
                    </button><br>';
                    $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-social-media" data-id="'.$data->id.'">
                        <i class="fas fa-trash"></i> Delete
                    </button>';
                    return $btn;
                })
                ->rawColumns(['item_social_media','action'])
                ->make(true);
        }
        return view('admin.social-media.index');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'item_social_media'   => 'required|string',
                'name_social_media'   => 'required|string',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'string'   => ':attribute detect not number or text',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {

                $social_media = SocialMedia::where('item_social_media', $request->item_social_media)->first();

                if(!empty($social_media)) {
                    return response()->json([
                        'code' => 403,
                        'success' => false,
                        'message' => 'Item does exist before'
                    ]);
                }

                SocialMedia::create([
                    'item_social_media' => $request->item_social_media,
                    'name_social_media' => $request->name_social_media
                ]);

                store_log($request->ip(), Browser::browserName(), 'social-media','store',Auth::user()->id);

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'success create item social media'
                ]);
            }

        }
    }

    public function show(Request $request)
    {
        if($request->ajax()) {
            $social_media = SocialMedia::find($request->id);
            if(empty($social_media)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'social media not found'
                ]);
            }else {
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => 'social media found',
                    'data'    => $social_media
                ]);
            }
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'edit_item_social_media'   => 'required|string',
                'edit_name_social_media'   => 'required|string',
            ];

            $messages = [
                'required' => ':attribute required cant empty',
                'string'   => ':attribute detect not number or text',
            ];

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'code'    => 302,
                    'success' => false,
                    'message' => $validator->messages()
                ]);
            }else {

                $social_media = SocialMedia::find($request->id);

                if(empty($social_media)) {
                    return response()->json([
                        'code'    => 404,
                        'success' => false,
                        'message' => 'item not found'
                    ]);
                }

                $social_media->update([
                    'item_social_media' => $request->edit_item_social_media,
                    'name_social_media' => $request->edit_name_social_media
                ]);
                store_log($request->ip(), Browser::browserName(), 'social-media','update',Auth::user()->id);

                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'success update item social media'
                ]);
            }

        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $social_media = SocialMedia::find($request->id);

            if(empty($social_media)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'soc not found'
                ]);
            }else {
                $social_media->delete();
                store_log($request->ip(), Browser::browserName(), 'social-media','delete',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Successfully delete item social media'
                ]);
            }
        }
    }
}

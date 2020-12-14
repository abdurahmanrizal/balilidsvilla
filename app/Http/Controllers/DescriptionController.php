<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;
use App\Models\Description;

class DescriptionController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Description::get();

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('description', function($data) {
                return $data->description_company;
            })
            ->addColumn('action', function($data) {
                 $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-description mb-2" data-id="'.$data->id.'">
                     <i class="fas fa-edit"></i> Edit
                 </button><br>';
                 $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-description" data-id="'.$data->id.'">
                     <i class="fas fa-trash"></i> Delete
                 </button>';
                 return $btn;
            })

            ->rawColumns(['description','action'])
            ->make(true);
        }
        $data_description = Description::get()->count();
        return view('admin.description.index',['count_data' => $data_description]);
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();
            $rules = [
                'content'  => 'required|string',
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
                Description::create([
                    'description_company' => $request->content
                ]);
                store_log($request->ip(), Browser::browserName(), 'description','store',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Success to store data description'
                ]);
            }

        }
    }

    public function show(Request $request)
    {
        if($request->ajax()) {
            $show_description = Description::find($request->id);

            if(empty($show_description)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Description not found'
                ]);
            }
            return response()->json([
                'code'    => 200,
                'success' => true,
                'message' => 'Description found',
                'data'    => $show_description
            ]);
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            $rules = [
                'content'  => 'required|string',
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
                $description = Description::find($request->id);
                if(empty($description)) {
                    return response()->json([
                        'code'    => 404,
                        'success' => false,
                        'message' => 'Description not found'
                    ]);
                }
                $description->update([
                    'description_company' => $request->content
                ]);
                store_log($request->ip(), Browser::browserName(), 'description','update',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Success update description company'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
       if($request->ajax()) {
            $description = Description::find($request->id);
            if(empty($description)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Description not found'
                ]);
            }

            $description->delete();
            store_log($request->ip(), Browser::browserName(), 'description','delete',Auth::user()->id);
            return response()->json([
                'code'    => 200,
                'success' => true,
                'message' => 'Success delete description company'
            ]);
       }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\User;
use App\Models\SocialMedia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DataTables;
use Browser;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Blog::with('user')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function($data) {
                    if(!file_exists(public_path("uploads/blog-post/photo/".$data->url))) {
                        return '-';
                    }
                    return '<img src="/uploads/blog-post/photo/' . $data->url . '?version=' . microtime() . '" alt="" class="img-thumbnail">';
                })
                ->addColumn('title', function($data) {
                   return $data->title;
                })
                ->addColumn('description', function($data) {
                    return $data->description;
                })
                ->addColumn('author', function($data) {
                    return $data->user['name'];
                })
                ->addColumn('action', function($data) {
                        $btn = '<button type="button" class="btn btn-outline-warning btn-sm edit-blog-post mb-2" data-id="'.$data->id.'">
                            <i class="fas fa-edit"></i> Edit
                        </button><br>';
                        $btn .= '<button type="button" class="btn btn-outline-danger btn-sm delete-blog-post" data-id="'.$data->id.'">
                            <i class="fas fa-trash"></i> Delete
                        </button>';
                        return $btn;
                })
                ->rawColumns(['thumbnail','action','title','description','author'])
                ->make(true);
        }
        return view('admin.blogs.index');
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $input = $request->all();

            if(empty($request->photo)) {
                $rules = [
                    'title'         => 'required|string',
                    'content'       => 'required|string',
                ];

            }else {
                $rules = [
                    'title'          => 'required|string',
                    'content'        => 'required|string',
                    'photo'          => 'max:2000|mimes:png,jpg,jpeg',
                ];
            }
            $messages = [
                'required' => ':attribute required cant empty',
                'string'   => ':attribute detect not number or text',
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
                if(empty($request->photo)) {
                    $blog_post = new Blog;
                    $blog_post->title = $request->title;
                    $blog_post->description = $request->content;
                    $blog_post->user_id   = Auth::user()->id;
                    $blog_post->url = '-';

                    $store_blog_post = $blog_post->save();

                    if(!$store_blog_post) {
                        return response()->json([
                            'code'    => 500,
                            'success' => false,
                            'message' => 'Something went wrong with our system'
                        ]);
                    }else {
                        store_log($request->ip(), Browser::browserName(), 'blog-post','store',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully add gallery'
                        ]);
                    }
                }else {
                    if ($file = $request->file('photo')) {

                        if (!file_exists(public_path("uploads/blog-post/photo"))) {
                            mkdir(public_path("uploads/blog-post/photo"), 0777, true);
                        }

                        $destinationPath = public_path("uploads/blog-post/photo");

                        $fileName = Str::random(40) . '.jpg';

                        $file->move($destinationPath, $fileName);

                        $blog_post = new Blog;
                        $blog_post->title = $request->title;
                        $blog_post->description = $request->content;
                        $blog_post->url = $fileName;
                        $blog_post->user_id = Auth::user()->id;

                        $store_blog_post = $blog_post->save();

                        if(!$store_blog_post) {
                            return response()->json([
                                'code'    => 500,
                                'success' => false,
                                'message' => 'Something went wrong with our system'
                            ]);
                        }else {
                            store_log($request->ip(), Browser::browserName(), 'blog-post','store',Auth::user()->id);
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
            $blog_post = Blog::find($request->id);
            if(empty($blog_post)) {
                return response()->json([
                    'code'    => 404,
                    'status'  => false,
                    'message' => 'Data blog post not found'
                ]);
            }else {
                return response()->json([
                    'code'    => 200,
                    'status'  => true,
                    'message' => 'Data blog post found',
                    'data'    => $blog_post
                ]);
            }
        }
    }

    public function update(Request $request)
    {
        if($request->ajax()) {
            $blog_post = Blog::find($request->id);

            if(empty($blog_post)) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data blog post not found'
                ]);
            }else {
                $input = $request->all();

                if(empty($request->photo)) {
                    $rules = [
                        'title'         => 'required|string',
                        'content'       => 'required|string',
                    ];

                }else {
                    $rules = [
                        'title'          => 'required|string',
                        'content'        => 'required|string',
                        'photo'          => 'max:2000|mimes:png,jpg,jpeg',
                    ];
                }

                $messages = [
                    'required' => ':attribute required cant empty',
                    'string'   => ':attribute detect not number or text',
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
                    if(empty($request->photo)) {
                        $blog_post->update([
                            'title'       => $request->title,
                            'description' => $request->content
                        ]);
                        store_log($request->ip(), Browser::browserName(), 'blog-post','update',Auth::user()->id);
                        return response()->json([
                            'code' => 200,
                            'success' => true,
                            'message' => 'Successfully update blog post'
                        ]);
                    }else {
                        if ($file = $request->file('photo')) {

                            if (file_exists(public_path("uploads/blog-post/photo/".$blog_post->url))) {
                                unlink(public_path("uploads/blog-post/photo/".$blog_post->url));
                            }

                            $destinationPath = public_path("uploads/blog-post/photo");

                            $fileName = Str::random(40) . '.jpg';

                            $file->move($destinationPath, $fileName);

                            $blog_post->update([
                                'title'       => $request->title,
                                'description' => $request->content,
                                'url'         => $fileName
                            ]);

                            store_log($request->ip(), Browser::browserName(), 'blog-post','update',Auth::user()->id);
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

    public function destroy(Request $request)
    {
        if($request->ajax()) {
            $data_blog_post = Blog::find($request->id);

            if(empty($data_blog_post)) {
                return response()->json([
                    'code'    => 404,
                    'success' => false,
                    'message' => 'Data blog post not found'
                ]);
            }else {
                $data_blog_post->delete();
                store_log($request->ip(), Browser::browserName(), 'blog-post','delete',Auth::user()->id);
                return response()->json([
                    'code'    => 200,
                    'success' => true,
                    'message' => 'Successfully delete blog post'
                ]);
            }
        }
    }

    public function post($id)
    {
        $blog_post = Blog::find($id);

        // return response()->json($blog_post);

        if(empty($blog_post)) {
            abort(404);
        }else {
            return view('layouts.client.blog',[
                'blog_post'  => $blog_post,
                'blog_posts' => $this->all_post($id),
                'social_medias' => $this->socialMedia()
            ]);
        }
    }

    private function all_post($id)
    {
        $blog_posts = Blog::where('id', '!=', $id)->get();
        return $blog_posts;
    }
    private function socialMedia()
    {
        $social_medias = SocialMedia::all();
        return $social_medias;
    }
}

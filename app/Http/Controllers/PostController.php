<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Get list of Posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts = Post::with("files")->get();
        return response()->json($posts);
    }

    /**
     * Create new post with upload file
     * @param StorePostRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePostRequest $request)
    {
//        dd($request->toArray());
        # Create and store Post model
        $post = new Post();
        $post->title = $request->input("title");
        $post->type = $request->input("type");
        $post->body = $request->input("body");
        $post->save();

        # Upload File if file is coming
        if ($request->file("file_upload")) {
            $random = rand(1, 10000000);
            $dateTime = Carbon::now();
            $image = $request->file("file_upload");
            $file_name = "{$post->type}-{$random}-{$dateTime}";
            $file_ext = $image->extension();
            $file_path = "public/posts";
            $file_fullName = "{$file_name}.{$file_ext}";
            $image->storeAs($file_path, $file_fullName);

            # Create and store File model to database
            $file = new File();
            $file->name = $file_name;
            $file->ext = $file_ext;
            $file->path = $file_path;
            $file->owner_id = $post->id;
            $file->owner_type = Post::class;
//          $file->owner()->associate($post);
            $file->save();
        }

        return response()->json($post);

    }

    /**
     * Update a Post
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        # Create and store Post model
        $post->title = $request->input("title");
        $post->body = $request->input("body");
        $post->save();

        if ($request->file("file_upload")) {
            # Upload File
            $random = rand(1,10000000);
            $dateTime = Carbon::now();
            $image = $request->file("file_upload");
            $file_name = "{$post->type}-{$random}-{$dateTime}";
            $file_ext = $image->extension();
            $file_path = "public/posts";
            $file_fullName = "{$file_name}.{$file_ext}";
            $image->storeAs($file_path, $file_fullName);

            # Create and store File model to database
            $file = new File();
            $file->name = $file_name;
            $file->ext = $file_ext;
            $file->path = $file_path;
            $file->owner_id = $post->id;
            $file->owner_type = Post::class;
            $file->save();
        }

        return response()->json($post);
    }

    /**
     * Show a Post
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Delete a post by admin user
     * @param Post $post
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Post $post)
    {
        if (!Auth::user()->isAdmin()) {
            abort(404);
        }
        $id = $post->id;
        $post->delete();
        return response()->json("deleted Post {$id} success");
    }
}

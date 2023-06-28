<?php

namespace App\Http\Controllers\api\user;;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CategoryPost;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with(['user' => function ($query) {
            $query->select('id', 'name');
        }])
            ->select('id', 'title', 'content', 'user_id')
            ->get();
        $response = [
            'message' => 'All posts that have been created.',
            'result' => $posts,
        ];
        return response($response, 201);
    }

    public function store(Request $request)
    {
        $user = User::find($request->user()->id);
        $user_name = $user->name;
        $request->validate(
            [
                'title' => 'required|string',
                'content' => 'required|string',
            ]
        );

        $post = Post::create(
            [
                'user_id' => $request->user()->id,
                'title' => $request->title,
                'content' => $request->content,
                'created_at' => Carbon::now(),
            ]
        );
        $categories_id = $request->category;
        if ($categories_id) {
            foreach ($categories_id as $id) {

                CategoryPost::create([
                    'post_id' => $post->id,
                    'category_id' => $id,
                ]);
            }
        }
        $response = [
            'message' => 'Your post created successfully.',
            'author' => $user_name,
            'result' => $post,
            'categories_ids' => $categories_id
        ];
        return response($response, 201);
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        $category_ids = CategoryPost::where('post_id', $post->id)->get();
        $categories = [];;
        $id_categories = $category_ids->pluck('category_id')->toArray();
        foreach ($id_categories as $id) {
            $category_name = Category::where('id', $id)->get('name');
            array_push($categories, $category_name);
        }
        $categories = array_unique($categories);
        $response = [
            'message' => 'show specific post.',
            'result' => $post,
            'categories' => $categories,
        ];
        return response($response, 201);
    }

    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        if ($request->user()->id == $post->user_id) {
            $request->validate(
                [
                    'title' => 'required|string',
                    'content' => 'required|string',
                ]
            );
            $post->update(
                [
                    'user_id' => $request->user()->id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'updated_at' => Carbon::now(),
                ]
            );
            $response = [
                'message' => 'Your post updated successfully.',
                'result' => $post,
            ];
        } else {
            $response = [
                'message' => "You can't update this post only owner of the post can update it.",
            ];
        }

        return response($response, 201);
    }

    public function destroy(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id == $request->user()->id) {
            $post->delete();
            $response = [
                'message' => "The post is deleted successfully.",
            ];
        } else {
            $response = [
                'message' => "You can't delete this post.",
            ];
        }
        return response($response, 201);
    }

    public function assign_category_to_post(Request $request, $id)
    {
        $post = Post::find($id);
        if ($request->user()->id == $post->user_id) {
            $categories_id = $request->category;
            if ($categories_id) {
                foreach ($categories_id as $id) {

                    CategoryPost::create([
                        'post_id' => $post->id,
                        'category_id' => $id,
                    ]);
                }
            }
            $category_ids = CategoryPost::where('post_id', $post->id)->get();
            $id_categories = $category_ids->pluck('category_id')->toArray();

            $categories = [];

            foreach ($id_categories as $id) {
                $category_name = Category::where('id', $id)->get('name');
                array_push($categories, $category_name);
            }
            $categories = array_unique($categories);

            $response = [
                'message' => 'Assigned category to post successfully.',
                'result' => $post,
                'categories_ids' => $categories_id,
                'categories' => $categories,
            ];
            return response($response, 201);
        } else {
            $response = [
                'message' => "You ca't access this post.",
            ];
            return response($response, 401);
        }
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        $results = Post::query()
            ->where('title', 'LIKE',  $searchTerm . '%')
            ->orWhere('content', 'LIKE',  $searchTerm . '%')
            ->get();
        $response = [
            'message' => "Shearched successfully.",
            'result' => $results,
        ];
        return response($response, 201);
    }
}

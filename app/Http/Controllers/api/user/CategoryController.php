<?php

namespace App\Http\Controllers\api\user;

use Carbon\Carbon;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $cateogries = Category::all();
        $response = [
            'message' => 'All categories that have been created.',
            'result' => $cateogries,
        ];
        return response($response, 201);
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string',
            ]
        );

        $category = Category::create(
            [
                'name' => $request->name,
                'created_at' => Carbon::now(),
            ]
        );
        $response = [
            'message' => 'Your category created successfully.',
            'result' => $category,
        ];
        return response($response, 201);
    }

    public function show(string $id)
    {
        $category = Category::find($id);
        $response = [
            'message' => 'show specific category.',
            'result' => $category,
        ];
        return response($response, 201);
    }

    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        $request->validate(
            [
                'name' => 'required|string',
            ]
        );
        $category->update(
            [
                'name' => $request->name,
                'updated_at' => Carbon::now(),
            ]
        );
        $response = [
            'message' => 'Category updated successfully.',
            'result' => $category,
        ];
        return response($response, 201);
    }
    public function destroy(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $response = [
            'message' => "The category is deleted successfully.",
        ];
        return response($response, 201);
    }
}

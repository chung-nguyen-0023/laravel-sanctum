<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tokenCan('categories-list')) {
            abort(403, 'Unauthorized');
        }
        $categories = Category::get();
        return CategoryResource::collection($categories);
    }

    public function show($id)
    {
        if (!auth()->user()->tokenCan('categories-show')) {
            abort(403, 'Unauthorized');
        }
        $category = Category::find($id);
        return new CategoryResource($category);
    }
}

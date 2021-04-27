<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tokenCan('categories-view')) {
            abort(403, 'Unauthorized');
        }
        $categories = Category::get();
        return response()->json([
            'status_code' => 200,
            'data' => $categories,
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->tokenCan('categories-view')) {
            abort(403, 'Unauthorized');
        }
        $category = Category::find($id);
        return response()->json([
            'status_code' => 200,
            'data' => $category,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->tokenCan('categories-create')) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Validate fail',
                'error' => $validator->errors(),
            ]);
        }

        if (Category::where('name', $request->get('name'))->first()) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Category already exist',
            ]);
        }
        $data = $request->all();
        $data['slug'] = Str::slug($data['name'], '-');
        $category = Category::create($data);

        return response()->json([
            'status_code' => 200,
            'data' => $category,
        ]);
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->tokenCan('categories-update')) {
            abort(403, 'Unauthorized');
        }

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return response()->json([
            'status_code' => 200,
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->tokenCan('categories-delete')) {
            abort(403, 'Unauthorized');
        }

        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'status_code' => 200,
            'success' => true,
        ]);
    }
}

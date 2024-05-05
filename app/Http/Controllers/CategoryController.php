<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['checkModule:categories','can:manage_categories']);
    }

    public function index()
    {
      
        return view('categories.index');
    }



    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('categories.index')->with('success', 'Category restored successfully.');
    }
}

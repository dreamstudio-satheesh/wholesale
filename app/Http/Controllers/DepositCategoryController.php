<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\DepositCategory;

class DepositCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_deposit_categories');
    }

    public function index(): View
    {
        $depositCategories = DepositCategory::orderBy('id', 'desc')->get();
        return view('deposit_categories.index', compact('depositCategories'));
    }

    // POST /accounting/deposit_category
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:192',
        ]);

        $depositCategory = DepositCategory::create($validatedData);

        return redirect()
            ->route('deposit_category.index')
            ->with('success', 'Deposit category created successfully.');
    }

    // PUT/PATCH /accounting/deposit_category/{deposit_category}
    public function update(Request $request, DepositCategory $deposit_category)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:192',
        ]);

        $deposit_category->update($validatedData);

        return redirect()
            ->route('deposit_category.index')
            ->with('success', 'Deposit category updated successfully.');
    }

    // DELETE /accounting/deposit_category/{deposit_category}
    public function destroy(DepositCategory $deposit_category)
    {
        $deposit_category->delete();

        return redirect()
            ->route('deposit_category.index')
            ->with('success', 'Deposit category deleted successfully.');
    }

    public function delete_by_selection(Request $request)
    {
        $selectedIds = $request->selectedIds;
        DepositCategory::whereIn('id', $selectedIds)->delete();

        // If you want to soft delete the selected categories
        // DepositCategory::whereIn('id', $selectedIds)->each(function ($category) {
        //     $category->delete();
        // });

        return redirect()
            ->route('deposit_category.index')
            ->with('success', 'Selected deposit categories deleted successfully.');
    }
}

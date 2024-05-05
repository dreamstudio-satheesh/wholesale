<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage_expense_categories');
    }

    public function index(): View
    {
        $expenseCategories = ExpenseCategory::orderBy('id', 'desc')->get();
        return view('expense_categories.index', compact('expenseCategories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:192',
        ]);

        $expenseCategory = ExpenseCategory::create($validatedData);

        return redirect()
            ->route('expense_category.index')
            ->with('success', 'Expense category created successfully.');
    }

    public function update(Request $request, ExpenseCategory $expense_category)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:192',
        ]);

        $expense_category->update($validatedData);

        return redirect()
            ->route('expense_category.index')
            ->with('success', 'Expense category updated successfully.');
    }

    public function destroy(ExpenseCategory $expense_category)
    {
        $expense_category->delete();

        return redirect()
            ->route('expense_category.index')
            ->with('success', 'Expense category deleted successfully.');
    }

    public function delete_by_selection(Request $request)
    {
        $selectedIds = $request->input('selectedIds'); // Ensure you're getting the input correctly
        ExpenseCategory::whereIn('id', $selectedIds)
            ->get()
            ->each->delete(); // This ensures soft deleting

        return redirect()
            ->route('expense_category.index')
            ->with('success', 'Selected expense categories deleted successfully.');
    }
}

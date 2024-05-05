<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Account;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_expense')->only('index');
        $this->middleware('can:create_expense')->only(['create', 'store']);
        $this->middleware('can:edit_expense')->only(['edit', 'update']);
        $this->middleware('can:delete_expense')->only('destroy');
        // Add more middleware as needed for other permissions
    }
    
    public function index()
    {
        $expenses = Expense::with(['account', 'category', 'payment'])
            ->latest()
            ->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $accounts = Account::all();
        $categories = ExpenseCategory::all();
        $paymentMethods = PaymentMethod::all();
        return view('expenses.create', compact('accounts', 'categories', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'expense_ref' => 'required|string|max:192',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);

        $data = $request->only(['account_id', 'expense_category_id', 'amount', 'payment_method_id', 'date', 'expense_ref', 'description']); // Adjust for expense fields

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Expense::create($data);

        return redirect()->route('expense.index')->with('success', 'Expense created successfully.'); // Adjust route names
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $accounts = Account::all();
        $categories = ExpenseCategory::all();
        $paymentMethods = PaymentMethod::all();
        return view('expenses.edit', compact('expense', 'accounts', 'categories', 'paymentMethods'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'expense_ref' => 'required|string|max:192', // Adjust for expense reference
            'description' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);

        $data = $request->only(['account_id', 'expense_category_id', 'amount', 'payment_method_id', 'date', 'expense_ref', 'description']); // Adjust for expense fields

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::delete($expense->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $expense->update($data);

        return redirect()->route('expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->attachment) {
            Storage::delete($expense->attachment);
        }

        $expense->delete();

        return redirect()->route('expense.index')->with('success', 'Expense deleted successfully.');
    }
}

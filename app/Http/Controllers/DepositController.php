<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Account;
use App\Models\DepositCategory;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_deposit')->only('index');
        $this->middleware('can:create_deposit')->only(['create', 'store']);
        $this->middleware('can:edit_deposit')->only(['edit', 'update']);
        $this->middleware('can:delete_deposit')->only('destroy');
        // Add more middleware as needed for other permissions
    }
     
    public function index()
    {
        
        $deposits = Deposit::with(['account', 'category', 'payment'])->latest()->get();
        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        $accounts = Account::all();
        $categories = DepositCategory::all();
        $paymentMethods = PaymentMethod::all();
        return view('deposits.create', compact('accounts', 'categories', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'deposit_category_id' => 'required|exists:deposit_categories,id',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'deposit_ref' => 'required|string|max:192',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);

        $data = $request->only(['account_id', 'deposit_category_id', 'amount', 'payment_method_id', 'date', 'deposit_ref', 'description']);

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Deposit::create($data);

        return redirect()->route('deposit.index')->with('success', 'Deposit created successfully.');
    }

    public function show(Deposit $deposit)
    {
        return view('deposits.show', compact('deposit'));
    }

    public function edit(Deposit $deposit)
    {
        $accounts = Account::all();
        $categories = DepositCategory::all();
        $paymentMethods = PaymentMethod::all();
        return view('deposits.edit', compact('deposit', 'accounts', 'categories', 'paymentMethods'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'deposit_category_id' => 'required|exists:deposit_categories,id',
            'amount' => 'required|numeric',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'date' => 'required|date',
            'deposit_ref' => 'required|string|max:192',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file',
        ]);

        $data = $request->only(['account_id', 'deposit_category_id', 'amount', 'payment_method_id', 'date', 'deposit_ref', 'description']);

        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists and uploading a new one
            if ($deposit->attachment) {
                Storage::delete($deposit->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $deposit->update($data);

        return redirect()->route('deposit.index')->with('success', 'Deposit updated successfully.');
    }

    public function destroy(Deposit $deposit)
    {
        if ($deposit->attachment) {
            Storage::delete($deposit->attachment);
        }
        
        $deposit->delete();

        return redirect()->route('deposit.index')->with('success', 'Deposit deleted successfully.');
    }
}

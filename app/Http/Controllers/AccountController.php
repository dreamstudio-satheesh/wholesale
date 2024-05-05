<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Account;
use Illuminate\View\View;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_account')->only('index');
        $this->middleware('can:create_account')->only(['create', 'store']);
        $this->middleware('can:edit_account')->only(['edit', 'update']);
        $this->middleware('can:delete_account')->only('destroy');
        // Add more middleware as needed for other permissions
    }
    
    public function index(): View
    {
        $accounts = Account::where('deleted_at', '=', null)
            ->orderBy('id', 'desc')
            ->get();
        return view('accounts.account_list', compact('accounts'));
    }

    public function create()
    {
        return view('accounts.create_account');
    }
    public function store(Request $request)
    {
        
            $this->validate($request, [
                'account_num' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
                'initial_balance' => 'required|numeric',
            ]);

            Account::create([
                'account_num' => $request['account_num'],
                'account_name' => $request['account_name'],
                'initial_balance' => $request['initial_balance'],
                'note' => $request['note'],
            ]);

            return redirect()->route('account.index')->with('success', 'Account created successfully.');

        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
            $account = Account::where('deleted_at', '=', null)->findOrFail($id);
            return view('accounts.edit_account', compact('account'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       
            $this->validate($request, [
                'account_num' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
            ]);

            Account::whereId($id)->update([
                'account_num' => $request['account_num'],
                'account_name' => $request['account_name'],
                'note' => $request['note'],
            ]);

          //  return response()->json(['success' => true]);

          return redirect()->route('account.index')->with('success', 'Account updated successfully.');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            Account::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return redirect()->route('account.index')->with('success', 'Account Deleted successfully.');
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
       
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $account_id) {
                Account::whereId($account_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
       
    }
}

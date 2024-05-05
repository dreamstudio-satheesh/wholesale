<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Customers extends Component
{
    use WithPagination;

    public $name, $phone, $email, $address, $customer_id;
    public $search = '';
    public $sortField = 'name'; // default sort field
    public $sortDirection = 'asc'; // or 'desc'
    protected $paginationTheme = 'bootstrap';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $userCanViewAllCustomers = auth()->user()->hasPermissionTo('view_all_customers');

        $customers = Customer::where('id', '!=', 1)
            ->when(!$userCanViewAllCustomers, function ($query) {
                return $query->where('created_by', auth()->id()); // Filter to current user's sales
            })
            ->where(function ($query) {
                $query
                    ->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('customers.main', [
            'customers' => $customers,
        ]);
    }

    public function create()
    {
        $this->resetCreateForm();
        // $this->reset();
    }

    private function resetCreateForm()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->customer_id = '';
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'address' => 'nullable',
        ]);
        
        // Base data for either update or create
        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ];
        
        // Determine if we are updating or creating
        if (!$this->customer_id) {
            // If creating a new customer, add the 'created_by' field
            $createData = array_merge($data, ['created_by' => Auth::id()]);
            $customer = Customer::create($createData);
        } else {
            // If updating, find the existing customer and update their information
            $customer = Customer::where('id', $this->customer_id)->update($data);
        }

        $message = $this->customer_id ? 'Customer updated.' : 'Customer created.';
        $this->dispatch('show-toastr', ['message' => $message]);
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->customer_id = $id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->email = $customer->email;
        $this->address = $customer->address;
    }

    public function delete($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->deleted_by = Auth::id();
            $customer->save();
            $customer->delete();
            $this->dispatch('show-toastr', ['message' => 'Customer deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Customer not found.']);
        }
    }
}

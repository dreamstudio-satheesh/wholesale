<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Suppliers extends Component
{
    use WithPagination;

    public $name, $phone, $email, $address, $supplier_id;

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
        $userCanViewAllSuppliers = auth()->user()->hasPermissionTo('view_all_suppliers');
        $suppliers = Supplier::when(!$userCanViewAllSuppliers, function ($query) {
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

        return view('suppliers.main', [
            'suppliers' => $suppliers,
        ]);
    }

    public function create()
    {
        $this->resetCreateForm();
    }

    private function resetCreateForm()
    {
        $this->name = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->supplier_id = null;
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable|email',
            'address' => 'nullable',
        ]);
        
        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ];
        
        if (!$this->supplier_id) {
            // If creating a new supplier, add 'created_by' and use create method directly
            $data['created_by'] = Auth::id();
            $supplier = Supplier::create($data);
        } else {
            // If updating, ensure 'id' is not part of $data to avoid mass assignment issues
            // and use update method on the found supplier
            $supplier = Supplier::where('id', $this->supplier_id)->update($data);
        }
        

        $message = $this->supplier_id ? 'Supplier updated.' : 'Supplier created.';
        $this->dispatch('show-toastr', ['message' => $message]);

        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->supplier_id = $id;
        $this->name = $supplier->name;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
        $this->address = $supplier->address;
    }

    public function delete($id)
    {
        $supplier = Supplier::find($id);

        if ($supplier) {
            $supplier->deleted_by = Auth::id();
            $supplier->save();
            $supplier->delete();
            $this->dispatch('show-toastr', ['message' => 'Supplier deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Supplier not found.']);
        }
    }
}

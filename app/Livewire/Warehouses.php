<?php

// app/Http/Livewire/Warehouses.php

namespace App\Livewire;

use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;

class Warehouses extends Component
{
    use WithPagination;
    public $search = '';
    public $sortField = 'name'; // default sort field
    public $sortDirection = 'asc'; // or 'desc'
    public $name, $address, $pincode, $phone, $city, $warehouse_id;

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
        //$warehouses = Warehouse::paginate(10);
        $warehouses = Warehouse::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')->orWhere('phone', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        return view('warehouses.main', compact('warehouses'));
    }

    public function create()
    {
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->address = '';
        $this->pincode = '';
        $this->phone = '';
        $this->city = '';
        $this->warehouse_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'address' => 'nullable',
            'pincode' => 'nullable',
            'phone' => 'nullable',
            'city' => 'nullable',
        ]);

        Warehouse::updateOrCreate(
            ['id' => $this->warehouse_id],
            [
                'name' => $this->name,
                'address' => $this->address,
                'pincode' => $this->pincode,
                'phone' => $this->phone,
                'city' => $this->city,
            ],
        );

        $message = $this->warehouse_id ? 'Warehouse updated.' : 'Warehouse created.';
        $this->dispatch('show-toastr', ['message' => $message]);
        $this->resetForm();
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $this->warehouse_id = $id;
        $this->name = $warehouse->name;
        $this->address = $warehouse->address;
        $this->pincode = $warehouse->pincode;
        $this->phone = $warehouse->phone;
        $this->city = $warehouse->city;
    }

    public function delete($id)
    {
        $warehouse = Warehouse::find($id);

        if ($warehouse) {
            $warehouse->delete();
            $this->dispatch('show-toastr', ['message' => 'Warehouse deleted successfully.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Warehouse not found.']);
        }
    }
}

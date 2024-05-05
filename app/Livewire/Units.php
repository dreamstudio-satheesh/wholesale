<?php

namespace App\Livewire;

use App\Models\Unit;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Units extends Component
{
    public  $name, $shortName, $baseUnit, $operator = '*', $operatorValue, $unit_id;
    public $search = '';
    public $sortField = 'name'; // default sort field
    public $sortDirection = 'asc'; // or 'desc'
    use WithPagination;
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
        $units = Unit::paginate(10);  
        
        $units = Unit::where(function ($query) {
            $query
                ->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('base_unit', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        $baseUnits = Unit::whereNull('base_unit')->get();
        return view('units.main',compact('units','baseUnits'));
    }

    public function create()
    {
        $this->resetCreateForm();
    }

    private function resetCreateForm(){
        $this->name = '';
        $this->shortName = '';
        $this->baseUnit = '';
        $this->operator = '*';
        $this->operatorValue = '';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'shortName' => 'required',
        ]);

        if ($this->baseUnit == '') {
           $this->operator = '*';
           $this->operatorValue = 1;
           $this->baseUnit = NULL;
        } else {
            $this->validate([
                'operator' => 'required',
                'operatorValue' => 'required|numeric',
            ]);
           
        }

        if (!$this->unit_id) {
            $data['created_by'] = Auth::id();
        }


        Unit::updateOrCreate(['id' => $this->unit_id], [
            'name' => $this->name,
            'short_name' => $this->shortName,
            'base_unit' => $this->baseUnit,
            'operator' => $this->operator,
            'operator_value' => $this->operatorValue
        ]);

        $message = $this->unit_id ? 'Unit updated.' : 'Unit created.';
        $this->dispatch('show-toastr', ['message' => $message]);
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $Unit = Unit::findOrFail($id);
        $this->unit_id = $id;
        $this->name = $Unit->name;
        $this->shortName = $Unit->short_name;
        $this->baseUnit = $Unit->base_unit;
        $this->operator = $Unit->operator;
        $this->operatorValue = $Unit->operator_value;
    }

    public function delete($id)
    {
        $unit = Unit::find($id);
        if ($unit) {
            $unit->deleted_by = Auth::id();
            $unit->save();
            $unit->delete();
            $this->dispatch('show-toastr', ['message' => 'Unit deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Unit not found.']);
        }
    }
}

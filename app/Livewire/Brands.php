<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Brands extends Component
{
    use WithPagination, WithFileUploads;
    public $name, $brand_id, $image;
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
        // $brands = Brand::paginate(10);
        $brands = Brand::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('brands.main', compact('brands'));
    }

    public function create()
    {
        $this->resetCreateForm();
    }

    private function resetCreateForm()
    {
        $this->name = '';
        $this->category_id = '';
    }

    public function saveBrand()
    {
        $brand = Brand::updateOrCreate(
            ['id' => $this->brand_id],
            [
                'name' => $this->name,
                'image' => 'nullable|image',
            ],
        );

        if (!$this->brand_id) {
            $data['created_by'] = Auth::id();
        }

       /*  if ($this->image) {
           
            // Use addMediaFromDisk for Livewire's temporary uploaded file
            $brand->addMedia($this->image->getRealPath())->usingFileName($this->image->getClientOriginalName())->toMediaCollection('beands');
        }  */


        if ($this->image) {
            if ($brand->hasMedia('brands')) {
                $brand->clearMediaCollection('brands');
            }

            $brand->addMedia($this->image->getRealPath())->toMediaCollection('brands');

            $this->image = null;
        }

        $message = $this->brand_id ? 'Brand updated.' : 'Brand created.';
        $this->dispatch('show-toastr', ['message' => $message]);
       
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $this->brand_id = $id;
        $this->name = $brand->name;
    }

    public function delete($id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            $brand->deleted_by = Auth::id();
            $brand->save();
            $brand->delete();
            $this->dispatch('show-toastr', ['message' => 'Brand deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Brand not found.']);
        }

        
    }
}

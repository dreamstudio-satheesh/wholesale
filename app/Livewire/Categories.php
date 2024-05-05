<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Categories extends Component
{
    use WithPagination;

    public $name, $parent_id=null, $category_id;
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
        //$categories = Category::with('parent')->paginate(10);
        $categories = Category::where(function ($query) {
            $query
                ->where('name', 'like', '%' . $this->search . '%');
        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('categories.main', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ]);
    }

    public function create()
    {
        $this->resetCreateForm();
    }


    private function resetCreateForm()
    {
        $this->name = '';
        $this->category_id = '';
        $this->parent_id = '';
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required',
        ]);

        if (!$this->category_id) {
            $data['created_by'] = Auth::id();
        }

        $parent_id = $this->parent_id === '' ? null : $this->parent_id;
        
        Category::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->name,
                'parent_id' => $parent_id,
            ],
        );

        $message = $this->category_id ? 'Category updated.' : 'Category created.';
        $this->dispatch('show-toastr', ['message' => $message]);
        $this->resetCreateForm();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->parent_id =$category->parent_id;
    }

    public function delete($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->deleted_by = Auth::id();
            $category->save();
            $category->delete();
            $this->dispatch('show-toastr', ['message' => 'Category deleted.']);
        } else {
            $this->dispatch('show-toastr', ['message' => 'Category not found.']);
        }

        
    }
}

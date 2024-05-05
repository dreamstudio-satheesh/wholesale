<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Transfer; 

class TransferStock extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
    }
    public function render()
    {
       $stocks=Transfer::with(['items.product', 'from_warehouse', 'to_warehouse'])->paginate(10);
        
        return view('stocks.transfer-stock', compact('stocks'));
    }
    
}

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    Update Stock
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="updateStock">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" id="date" class="form-control" wire:model="date" disabled>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($moduleStatuses['warehouses'])
                            <div class="form-group">
                                <label for="warehouse">Warehouse</label>
                                <select id="warehouse" class="form-control" wire:model="warehouse_id">
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="searchInput">Product</label>
                            <input id="searchInput" type="text" class="form-control"
                                wire:model.live.debounce.500ms="search"
                                placeholder="Scan/Search Product by code or name" autofocus autocomplete="off">
                            @error('selectedProductId')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="list-group mt-2">
                                @foreach ($searchResults as $result)
                                    <a href="#" class="list-group-item list-group-item-action"
                                        wire:click.prevent="selectProduct({{ $result->id }},{{ $result->variant_id }})">
                                        {{ $result->name }} (SKU: {{ $result->sku }})
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        @if ($selectedProductId)
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Product Name and SKU:</label>
                                <div class="col-sm-8">
                                    <input type="text" id="date" class="form-control"
                                        value=" {{ $selectedProductName }} (SKU: {{ $selectedProductSKU }})" disabled>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Current Stock:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" value=" {{ $currentStock }}" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Quantity</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" wire:model="quantity">
                                    @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <select class="form-control" wire:model.live="type">
                                        <option value="Addition">Addition</option>
                                        <option value="Subtraction">Subtraction</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 col-form-label">Reason</label>
                                <div class="col-sm-10">
                                    <select class="form-control" wire:model="movement_reason">
                                        @if ($type === 'Addition')
                                            @foreach ($additionReasons as $reason)
                                                <option value="{{ $reason }}">{{ str_replace('_', ' ', $reason) }}
                                                </option>
                                            @endforeach
                                        @elseif($type === 'Subtraction')
                                            @foreach ($subtractionReasons as $reason)
                                                <option value="{{ $reason }}">{{ str_replace('_', ' ', $reason) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('movement_reason')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
    
                            </div>
                        @endif

                        

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Update Stock</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

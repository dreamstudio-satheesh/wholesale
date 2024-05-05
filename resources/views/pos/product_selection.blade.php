        <div class="row">
            <div class="col-md-6 mb-2">
                @if ($moduleStatuses['warehouses'])
                    <select id="warehouseDropdown" class="form-control">
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <div class="col-md-6 mb-2">
                <select id="categorySelect" class="form-control">
                    <option value="">All Category</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div id="productContainer" class="row"></div>
        <div id="paginationContainer"></div>

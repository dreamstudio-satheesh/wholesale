<div class="col-lg-8 offset-md-2">
    <div class="card card-default">

        <div class="card-header card-header-border-bottom">
            <h2>Module Settings </h2>
        </div>
        <div class="card-body">
    
            <ul class="">
    
                @foreach ($settings as $key => $value)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <h5 class="mt-0 mb-2 text-dark"> {{ ucfirst(str_replace('_', ' ', $key)) }}</h5>
                        <label class="switch switch-primary switch-pill form-control-label">
        
                            <input type="checkbox" class="switch-input form-check-input" id="{{ $key }}"
                                wire:model.change="settings.{{ $key }}" {{ $value ? 'checked' : '' }}>
                            <span class="switch-label"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </li>
                @endforeach
        
        
            </ul>
    
        </div>
    
        
    </div>
</div>


<div>
    <div>
        <button wire:click="queryOpenAI">Query OpenAI</button>
    
        @if($response)
            <div>
                <pre>{{ json_encode($response, JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif
    </div>
    
</div>

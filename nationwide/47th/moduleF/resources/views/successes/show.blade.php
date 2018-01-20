<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    @foreach ($data as $item)
        @if (is_object($item))
            
        @else
            {{ $item }}
        @endif
    @endforeach
</data>

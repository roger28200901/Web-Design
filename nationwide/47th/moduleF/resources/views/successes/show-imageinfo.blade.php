<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    @foreach ($data as $key => $value)
        <{{ $key }}>{{ $value }}</{{ $key }}>
    @endforeach
</data>

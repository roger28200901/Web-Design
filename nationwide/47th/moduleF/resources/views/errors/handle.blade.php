<?xml version="1.0" encoding="UTF-8"?>
@if (isset($message))
    <data type="string" success="0" status="{{ $status_code }}">{{ $message }}</data>
@else
    <data type="string" success="0" status="{{ $status_code }} />
@endif
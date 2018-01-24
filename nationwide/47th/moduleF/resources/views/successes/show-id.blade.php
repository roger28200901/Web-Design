<?xml version="1.0" encoding="UTF-8"?>
@if (isset($id))
    <data type="string" success="1" status="200">{{ $id }}</data>
@else
    <data type="string" success="1" status="200"/>
@endif

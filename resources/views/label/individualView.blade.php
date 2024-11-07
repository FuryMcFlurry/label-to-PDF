@extends('layouts.app')

@section('title', 'Individual shipment')

@section('content')
    <div id="label-view" data-shipment-id="{{ $shipmentId }}"></div>
@endsection
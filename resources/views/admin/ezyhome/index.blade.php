@extends('layouts.admin')

@section('title', 'EzyHome Topology')

@section('content')
    @include('admin.components.ezyhome-topology', [
        'topology'     => $topology ?? null,
        'deviceList'   => $deviceList ?? [],
        'deviceTypes'  => $deviceTypes ?? [],
        'protocols'    => $protocols ?? [],
        'networkAreas' => $networkAreas ?? collect(),
        'isSuperAdmin' => $isSuperAdmin ?? false,
        'title'        => 'Map: EzyHome Topology',
    ])
@endsection

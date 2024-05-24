@extends('layouts.backend.admin')

@section('content')
    @if (Auth::user()->role == 'Admin')
        <div class="row">
            @include('admin.dashboard_component.card1', [
                'count' => $users,
                'title' => 'Users',
                'subtitle' => 'Total users',
                'color' => 'primary',
                'icon' => 'user',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $customers,
                'title' => 'Customers',
                'subtitle' => 'Total Customers',
                'color' => 'success',
                'icon' => 'user',
            ])
        </div>
    @elseif(Auth::user()->role == 'Supir')
        @include('admin.dashboard_component.supir')
    @else
        @include('admin.dashboard_component.user')
    @endif
@endsection

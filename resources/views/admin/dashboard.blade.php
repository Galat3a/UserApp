@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Usuarios</h5>
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
        
        <!-- Verified Users Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Usuarios Verificados</h5>
                    <h2>{{ $verifiedUsers }}</h2>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    Actividad Reciente
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($recentActivity as $activity)
                        <li class="list-group-item">
                            {{ $activity->description }}
                            <small class="text-muted float-end">
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

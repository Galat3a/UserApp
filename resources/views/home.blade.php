@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 class="mb-3">👋 Hola, {{ Auth::user()->name }}</h2>
                            <p class="text-muted">Bienvenido a tu panel personal. Aquí podrás gestionar tu cuenta.</p>
                            <div class="mt-3">
                                <p class="mb-1">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Miembro desde: {{ Auth::user()->created_at->format('d/m/Y') }}
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-envelope me-2"></i>
                                    Estado del correo: 
                                    @if(Auth::user()->email_verified_at)
                                        <span class="badge bg-success">Verificado</span>
                                    @else
                                        <span class="badge bg-warning">Pendiente de verificación</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h4 class="mb-4">Accesos Rápidos</h4>
        </div>
        <div class="col-12 d-flex justify-content-center gap-4">
            <div style="max-width: 350px;">
                <a href="{{ route('profile.show') }}" class="text-decoration-none">
                    <div class="card card-hover cursor-pointer">
                        <div class="card-body text-center p-4">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center mb-3 mx-auto">
                                <i class="fas fa-user-edit fa-2x text-white"></i>
                            </div>
                            <h5>Editar Perfil</h5>
                            <p class="text-muted small">Actualiza tus datos personales</p>
                        </div>
                    </div>
                </a>
            </div>

            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
            <div style="max-width: 350px;">
                <a href="{{ route('users.index') }}" class="text-decoration-none">
                    <div class="card card-hover cursor-pointer">
                        <div class="card-body text-center p-4">
                            <div class="icon icon-shape bg-gradient-success shadow text-center mb-3 mx-auto">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                            <h5>Gestionar Usuarios</h5>
                            <p class="text-muted small">Administra los usuarios del sistema</p>
                        </div>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Estado de la Cuenta -->
</div>
@endsection
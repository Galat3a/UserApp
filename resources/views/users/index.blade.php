@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-dark">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="text-white mb-0">Gestión de Usuarios</h3>
                </div>
                <div class="col text-end">
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if(session('status'))
                <div class="alert alert-success m-3">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger m-3">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0 px-4">Usuario</th>
                            <th class="border-top-0">Email</th>
                            <th class="border-top-0">Estado</th>
                            <th class="border-top-0">Rol</th>
                            <th class="border-top-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @if(auth()->user()->role === 'admin' && $user->id === 1)
                                @continue
                            @endif
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md rounded-circle bg-gradient-{{ $user->role === 'admin' ? 'primary' : 'info' }} me-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i>Verificado
                                        </span>
                                    @else
                                        <span class="badge bg-warning rounded-pill">
                                            <i class="fas fa-clock me-1"></i>Pendiente
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'info' }} rounded-pill">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </a>
                                        
                                        <!-- Permitir verificación del propio correo -->
                                        @if(!$user->email_verified_at)
                                        <form action="{{ route('users.verify', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i> Verificar
                                            </button>
                                        </form>
                                        @endif

                                        <!-- Solo mostrar botón eliminar si NO es el usuario actual -->
                                        @if(auth()->id() !== $user->id && $user->id !== 1)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i> Eliminar
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}
.table > :not(caption) > * > * {
    padding: 1rem 0.5rem;
}
.delete-form button:hover {
    transform: scale(1.05);
}
.btn {
    transition: all 0.2s;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Confirmar eliminación
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
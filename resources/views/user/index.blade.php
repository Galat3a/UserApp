@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Gestión de Usuarios') }}</span>
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Crear Usuario</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                   
                                    <th>Verificado</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="">✅</span>
                                        @else
                                            <span class="">❌</span>
                                        @endif
                                    </td>
                                    @if ($user->id === 1)
                                        <td>Super-Admin</td>
                                    @else    
                                    <td>{{ ucfirst($user->role) }}</td>
                                    @endif
                               
                                    
                                    <td>
                                        <div class="btn-group gap-3" role="group">
                                            <a href="{{ route('user.edit', $user) }}" class="btn btn-info btn-sm">Editar</a>
                                            @if(auth()->user()->id !== $user->id && $user->id !== 1)
                                                <form action="{{ route('user.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Are you sure you want to delete this user?')">Eliminar</button>
                                                </form>
                                            @endif
                                            @if(!$user->email_verified_at)
                                            <form method="POST" action="{{ route('user.verify', $user->id) }}" style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to verify this user?')">Verificar</button>
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
    </div>
</div>
@endsection
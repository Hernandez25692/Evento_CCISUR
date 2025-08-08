@extends('layouts.app')

@section('content')
    <div class="container py-5" style="background: #f8fafc; min-height: 100vh;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary mb-0">
                <i class="fa fa-users me-2"></i> Gestión de Usuarios
            </h2>
            <a href="{{ route('usuarios.create') }}" class="btn btn-lg btn-success shadow">
                <i class="fa fa-user-plus me-2"></i> Nuevo Usuario
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        <div class="card shadow-lg border-0 rounded-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Fecha de registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td class="text-center fw-bold">{{ $usuario->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width:36px; height:36px;">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <span>{{ $usuario->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $usuario->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fa fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-user-slash fa-2x mb-2"></i><br>
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-center">
                    {{ $usuarios->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

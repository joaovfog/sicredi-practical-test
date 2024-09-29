@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between">
        <h3>Usuários</h3>
        <div class="d-flex flex-column align-items-center">
            <span>
                Tipo do Usuário
            </span>
            <span class="text-uppercase">
                {{ Auth::user()->user_type }}
            </span>
        </div>
        @if(Auth::user()->user_type === 'administrador')
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal" data-action="create">
                Adicionar
            </button>
        @endif
    </div>

    @if(session('success'))
        <div id="success-message" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(Auth::user()->user_type === 'administrador')
        @if($users->isEmpty())
            <p>Nenhum usuário cadastrado.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form 
                                    action="{{ route('users.destroy', $user->id) }}" 
                                    method="POST" 
                                    class="d-inline" 
                                    onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal" 
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-user_type="{{ $user->user_type }}">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @else
        <div class="alert alert-warning">Você não tem permissão para visualizar os usuários.</div>
    @endif
</div>

<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Adicionar usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('users.createUser') }}" id="userForm">
                    @csrf
                    <input type="hidden" id="userId" name="userId">
                    <input type="hidden" name="_method" id="userMethod" value="POST">

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text-muted" id="passwordNote" style="display: none;">Deixe em branco se não quiser alterar a senha.</small>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>

                    <div class="mb-3">
                        <label for="user_type" class="form-label">Tipo de Usuário</label>
                        <select class="form-control" id="user_type" name="user_type" required>
                            <option value="usuário">Usuário</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);

    document.addEventListener('DOMContentLoaded', function() {
        const userForm = document.getElementById('userForm');
        const createUserModal = document.getElementById('createUserModal');

        createUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const action = button.getAttribute('data-action');

            if (action === 'create') {
                // create
                document.getElementById('userId').value = '';
                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('user_type').value = 'usuário';
                document.getElementById('createUserModalLabel').innerText = 'Adicionar usuário';
                userForm.action = "{{ route('users.createUser') }}";
                document.getElementById('userMethod').value = 'POST';
                document.getElementById('passwordNote').style.display = 'none';
            } else {
                // edit
                const userId = button.getAttribute('data-id');
                const userName = button.getAttribute('data-name');
                const userEmail = button.getAttribute('data-email');
                const userType = button.getAttribute('data-user_type');

                document.getElementById('userId').value = userId;
                document.getElementById('name').value = userName;
                document.getElementById('email').value = userEmail;
                document.getElementById('user_type').value = userType;

                document.getElementById('createUserModalLabel').innerText = 'Editar usuário';
                userForm.action = "{{ url('users') }}/" + userId;
                document.getElementById('userMethod').value = 'PUT';
                document.getElementById('passwordNote').style.display = 'block';
            }
        });
    });
</script>
@endsection

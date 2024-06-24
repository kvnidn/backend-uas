@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="">
                <div class="users">
                    <h4>Users <a href="#" class="add-user" id="opencreateModalUser"> Add User</a></h4>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Name</th>
                                <th class="email-heading">Email</th>
                                <th class="password-heading">Password</th>
                                <th class="role-heading">Role</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>*****</td>
                                <td class="{{ $item->role == 'Admin' ? 'admin-status' : 'lecturer-status' }}">{{ $item->role }}</td>
                                <td>
                                    <a href="#" class="edit-button-user" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-email="{{ $item->email }}" data-role="{{ $item->role }}">Edit</a>
                                    <a href="{{ url('user/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
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

<!-- Create User Modal -->
<div id="createModalUser" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Create User</h4>
        <form action="{{ url('/user/create') }}" method="POST">
            @csrf
            <div class="form-name">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}"/>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-email">
                <label>Email</label>
                <input type="text" name="email" value="{{ old('email') }}"/>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Password</label>
                <input type="password" name="password" value="{{ old('password') }}"/>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-role">
                <label>Role</label>
                <input type="radio" name="role" value="Admin" {{ old('role') == 'Admin' ? 'checked' : '' }}> Admin
                <input type="radio" name="role" value="Lecturer" {{ old('role') == 'Lecturer' ? 'checked' : '' }}> Lecturer
                <input type="radio" name="role" value="Assistant" {{ old('role') == 'Assistant' ? 'checked': '' }}> Assistant
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModalUser" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit User</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-name">
                <label>Name</label>
                <input type="text" name="name" id="modalUserName"/>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-email">
                <label>Email</label>
                <input type="text" name="email" id="modalUserEmail"/>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Password</label>
                <input type="password" name="password" id="modalUserPassword"/>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-role">
                <label>Role</label>
                <input type="radio" name="role" value="Admin" id="modalRoleAdmin"> Admin
                <input type="radio" name="role" value="Lecturer" id="modalRoleLecturer"> Lecturer
                <input type="radio" name="role" value="Assistant" id="modalRoleAssistant"> Assistant
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

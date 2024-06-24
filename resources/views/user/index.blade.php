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
                    <div class="page-title" style="margin-bottom: 20px;">
                        <h3>Users</h3>
                        <a href="#" class="add-user" id="opencreateModalUser"><i class="fa-solid fa-user fa-xl" style="padding-right: 14px;"></i>Add User</a>
                    </div>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading-user">ID</th>
                                <th class="name-heading-user">Name</th>
                                <th class="email-heading-user">Email</th>
                                <th class="password-heading-user">Password</th>
                                <th class="role-heading-user">Role</th>
                                <th class="action-heading-user">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user as $index => $item)
                            <tr class="user-content">
                                <td class="id">{{ $index + 1 }}</td>
                                <td class="name">{{ $item->name }}</td>
                                <td class="email">{{ $item->email }}</td>
                                <td class="password">*****</td>
                                <td class="{{ $item->role == 'Admin' ? 'admin-status' : 'lecturer-status' }}">{{ $item->role }}</td>
                                <td class="action">
                                    <a href="#" class="edit-button-user" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-email="{{ $item->email }}" data-role="{{ $item->role }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                                    <a href="{{ url('user/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
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
        <h4>Create User</h4>
        <form action="{{ url('/user/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="{{ old('name') }}"/>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" value="{{ old('email') }}"/>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" value="{{ old('password') }}"/>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="radio" name="role" value="Admin" {{ old('role') == 'Admin' ? 'checked' : '' }}> Admin
                <input type="radio" name="role" value="Lecturer" {{ old('role') == 'Lecturer' ? 'checked' : '' }}> Lecturer
                <input type="radio" name="role" value="Assistant" {{ old('role') == 'Assistant' ? 'checked': '' }}> Assistant
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <div class="close-user-button">
                    <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                </div>
                <div class="save-user-button">
                    <button type="submit"><i class="fa-solid fa-check"></i>Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModalUser" class="modal">
    <div class="modal-content">
        <h4>Edit User</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="modalUserName"/>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" id="modalUserEmail"/>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="modalUserPassword"/>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Role</label>
                <input type="radio" name="role" value="Admin" id="modalRoleAdmin"> Admin
                <input type="radio" name="role" value="Lecturer" id="modalRoleLecturer"> Lecturer
                <input type="radio" name="role" value="Assistant" id="modalRoleAssistant"> Assistant
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <div class="close-user-button">
                    <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                </div>
                <div class="save-user-button">
                    <button type="submit"><i class="fa-solid fa-check"></i>Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

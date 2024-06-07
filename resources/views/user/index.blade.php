@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Users <a href="{{ url("user/create") }}" class="add-user"> Add User</a></h4>
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
                            @foreach ($user->sortBy('id') as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->password }} </td>
                                <td class= {{ $item->role == 'Admin' ? 'admin-status' : 'lecturer-status' }}>{{ $item->role }}</td>
                                <td>
                                    <a href="{{ url('user/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
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

@endsection

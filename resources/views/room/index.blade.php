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
                    <h3>Rooms <a href="#" class="add-user" id="opencreateModalRoom"> Add Room</a></h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Room Number</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($room as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>R{{ $item->room_number }}</td>
                                <td>
                                    <a href="#" class="edit-button-room" data-id="{{ $item->id }}" data-room_number="{{ $item->room_number }}">Edit</a>
                                    <a href="{{ url('room/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
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

<!-- Create Room Modal -->
<div id="createModalRoom" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Create Room</h4>
        <form action="{{ url('/room/create') }}" method="POST">
            @csrf
            <div class="form-name">
                <label>Room Number</label>
                <input type="text" name="room_number" value="{{ old('room_number') }}"/>
                @error('room_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Room Modal -->
<div id="editModalRoom" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit Room</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="modalRoomId">
            <div class="form-name">
                <label>Room Number</label>
                <input type="text" name="room_number" id="modalRoomNumber"/>
                @error('room_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

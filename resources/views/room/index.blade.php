@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <div class="page-title" style="margin-bottom: 20px;">
                        <h3>Rooms</h3>
                        <a href="#" class="add-user" id="opencreateModalRoom"><i class="fa-solid fa-chalkboard-user fa-xl" style="padding-right: 14px;"></i>Add Room</a>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fa-solid fa-circle-check"></i>{{ session('status') }}
                        </div>
                    @endif
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading-room">ID</th>
                                <th class="room-heading-room">Room Number</th>
                                <th class="action-heading-room">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($room as $index => $item)
                            <tr class="user-content">
                                <td class="id">{{ $index + 1 }}</td>
                                <td class="room">R{{ $item->room_number }}</td>
                                <td class="action">
                                    <a href="#" class="edit-button-room" data-id="{{ $item->id }}" data-room_number="{{ $item->room_number }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                                    <a href="{{ url('room/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
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
<div id="createModalRoom" class="modal {{ $errors->createRoom->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Create Room</h4>
        <form action="{{ url('/room/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Room Number</label>
                <input type="text" name="room_number" id="room_number" data-old-value="{{ old('room_number') }}"/>
                <br>
                @error('room_number', 'createRoom') <span class="text-danger">{{ $message }}</span> @enderror
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

<!-- Edit Room Modal -->
<div id="editModalRoom" class="modal {{ $errors->editRoom->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Edit Room</h4>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <input type="hidden" id="modalRoomAction" name="formAction" value='' data-old-action="{{ old('formAction') }}">

            <input type="hidden" name="id" id="modalRoomId">
            <div class="form-group">
                <label>Room Number</label>
                <input type="text" name="room_number" id="modalRoomNumber" value="" data-old-value="{{ old('room_number') }}"/>
                <br>
                @error('room_number', 'editRoom') <span class="text-danger">{{ $message }}</span> @enderror
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

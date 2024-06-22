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
                    <h3>Subjects <a href="#" class="add-user" id="opencreateModalSubject"> Add Subject</a></h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Subject Name</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subject as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="#" class="edit-button-subject" data-id="{{ $item->id }}" data-name="{{ $item->name }}">Edit</a>
                                    <a href="{{ url('subject/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
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

<!-- Create Subject Modal -->
<div id="createModalSubject" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Create Subject</h4>
        <form action="{{ url('/subject/create') }}" method="POST">
            @csrf
            <div class="form-name">
                <label>Subject Name</label>
                <input type="text" name="name" value="{{ old('name') }}"/>
                @error('name') <span class="">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Subject Modal -->
<div id="editModalSubject" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit Subject</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="modalSubjectId">
            <div class="form-name">
                <label>Subject Name</label>
                <input type="text" name="name" id="modalSubjectName"/>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <div class="page-title">
                        <h3>Subjects</h3>
                        <a href="#" class="add-user" id="opencreateModalSubject"><i class="fa-regular fa-file-lines fa-xl" style="padding-right: 14px;"></i>Add Subject</a>
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
                                <th class="id-heading-subject">ID</th>
                                <th class="subject-heading-subject">Subject Name</th>
                                <th class="action-heading-subject">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subject as $index => $item)
                            <tr class="user-content">
                                <td class="id">{{ $index + 1 }}</td>
                                <td class="subject">{{ $item->name }}</td>
                                <td class="action">
                                    <a href="#" class="edit-button-subject" data-id="{{ $item->id }}" data-name="{{ $item->name }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                                    <a href="{{ url('subject/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
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
<div id="createModalSubject" class="modal {{ $errors->createSubject->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Create Subject</h4>
        <form action="{{ url('/subject/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Subject Name</label>
                <input type="text" name="name" id="name" value="" data-old-value="{{ old('name') }}"/>
                <br>
                @error('name', 'createSubject') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-actions">
                <div class="close-user-button">
                    <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                </div>
                <div class="save-user-button">
                    <button type="submit"><i class="fa-solid fa-check"></i>Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Subject Modal -->
<div id="editModalSubject" class="modal {{ $errors->editSubject->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Edit Subject</h4>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <input type="hidden" id="modalSubjectAction" name="formAction" value='' data-old-action="{{ old('formAction') }}">

            <input type="hidden" name="id" id="modalSubjectId">
            <div class="form-group">
                <label>Subject Name</label>
                <input type="text" name="name" id="modalSubjectName" data-old-value="{{ old('name') }}"/>
                <br>
                @error('name', 'editSubject') <span class="text-danger">{{ $message }}</span> @enderror
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

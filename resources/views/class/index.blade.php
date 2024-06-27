@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <div class="page-title" style="margin-bottom: 20px;">
                        <h3>Class</h3>
                        <a href="#" class="add-user" id="opencreateModalClass"><i class="fa-solid fa-tag fa-xl" style="padding-right: 14px;"></i>Add Class</a>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fa-solid fa-circle-check"></i>{{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-xmark"></i>{{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading-class">ID</th>
                                <th class="prodi-heading-class">Prodi</th>
                                <th class="subject-heading-class">Subject</th>
                                <th class="class-heading-class">Class</th>
                                <th class="action-heading-class">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($class->sortBy('prodi') as $index => $item)
                            @php $counter = $loop->iteration @endphp
                            <tr class="class-content">
                                <td class="id">{{ $counter }}</td>
                                <td class="prodi">{{ $item->prodi }}</td>
                                <td class="subject">{{ $item->subject->name }}</td>
                                <td class="class">{{ $item->class }}</td>
                                <td class="action">
                                    <a href="#" class="edit-button-class" data-id="{{ $item->id }}" data-prodi="{{ $item->prodi }}" data-subject="{{ $item->subject_id }}" data-class="{{ $item->class }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                                    <a href="{{ url('class/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
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

<!-- Create Class Modal -->
<div id="createModalClass" class="modal {{ $errors->createClass->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Create Class</h4>
        <form action="{{ url('/class/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Prodi</label>
                <select name="prodi" id="prodi" data-old-value="{{ old('prodi') }}">
                    <option value="" disabled selected>Select a Prodi</option>
                    <option value="TI">TI</option>
                    <option value="SI">SI</option>
                </select>
                <br>
            </div>

            <div class="form-group">
                <label>Subject Name</label>
                <select name="subject_id" id="subject_id" data-old-value="{{ old('subject_id') }}">
                    <option value="" disabled selected>Select a Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <br>
            </div>

            <div class="form-group">
                <label>Class</label>
                <select name="class" id="class" data-old-value="{{ old('class') }}">
                    <option value="" disabled selected>Select Class</option>
                    @foreach (range('A', 'Z') as $char)
                        <option value="{{ $char }}">{{ $char }}</option>
                    @endforeach
                </select>
                <br>
            </div>

            @if($errors->createClass->any())
                <div class="form-errors">
                    @error('prodi', 'createClass')
                        @if ($message)
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror
                    
                    @error('subject_id', 'createClass')
                        @if ($message) 
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror

                    @error('class', 'createClass')
                        @if ($message) 
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror
                </div>
            @endif

            <div class="form-actions">
                <div class="close-user-button">
                    <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                </div>
                <div class="save-user-button">
                    <button type="submit"><i class="fa-solid fa-check"></i>Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Class Modal -->
<div id="editModalClass" class="modal {{ $errors->editClass->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Edit Class</h4>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')

            <input type="hidden" id="modalClassAction" name="formAction" value='' data-old-action="{{ old('formAction') }}">

            <div class="form-group">
                <label>Prodi</label>
                <select name="prodi" id="modalProdi" data-old-value="{{ old('prodi') }}">
                    <option value="" disabled>Select a Prodi</option>
                    <option value="TI">TI</option>
                    <option value="SI">SI</option>
                </select>
                <br>
            </div>

            <div class="form-group">
                <label>Subject Name</label>
                <select name="subject_id" id="modalSubject" data-old-value="{{ old('subject_id') }}">
                    <option value="" disabled>Select a Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                <br>
            </div>

            <div class="form-group">
                <label>Class</label>
                <select name="class" id="modalClass" data-old-value="{{ old('class') }}">
                    <option value="" disabled>Select Class</option>
                    @foreach (range('A', 'Z') as $char)
                        <option value="{{ $char }}">{{ $char }}</option>
                    @endforeach
                </select>
                <br>
            </div>

            @if($errors->editClass->any())
                <div class="form-errors">
                    @error('prodi', 'editClass')
                        @if ($message)
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror
                    
                    @error('subject_id', 'editClass')
                        @if ($message) 
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror

                    @error('class', 'editClass')
                        @if ($message) 
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @endif
                    @enderror
                </div>
            @endif

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

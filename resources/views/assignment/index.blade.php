@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <div class="page-title">
                        <h3>Assignment</h3>
                        <a href="#" class="add-user" id="opencreateModalAssignment"><i class="fa-solid fa-notes-medical fa-xl" style="padding-right: 14px;"></i>Add Assignment</a>
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
                    <form method="GET" action="{{ route('assignment.index') }}" class="options">
                        <!-- Filter by Subject -->
                        <label for="subject">Filter by Subject:</label>
                        <select name="subject_id" id="subject" onchange="this.form.submit()">
                            <option value="">All Subjects</option>
                            @foreach ($allSubject as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        <!-- Filter by Lecturer -->
                        <label for="lecturer" style="margin-left: 200px;">Filter by Lecturer:</label>
                        <select name="lecturer_id" id="lecturer" onchange="this.form.submit()">
                            <option value="">All Lecturer</option>
                            @foreach ($allLecturer as $lecturer)
                                <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading-assignment">ID</th>
                                <th class="subject-heading-assignment">Subject Name</th>
                                <th class="user-heading-assignment">User</th>
                                <th class="class-heading-assignment">Class</th>
                                <th class="action-heading-assignment">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $index => $item)
                            <tr class="assignment-content">
                                <td class="id">{{ $index + 1 }}</td>
                                <td class="subject">{{ $item->kelas->prodi }}-{{ $item->kelas->subject->name }}</td>
                                <td class="user">{{ $item->user->name ?? 'N/A' }}</td>
                                <td class="class">{{ $item->kelas->class }}</td>
                                <td class="action">
                                    <a href="#" class="edit-button-assignment" data-id="{{ $item->id }}" data-user_id="{{ $item->user_id }}" data-kelas_id="{{ $item->kelas_id }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                                    <a href="{{ url('assignment/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
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

<!-- Create Assignment Modal -->
<div id="createModalAssignment" class="modal {{ $errors->createAssignment->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Create Assignment</h4>
        <form action="{{ url('/assignment/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>User</label>
                <select name="user_id" id="user_id" data-old-value="{{ old('user_id') }}">
                    <option value="" disabled selected>Select a user</option>
                    @foreach($allLecturer as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <br>
            </div>

            <div class="form-group">
                <label>Class</label>
                <select name="kelas_id" id="kelas_id" data-old-value="{{ old('kelas_id') }}">
                    <option value="" disabled selected>Select a Class</option>
                    @foreach($allKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->prodi }}-{{ $kelas->subject->name }}-{{ $kelas->class }}</option>
                    @endforeach
                </select>
                <br>
            </div>

            @if($errors->createAssignment->any())
                <div class="form-errors">
                    @error('user_id', 'createAssignment') 
                        @if ($message)
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror
                    
                    @error('kelas_id', 'createAssignment')
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
                    <button type="submit"><i class="fa-solid fa-check"></i>Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Assignment Modal -->
<div id="editModalAssignment" class="modal {{ $errors->editAssignment->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Edit Assignment</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" id="modalAssignmentAction" name="formAction" value='' data-old-action="{{ old('formAction') }}">

            <div class="form-group">
                <label>User</label>
                <select name="user_id" id="modalUser" data-old-value="{{ old('user_id') }}">
                    <option value="" disabled>Select a user</option>
                    @foreach($allLecturer as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <br>
                @error('user_id', 'editAssignment') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Class</label>
                <select name="kelas_id" id="modalClass" data-old-value="{{ old('kelas_id') }}">
                    <option value="" disabled>Select a Class</option>
                    @foreach($allKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->prodi }}-{{ $kelas->subject->name }}-{{ $kelas->class }}</option>
                    @endforeach
                </select>
                <br>
                @error('kelas_id', 'editAssignment') <span class="text-danger">{{ $message }}</span> @enderror
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

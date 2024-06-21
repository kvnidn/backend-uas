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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="">
                <div class="users">
                    <h4>Assignment <a href="#" class="add-user" id="opencreateModalAssignment">Add Assignment</a></h4>
                    <form method="GET" action="{{ route('assignment.index') }}">
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
                        <label for="lecturer">Filter by Lecturer:</label>
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
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Subject Name</th>
                                <th class="user-heading">User</th>
                                <th class="kelas-heading">Class</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $index => $item)
                            <tr class="assignment-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kelas->prodi }}-{{ $item->kelas->subject->name }}</td>
                                <td>{{ $item->user->name ?? 'N/A' }}</td>
                                <td>{{ $item->kelas->class }}</td>
                                <td>
                                    <a href="#" class="edit-button-assignment" data-id="{{ $item->id }}" data-user_id="{{ $item->user_id }}" data-kelas_id="{{ $item->kelas_id }}">Edit</a>
                                    <a href="{{ url('assignment/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure?')">Delete</a>
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
<div id="createModalAssignment" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Create Assignment</h4>
        <form action="{{ url('/assignment/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>User</label>
                <select name="user_id">
                    <option value="" disabled selected>Select a user</option>
                    @foreach($allLecturer as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Class</label>
                <select name="kelas_id">
                    <option value="" disabled selected>Select a Class</option>
                    @foreach($allKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->prodi }}-{{ $kelas->subject->name }}-{{ $kelas->class }}</option>
                    @endforeach
                </select>
                @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Assignment Modal -->
<div id="editModalAssignment" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit Assignment</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>User</label>
                <select name="user_id" id="modalUser">
                    <option value="" disabled>Select a user</option>
                    @foreach($allLecturer as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>Class</label>
                <select name="kelas_id" id="modalClass">
                    <option value="" disabled>Select a Class</option>
                    @foreach($allKelas as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->prodi }}-{{ $kelas->subject->name }}-{{ $kelas->class }}</option>
                    @endforeach
                </select>
                @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

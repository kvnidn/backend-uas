@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Assignment <a href="{{ url("assignment/create") }}" class="add-user">Add Assignment</a></h4>
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
                        <!-- Filter by Subject -->
                        <label for="lecturer">Filter by Subject:</label>
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
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kelas->prodi }}-{{ $item->kelas->subject->name }}</td>
                                <td>{{ $item->user->name ?? 'N/A'}}</td>
                                <td>{{$item->kelas->class}}</td>
                                <td>
                                    <a href="{{ url('assignment/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
                                    <a href="{{ url('assignment/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
                                    </form>
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

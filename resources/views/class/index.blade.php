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
                    <h3>Class <a href="#" class="add-user" id="opencreateModalClass">Add Classes</a></h3>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Prodi</th>
                                <th class="user-heading">Subject</th>
                                <th class="kelas-heading">Class</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($class->sortBy('year')->sortBy('prodi') as $index => $item)
                            @php $counter = $loop->iteration @endphp
                            <tr class="class-content">
                                <td>{{ $counter }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->subject->name }}</td>
                                <td>{{ $item->class }}</td>
                                <td>
                                    <a href="#" class="edit-button-class" data-id="{{ $item->id }}" data-prodi="{{ $item->prodi }}" data-subject="{{ $item->subject_id }}" data-class="{{ $item->class }}">Edit</a>
                                    <a href="{{ url('class/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
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
<div id="createModalClass" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Create Class</h4>
        <form action="{{ url('/class/create') }}" method="POST">
            @csrf
            <div class="form-prodi">
                <label>Prodi</label>
                <select name="prodi">
                    <option value="" disabled selected>Select a Prodi</option>
                    <option value="TI">TI</option>
                    <option value="SI">SI</option>
                </select>
                @error('prodi') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-name">
                <label>Subject Name</label>
                <select name="subject_id">
                    <option value="" disabled selected>Select a subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-class">
                <label>Class</label>
                <select name="class">
                    <option value="" disabled selected>Select Class</option>
                    @foreach (range('A', 'Z') as $char)
                        <option value="{{ $char }}">{{ $char }}</option>
                    @endforeach
                </select>
                @error('class') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="save-user-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Class Modal -->
<div id="editModalClass" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit Class</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-prodi">
                <label>Prodi</label>
                <select name="prodi" id="modalProdi">
                    <option value="" disabled>Select a Prodi</option>
                    <option value="TI">TI</option>
                    <option value="SI">SI</option>
                </select>
                @error('prodi') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-name">
                <label>Subject Name</label>
                <select name="subject_id" id="modalSubject">
                    <option value="" disabled>Select a subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
                @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-class">
                <label>Class</label>
                <select name="class" id="modalClass">
                    <option value="" disabled>Select Class</option>
                    @foreach (range('A', 'Z') as $char)
                        <option value="{{ $char }}">{{ $char }}</option>
                    @endforeach
                </select>
                @error('class') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

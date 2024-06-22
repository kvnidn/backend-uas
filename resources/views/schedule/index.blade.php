@extends('layouts.main')

@section('isi')
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
<div class="users">
    @if(auth()->user()->role == 'Admin')
        <h3>Schedule <a href="#" class="add-user" id="opencreateModalSchedule">Add Schedule</a></h3>
    @else
        <h3>Schedule</h3>
    @endif
</div>

<div class="table-container">
    <form method="GET" action="{{ route('schedule.index') }}">
        <!-- Filter by Day of the Week -->
        <label for="day_of_week">Filter by Day of the Week:</label>
        <select name="day_of_week" id="day_of_week" onchange="this.form.submit()">
            <option value="">All Days</option>
            <option value="Monday" {{ request('day_of_week') == 'Monday' ? 'selected' : '' }}>Monday</option>
            <option value="Tuesday" {{ request('day_of_week') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
            <option value="Wednesday" {{ request('day_of_week') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
            <option value="Thursday" {{ request('day_of_week') == 'Thursday' ? 'selected' : '' }}>Thursday</option>
            <option value="Friday" {{ request('day_of_week') == 'Friday' ? 'selected' : '' }}>Friday</option>
            <option value="Saturday" {{ request('day_of_week') == 'Saturday' ? 'selected' : '' }}>Saturday</option>
            <option value="Sunday" {{ request('day_of_week') == 'Sunday' ? 'selected' : '' }}>Sunday</option>
        </select>
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
            <option value="">All Lecturers</option>
            @foreach ($allLecturer as $lecturer)
                <option value="{{ $lecturer->id }}" {{ request('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                    {{ $lecturer->name }}
                </option>
            @endforeach
        </select>

            <!-- Filter by Room -->
            <label for="room">Filter by Room:</label>
            <select name="room_id" id="room" onchange="this.form.submit()">
                <option value="">All Rooms</option>
                @foreach ($allRoom as $room)
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                        Room {{ $room->room_number }}
                    </option>
                @endforeach
            </select>
    </form>

    <table class="table">
        <thead class="table-header">
            <tr class="table-header-row">
                <th class="id-heading">ID</th>
                <th class="name-heading">Day</th>
                <th class="user-heading">Start Time</th>
                <th class="user-heading">End Time</th>
                <th class="user-heading">Subject Name</th>
                <th class="user-heading">Lecturer Name</th>
                <th class="user-heading">Class</th>
                <th class="user-heading">Room Number</th>
                <th class="action-heading">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedSchedules as $group)
                @php
                    $ids = $group->pluck('id')->join(',');
                    $dates = $group->pluck('date')->join(',');
                @endphp
                <tr class="user-content">
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ date('l', strtotime($group->first()->date)) }}</td>
                    <td>{{ $group->first()->start_time }}</td>
                    <td>{{ $group->first()->end_time }}</td>
                    <td>{{ $group->first()->assignment->kelas->prodi }}-{{ $group->first()->assignment->kelas->subject->name }}</td>
                    <td>{{ $group->first()->assignment->user->name }}</td>
                    <td>{{ $group->first()->assignment->kelas->class }}</td>
                    <td>R{{ $group->first()->room->room_number }}</td>
                    <td>
                        <div class="actions">
                        @if(auth()->user()->role == 'Admin' || (in_array(auth()->user()->role, ['Lecturer', 'Assistant']) && auth()->user()->name == $group->first()->assignment->user->name))
                            <div class="actions">
                                <a href="#" class="batch-edit-button-schedule" data-ids="{{ $ids }}" data-dates="{{ $dates }}" data-start="{{ $group->first()->start_time }}" data-end="{{ $group->first()->end_time }}" data-assignment-id="{{ $group->first()->assignment->id }}" data-room-id="{{ $group->first()->room->id }}">Edit</a>
                            @endif

                            @if(auth()->user()->role == 'Admin')
                                <a href="{{ url('schedule/batch-delete?ids=' . $ids) }}" class="batch-delete-button" onclick="return confirm('Are you sure?')">Delete</a>
                            @endif
                            <button class="expand-button">Expand</button>
                        </div>
                    </td>
                </tr>
                <tr class="expanded-content" style="display: none;">
                    <td colspan="9">
                        <table class="table">
                            <thead class="table-header">
                                <tr class="table-header-row">
                                    <th class="id-heading">ID</th>
                                    <th class="user-heading">Date</th>
                                    <th class="user-heading">Start Time</th>
                                    <th class="user-heading">End Time</th>
                                    <th class="user-heading">Subject Name</th>
                                    <th class="user-heading">Lecturer Name</th>
                                    <th class="user-heading">Class</th>
                                    <th class="user-heading">Room Number</th>
                                    <th class="action-heading">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($group as $item)
                                    <tr>
                                        <td>{{ $loop->parent->index + 1 }}.{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->start_time }}</td>
                                        <td>{{ $item->end_time }}</td>
                                        <td>{{ $item->assignment->kelas->prodi }}-{{ $item->assignment->kelas->subject->name }}</td>
                                        <td>{{ $item->assignment->user->name }}</td>
                                        <td>{{ $item->assignment->kelas->class }}</td>
                                        <td>R{{ $item->room->room_number }}</td>
                                        <td>
                                            <div class="actions">
                                                @if(auth()->user()->role == 'Admin' || (in_array(auth()->user()->role, ['Lecturer', 'Assistant']) && auth()->user()->name == $group->first()->assignment->user->name))
                                                    <a href="#" class="edit-button-schedule" data-id="{{ $item->id }}" data-date="{{ $item->date }}" data-start="{{ $item->start_time }}" data-end="{{ $item->end_time }}" data-assignment-id="{{ $item->assignment->id }}" data-room-id="{{ $item->room->id }}">Edit</a>
                                                @endif

                                                @if(auth()->user()->role == 'Admin')
                                                    <a href="{{ url('schedule/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure?')">Delete</a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="createModalSchedule" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Create Assignment</h4>
        <form action="{{ url('/schedule/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required>
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="start_time" required>
                @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="end_time" required>
                @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="assignment_id">Assignment</label>
                <select name="assignment_id" id="assignment_id" required>
                    <option value="" disabled selected>Select an assignment</option>
                    @foreach($allAssignments as $assignment)
                        <option value="{{ $assignment->id }}">
                            {{ $assignment->kelas->prodi }} - {{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('assignment_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="room_id">Room</label>
                <select name="room_id" id="room_id" required>
                    <option value="" disabled selected>Select a room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">
                            R{{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
                @error('room_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="repeat">Repeat (weeks)</label>
                <input type="number" name="repeat" id="repeat" min="0" max="52" value="0">
            </div>

            <div class="save-user-button">
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="editModalSchedule" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit Assignment</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="modalDate" value="" required>
                @error('date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="modalStart" value="" required>
                @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="modalEnd" value="" required>
                @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="assignment_id">Assignment</label>
                <select name="assignment_id" id="modalAssignment" required {{ in_array(auth()->user()->role, ['Lecturer', 'Assistant']) ? 'disabled' : '' }}>
                    <option value="" disabled>Select an assignment</option>
                    @foreach($allAssignments as $assignment)
                        <option value="{{ $assignment->id }}" >
                            {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('assignment_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="room_id">Room</label>
                <select name="room_id" id="modalRoom" required>
                    <option value="" disabled>Select a room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" >
                            {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
                @error('room_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="editModalScheduleBatch" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit Assignment</h4>
        <form id="editFormBatch" method="POST">
            @csrf
            @method('PUT')

            <div id="idsContainer"></div>
            <div id="datesContainer"></div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="modalStartBatch" value="" required>
                @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="modalEndBatch" value="" required>
                @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="assignment_id">Assignment</label>
                <select name="assignment_id" id="modalAssignmentBatch" required {{ in_array(auth()->user()->role, ['Lecturer', 'Assistant']) ? 'disabled' : '' }}>
                    <option value="" disabled>Select an assignment</option>
                    @foreach($allAssignments as $assignment)
                        <option value="{{ $assignment->id }}" >
                            {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('assignment_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="room_id">Room</label>
                <select name="room_id" id="modalRoomBatch" required>
                    <option value="" disabled>Select a room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" >
                            {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
                @error('room_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts.main')

@section('isi')
<div class="users">
    <div class="page-title">
        @if(auth()->user()->role == 'Admin')
            <h3>Schedule</h3>
            <a href="#" class="add-user" id="opencreateModalSchedule"><i class="fa-regular fa-calendar-plus fa-xl" style="padding-right: 14px;"></i>Add Schedule</a>
        @else
            <h3>Schedule</h3>
        @endif
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

    <div class="filter-container">
        <form method="GET" action="{{ route('schedule.index') }}" class="options">
            <div class="filter-container-row">
                <div class="filter-container-column">
                    <!-- Filter by Day of the Week -->
                    <label for="day_of_week">Filter by Day:</label>
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
                </div>
                <div class="filter-container-column">
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
                </div>
            </div>
            <div class="filter-container-row">
                <div class="filter-container-column">
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
                </div>
                <div class="filter-container-column">
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
                </div>
            </div>
        </form>
    </div>
</div>

<div class="table-container">
    <table class="table">
        <thead class="table-header">
            <tr class="table-header-row">
                <th class="id-heading-schedule">ID</th>
                <th class="day-heading-schedule">Day</th>
                <th class="start-heading-schedule">Start Time</th>
                <th class="end-heading-schedule">End Time</th>
                <th class="subject-heading-schedule">Subject Name</th>
                <th class="lecturer-heading-schedule">Lecturer Name</th>
                <th class="class-heading-schedule">Class</th>
                <th class="room-heading-schedule">Room Number</th>
                <th class="action-heading-schedule">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedSchedules as $group)
                @php
                    $ids = $group->pluck('id')->join(',');
                    $dates = $group->pluck('date')->join(',');
                @endphp
                <tr class="user-content">
                    <td class="id">{{ $loop->index + 1 }}</td>
                    <td class="day">{{ date('l', strtotime($group->first()->date)) }}</td>
                    <td class="start">{{ $group->first()->start_time }}</td>
                    <td class="end">{{ $group->first()->end_time }}</td>
                    <td class="subject">{{ $group->first()->assignment->kelas->prodi }}-{{ $group->first()->assignment->kelas->subject->name }}</td>
                    <td class="lecturer">{{ $group->first()->assignment->user->name }}</td>
                    <td class="class">{{ $group->first()->assignment->kelas->class }}</td>
                    <td class="room">R{{ $group->first()->room->room_number }}</td>
                    <td class="action">
                        <div class="actions">
                        @if(auth()->user()->role == 'Admin' || (in_array(auth()->user()->role, ['Lecturer', 'Assistant']) && auth()->user()->name == $group->first()->assignment->user->name))
                            <div class="actions">
                                <a href="#" class="batch-edit-button-schedule" data-ids="{{ $ids }}" data-dates="{{ $dates }}" data-start="{{ $group->first()->start_time }}" data-end="{{ $group->first()->end_time }}" data-assignment-id="{{ $group->first()->assignment->id }}" data-room-id="{{ $group->first()->room->id }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                            @endif

                            @if(auth()->user()->role == 'Admin')
                                <a href="{{ url('schedule/batch-delete?ids=' . $ids) }}" class="batch-delete-button" onclick="return confirm('Are you sure?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
                            @endif
                            <button class="expand-button">Expand<i class="fa-solid fa-caret-down" style="padding-left: 8px;"></i></button>
                        </div>
                    </td>
                </tr>
                <tr class="expanded-content" style="display: none;">
                    <td colspan="9">
                        <table class="table">
                            <thead class="table-header-expanded">
                                <tr class="table-header-row">
                                    <th class="id-heading-schedule">ID</th>
                                    <th class="date-heading-schedule">Date</th>
                                    <th class="start-heading-schedule">Start Time</th>
                                    <th class="end-heading-schedule">End Time</th>
                                    <th class="subject-heading-schedule">Subject Name</th>
                                    <th class="lecturer-heading-schedule">Lecturer Name</th>
                                    <th class="class-heading-schedule">Class</th>
                                    <th class="room-heading-schedule">Room Number</th>
                                    <th class="action-heading-schedule">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $groupbydate = $group->sortBy('date');
                                @endphp
                                @foreach ($groupbydate as $item)
                                    <tr>
                                        <td class="id">{{ $loop->parent->index + 1 }}.{{ $loop->index + 1 }}</td>
                                        <td class="date">{{ date('d M Y', strtotime($item->date)) }}</td>
                                        <td class="start">{{ $item->start_time }}</td>
                                        <td class="end">{{ $item->end_time }}</td>
                                        <td class="subject">{{ $item->assignment->kelas->prodi }}-{{ $item->assignment->kelas->subject->name }}</td>
                                        <td class="lecturer">{{ $item->assignment->user->name }}</td>
                                        <td class="class">{{ $item->assignment->kelas->class }}</td>
                                        <td class="room">R{{ $item->room->room_number }}</td>
                                        <td class="action">
                                            <div class="actions">
                                                @if(auth()->user()->role == 'Admin' || (in_array(auth()->user()->role, ['Lecturer', 'Assistant']) && auth()->user()->name == $group->first()->assignment->user->name))
                                                    <a href="#" class="edit-button-schedule" data-id="{{ $item->id }}" data-date="{{ $item->date }}" data-start="{{ $item->start_time }}" data-end="{{ $item->end_time }}" data-assignment-id="{{ $item->assignment->id }}" data-room-id="{{ $item->room->id }}"><i class="fa-regular fa-pen-to-square" style="padding-right: 8px;"></i>Edit</a>
                                                @endif

                                                @if(auth()->user()->role == 'Admin')
                                                    <a href="{{ url('schedule/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure?')"><i class="fa-regular fa-trash-can fa-sm" style="padding-right: 8px;"></i>Delete</a>
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

<div id="createModalSchedule" class="modal {{ $errors->createSchedule->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Create Schedule</h4>
        <form action="{{ url('/schedule/create') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" data-old-value="{{ old('date') }}" required>
                @error('date', 'createSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="start_time" data-old-value="{{ old('start_time') }}" required>
                @error('start_time', 'createSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="end_time" data-old-value="{{ old('end_time') }}" required>
                @error('end_time', 'createSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="assignment_id">Assignment</label>
                <select name="assignment_id" id="assignment_id" data-old-value="{{ old('assignment_id') }}" required>
                    <option value="" disabled selected>Select an assignment</option>
                    @foreach($allAssignments as $assignment)
                        <option value="{{ $assignment->id }}">
                            {{ $assignment->kelas->prodi }} - {{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                        </option>
                    @endforeach
                </select>
                @error('assignment_id', 'createSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="room_id">Room</label>
                <select name="room_id" id="room_id" data-old-value="{{ old('room_id') }}" required>
                    <option value="" disabled selected>Select a room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">
                            R{{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
                @error('room_id', 'createSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="repeat">Repeat (weeks)</label>
                <input type="number" name="repeat" id="repeat" min="0" max="52" value="0" data-old-value="{{ old('repeat') }}">
                @error('schedule', 'createSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

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

<div id="editModalSchedule" class="modal {{ $errors->editSchedule->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Edit Schedule</h4>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" id="modalScheduleAction" name="formAction" value='' data-old-action="{{ old('formAction') }}">
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="modalDate" value="" data-old-value="{{ old('date') }}" required>
                @error('date', 'editSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="modalStart" value="" data-old-value="{{ old('start_time') }}" required>
                @error('start_time', 'editSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="modalEnd" value="" data-old-value="{{ old('end_time') }}" required>
                @error('end_time', 'editSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="assignment_id">Assignment</label>
                <select name="assignment_id" id="modalAssignment" data-old-value="{{ old('assignment_id') }}" required {{ in_array(auth()->user()->role, ['Lecturer', 'Assistant']) ? 'selected' : '' }}>
                    <option value="" disabled>Select an assignment</option>
                    @if(auth()->user()->role === 'Admin')
                        @foreach($allAssignments as $assignment)
                            <option value="{{ $assignment->id }}">
                                {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                            </option>
                        @endforeach
                    @else
                        @foreach($allAssignments as $assignment)
                            @if(auth()->user()->id == $assignment->user_id)
                                <option value="{{ $assignment->id }}">
                                    {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
                @error('assignment_id', 'editSchedule') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="room_id">Room</label>
                <select name="room_id" id="modalRoom" data-old-value="{{ old('room_id') }}" required>
                    <option value="" disabled>Select a room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" >
                            {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
                @error('room_id', 'editSchedule') <span class="text-danger">{{ $message }}</span> @enderror
                @error('schedule', 'editSchedule') <span class="text-danger">{{ $message }}</span> @enderror
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

<div id="editModalScheduleBatch" class="modal {{ $errors->editScheduleBatch->any() ? 'open' : '' }}">
    <div class="modal-content">
        <h4>Batch Edit Schedule</h4>
        <form id="editFormBatch" method="POST" action="">
            @csrf
            @method('PUT')

            <input type="hidden" id="modalScheduleActionBatch" name="formActionBatch" value='' data-old-action="{{ old('formActionBatch') }}">
            
            <div id="idsContainer"></div>
            <div id="datesContainer" style="display:none">
                @if(old('dates'))
                    @foreach(old('dates') as $index => $date)
                        <input type="date" name="dates[]" value="{{ $date }}" data-old-value="{{ $date }}">
                    @endforeach
                @endif
            </div>

            <div class="form-group">
                <label for="start_time">Start Time</label>
                <input type="time" name="start_time" id="modalStartBatch" value="" data-old-value="{{ old('start_time') }}" required>
                @error('start_time', 'editScheduleBatch') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" id="modalEndBatch" value="" data-old-value="{{ old('end_time') }}" required>
                @error('end_time', 'editScheduleBatch') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="assignment_id">Assignment</label>
                <select name="assignment_id" id="modalAssignmentBatch" required data-old-value="{{ old('assignment_id') }}" {{ in_array(auth()->user()->role, ['Lecturer', 'Assistant']) ? 'selected' : '' }}>
                    <option value="" disabled>Select an assignment</option>
                    @if(auth()->user()->role === 'Admin')
                        @foreach($allAssignments as $assignment)
                            <option value="{{ $assignment->id }}">
                                {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                            </option>
                        @endforeach
                    @else
                        @foreach($allAssignments as $assignment)
                            @if(auth()->user()->id == $assignment->user_id)
                                <option value="{{ $assignment->id }}">
                                    {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }} - {{ $assignment->user->name }}
                                </option>
                            @endif
                        @endforeach
                    @endif
                </select>
                @error('assignment_id', 'editScheduleBatch') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="room_id">Room</label>
                <select name="room_id" id="modalRoomBatch" data-old-value="{{ old('room_id') }}" required>
                    <option value="" disabled>Select a room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" >
                            {{ $room->room_number }}
                        </option>
                    @endforeach
                </select>
                @error('room_id', 'editScheduleBatch') <span class="text-danger">{{ $message }}</span> @enderror
                @error('schedule', 'editScheduleBatch') <span class="text-danger">{{ $message }}</span> @enderror
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

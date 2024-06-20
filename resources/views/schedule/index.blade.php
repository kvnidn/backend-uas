@extends('layouts.main')

@section('isi')
<div class="users">
    @if(auth()->user()->role == 'Admin')
        <h4>Schedule <a href="{{ url('schedule/create') }}" class="add-user">Add Schedule</a></h4>
    @else
        <h4>Schedule</h4>
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
            <!-- Filter by Class -->
            <label for="class">Filter by Class:</label>
            <select name="class_id" id="class" onchange="this.form.submit()">
                <option value="">All Classes</option>
                @foreach ($allClass as $class)
                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                    {{ $class->class }}
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
                                <a href="{{ url('schedule/batch-edit?ids=' . $ids) }}" class="edit-button">Edit</a>
                            @endif

                            @if(auth()->user()->role == 'Admin')
                                <a href="{{ url('schedule/batch-delete?ids=' . $ids) }}" class="delete-button" onclick="return confirm('Are you sure?')">Delete</a>
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
                                                    <a href="{{ url('schedule/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.expand-button').forEach(button => {
            button.addEventListener('click', function() {
                const expandedContent = this.closest('tr').nextElementSibling;
                if (expandedContent.style.display === 'none' || !expandedContent.style.display) {
                    expandedContent.style.display = 'table-row';
                    this.textContent = 'Collapse';
                } else {
                    expandedContent.style.display = 'none';
                    this.textContent = 'Expand';
                }
            });
        });
    });
</script>
@endsection

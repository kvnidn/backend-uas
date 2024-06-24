@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <div class="page-title">
                        <h3>Schedule</h3>
                    </div>
                    <h1 class="date-today">
                        {{ date('l, j F Y', strtotime($selectedDate)) }}
                    </h1>
                    <div class="date-navigation">
                        @if ($selectedDate > $earliestDate)
                            <a href="{{ route('schedule.view', ['date' => $prevDate, 'room_id' => $selectedRoom]) }}" class="prev-date">Previous Date</a>
                        @else
                            <span class="prev-date disabled">Previous Date</span>
                        @endif
                        <a href="{{ route('schedule.view', ['date' => today()->toDateString(), 'room_id' => $selectedRoom]) }}" class="today">Today</a>
                        @if ($selectedDate < $furthestDate)
                            <a href="{{ route('schedule.view', ['date' => $nextDate, 'room_id' => $selectedRoom]) }}" class="next-date">Next Date</a>
                        @else
                            <span class="next-date disabled">Next Date</span>
                        @endif
                        <form method="GET" action="{{ route('schedule.view') }}" class="options">
                            <label for="calendar">Select Date:</label>
                            <input type="date" name="date" id="calendar" value="{{ $selectedDate }}" onchange="this.form.submit()">
                        </form>
                    </div>
                    <form method="GET" action="{{ route('schedule.view', ['date' => $selectedDate]) }}" class="options">
                        <label for="room">Filter by Room:</label>
                            <select name="room_id" id="room" onchange="this.form.submit()">
                                <option value="">All Rooms</option>
                                @foreach ($allRoom as $room)
                                    <option value="{{ $room->id }}" {{ $room->id == $selectedRoom ? 'selected' : '' }}>
                                        Room {{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                    </form>
                </div>                
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading-view">ID</th>
                                <th class="start-heading-view">Start Time</th>
                                <th class="end-heading-view">End Time</th>
                                <th class="subject-heading-view">Subject Name</th>
                                <th class="lecturer-heading-view">Lecturer Name</th>
                                <th class="class-heading-view">Class</th>
                                <th class="room-heading-view">Room Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $index => $item)
                            <tr class="user-content">
                                <td class="id">{{ $index + 1 }}</td>
                                <td class="start">{{ $item->start_time }}</td>
                                <td class="end">{{ $item->end_time }}</td>
                                <td class="subject">{{ $item->assignment->kelas->prodi }}-{{ $item->assignment->kelas->subject->name}}</td>
                                <td class="lecturer">{{ $item->assignment->user->name }}</td>
                                <td class="class">{{ $item->assignment->kelas->class }}</td>
                                <td class="room">R{{ $item->room->room_number }}</td>
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

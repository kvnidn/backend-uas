@extends('layouts.main')

@section('isi')

<div class="users">
    <h4>Key Lending</h4>
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
    <div class="date-navigation">
        <span>{{ $selectedDate }} ({{ date('l', strtotime($selectedDate)) }})</span>
    </div>
</div>
<div class="table-container">
    <form method="GET" action="{{ route('key-lending.viewToday') }}">
        <label for="room">Filter by Room:</label>
        <select name="room_id" id="room" onchange="this.form.submit()">
            <option value="">All Rooms</option>
            @foreach ($allRoom as $room)
                <option value="{{ $room->id }}" {{ $room->id == $selectedRoomId ? 'selected' : '' }}>
                    Room {{ $room->room_number }}
                </option>
            @endforeach
        </select>
    </form>
    <table class="table">
        <thead class="table-header">
            <tr class="table-header-row">
                <th class="id-heading">ID</th>
                <th class="user-heading">Start Time</th>
                <th class="user-heading">End Time</th>
                <th class="user-heading">Subject Name</th>
                <th class="user-heading">Lecturer Name</th>
                <th class="user-heading">Class</th>
                <th class="user-heading">Room Number</th>
                <th class="action-heading">Lend</th>
                <th class="action-heading">Return</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schedule as $index => $scheduleItem)
                <tr class="user-content">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $scheduleItem->start_time }}</td>
                    <td>{{ $scheduleItem->end_time }}</td>
                    <td>{{ $scheduleItem->assignment ? $scheduleItem->assignment->kelas->prodi . '-' . $scheduleItem->assignment->kelas->subject->name : '' }}</td>
                    <td>{{ $scheduleItem->assignment ? $scheduleItem->assignment->user->name : '' }}</td>
                    <td>{{ $scheduleItem->assignment && $scheduleItem->assignment->kelas ? $scheduleItem->assignment->kelas->class : '' }}</td>
                    <td>{{ $scheduleItem->room ? 'R' . $scheduleItem->room->room_number : '' }}</td>
                    <td>
                        @php
                            $tenMinutesBeforeStart = Carbon\Carbon::parse($scheduleItem->start_time)->subMinutes(10);
                            $currentTime = now();
                            $end_time = Carbon\Carbon::parse($scheduleItem->end_time, 'Asia/Bangkok');
                            $keyLending = $keyLendings->where('schedule_id', $scheduleItem->id)->first();
                        @endphp

                        @if ($currentTime >= $tenMinutesBeforeStart && $currentTime <= $end_time && !$keyLending)
                            <button type="button" class="btn btn-success lend-btn"
                                data-action="start" data-schedule-id="{{ $scheduleItem->id }}">
                                Lend Key
                            </button>
                        @elseif ($keyLending)
                            <button type="button" disabled style="background-color:yellow">
                                Key Lent at {{ $keyLending->start_time }}
                            </button>
                        @else
                            <button type="button" disabled>
                                Lend Key
                            </button>
                        @endif
                    </td>
                    <td>
                        @if ($keyLending && $keyLending->start_time == $keyLending->end_time)
                            <button type="button" class="btn btn-warning return-btn"
                                data-action="end" data-schedule-id="{{ $scheduleItem->id }}">
                                Return Key
                            </button>
                        @elseif($keyLending)
                            @php
                                $twentyMinutesAfterEnd = Carbon\Carbon::parse($scheduleItem->end_time)->addMinutes(20);
                            @endphp
                            @if (Carbon\Carbon::parse($keyLending->end_time)->lessThanOrEqualTo($twentyMinutesAfterEnd))
                                <button type="button" disabled style="background-color: yellow">
                                    Key Returned at {{ $keyLending->end_time }}
                                </button>
                            @elseif(Carbon\Carbon::parse($keyLending->end_time)->greaterThan($twentyMinutesAfterEnd))
                                <button type="button" disabled style="background-color: red">
                                    Returned Late at {{ $keyLending->end_time }}
                                </button>
                            @endif
                        @else
                            <button type="button" disabled>
                                Return Key
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Common Modal Structure with Overlay -->
<div id="keyActionModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close">&times;</span>
        <h5 id="modalTitle">Modal Title</h5>
        <form id="keyActionForm" method="POST">
            @csrf
            <input type="hidden" id="scheduleId" name="schedule_id">
            <input type="hidden" id="actionType" name="action_type">
            <div class="modal-body">
                <p id="modalMessage">Please enter your password:</p>
                <input type="password" name="password" class="form-control" required>
                <div id="modalAlert" style="margin-top: 10px;"></div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts.main')

@section('isi')

<div class="users">
    <div class="page-title">
        <h3>Key Lending</h3>
    </div>
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
        <span>{{ date('l, j F Y', strtotime($selectedDate)) }}</span>
    </div>
    
    <form method="GET" action="{{ route('key-lending.viewToday') }}" class="options">
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
</div>
<div class="table-container">
    <table class="table">
        <thead class="table-header">
            <tr class="table-header-row">
                <th class="id-heading-lend">ID</th>
                <th class="start-heading-lend">Start Time</th>
                <th class="end-heading-lend">End Time</th>
                <th class="subject-heading-lend">Subject Name</th>
                <th class="lecturer-heading-lend">Lecturer Name</th>
                <th class="class-heading-lend">Class</th>
                <th class="room-heading-lend">Room Number</th>
                <th class="action-heading-lend">Lend</th>
                <th class="action-heading-return">Return</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($schedule as $index => $scheduleItem)
                <tr class="user-content">
                    <td class="id">{{ $index + 1 }}</td>
                    <td class="start">{{ $scheduleItem->start_time }}</td>
                    <td class="end">{{ $scheduleItem->end_time }}</td>
                    <td class="subject">{{ $scheduleItem->assignment ? $scheduleItem->assignment->kelas->prodi . '-' . $scheduleItem->assignment->kelas->subject->name : '' }}</td>
                    <td class="lecturer">{{ $scheduleItem->assignment ? $scheduleItem->assignment->user->name : '' }}</td>
                    <td class="class">{{ $scheduleItem->assignment && $scheduleItem->assignment->kelas ? $scheduleItem->assignment->kelas->class : '' }}</td>
                    <td class="room">{{ $scheduleItem->room ? 'R' . $scheduleItem->room->room_number : '' }}</td>
                    <td class="action">
                        @php
                            $tenMinutesBeforeStart = Carbon\Carbon::parse($scheduleItem->start_time)->subMinutes(10);
                            $currentTime = now();
                            $end_time = Carbon\Carbon::parse($scheduleItem->end_time, 'Asia/Bangkok');
                            $keyLending = $keyLendings->where('schedule_id', $scheduleItem->id)->first();
                        @endphp

                        @if ($currentTime >= $tenMinutesBeforeStart && $currentTime <= $end_time && !$keyLending)
                            <button type="button" class="btn btn-success lend-btn"
                                data-action="start" data-schedule-id="{{ $scheduleItem->id }}">
                                <i class="fa-solid fa-key" style="padding-right: 4px"></i>Lend Key
                            </button>
                        @elseif ($keyLending)
                            <button type="button" class="btn after-lend disabled">
                                Key Lent at <span>{{ $keyLending->start_time }}</span>
                            </button>
                        @else
                            <button type="button" class="btn cannot-lend disabled">
                                Lend Key
                            </button>
                        @endif
                    </td>
                    <td class="action">
                        @if ($keyLending && $keyLending->start_time == $keyLending->end_time)
                            <button type="button" class="btn btn-warning return-btn"
                                data-action="end" data-schedule-id="{{ $scheduleItem->id }}">
                                <i class="fa-solid fa-key" style="padding-right: 4px"></i>Return Key
                            </button>
                        @elseif($keyLending)
                            @php
                                $twentyMinutesAfterEnd = Carbon\Carbon::parse($scheduleItem->end_time)->addMinutes(20);
                            @endphp
                            @if (Carbon\Carbon::parse($keyLending->end_time)->lessThanOrEqualTo($twentyMinutesAfterEnd))
                                <button type="button" class="btn after-return disabled">
                                    Key Returned at <span>{{ $keyLending->end_time }}</span>
                                </button>
                            @elseif(Carbon\Carbon::parse($keyLending->end_time)->greaterThan($twentyMinutesAfterEnd))
                                <button type="button" class="btn return-late disabled">
                                    Returned Late at {{ $keyLending->end_time }}
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn cannot-return disabled">
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
        <h4 id="modalTitle">Modal Title</h4>
        <form id="keyActionForm" method="POST">
            @csrf
            <input type="hidden" id="scheduleId" name="schedule_id">
            <input type="hidden" id="actionType" name="action_type">
            <div class="modal-body">
                <p id="modalMessage">Please enter your password:</p>
                <input type="password" name="password" class="form-control" required>
                <div id="modalAlert" style="margin-top: 10px;"></div>
                <div class="form-actions">
                    <div class="close-user-button">
                        <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                    </div>
                    <div class="save-user-button">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i>Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

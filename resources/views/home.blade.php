@extends('layouts/main')

@section('isi')
    <div class="container">
        <div class="users">
            <div class="home-page">
                <div class="home-title-container">
                    <h1 class="home-title">Home</h1>

                    <h4>Welcome to FTI Untar: Key Retrieval Website!</h4>
                </div>
                <div class="date-and-time-container">
                    <div class="date-and-time">
                        <h5 id="current-date" class="current-date"><h5>
                        <h5 id="current-time" class="current-date"><h5>
                    </div>
                    <i class="fa-solid fa-clock fa-2xl"></i>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('home') }}" class="options">
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
        <nav class="section-navbar">
            <a href="#" onclick="showContent('incoming-schedule', this)" class="active-link" style="margin-left: 0px;">Incoming Schedule</a>
            <a href="#" onclick="showContent('key-lent', this)">Key Lent</a>
        </nav>

        <div id="incoming-schedule" class="table-container content active-content">
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

        <div id="key-lent" class="table-container content">
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
                    @foreach ($keyLendings as $index => $scheduleItem)
                        <tr class="user-content">
                            <td class="id">{{ $index + 1 }}</td>
                            <td class="start">{{ $scheduleItem->schedule->start_time }}</td>
                            <td class="end">{{ $scheduleItem->schedule->end_time }}</td>
                            <td class="subject">{{ $scheduleItem->schedule->assignment ? $scheduleItem->schedule->assignment->kelas->prodi . '-' . $scheduleItem->schedule->assignment->kelas->subject->name : '' }}</td>
                            <td class="lecturer">{{ $scheduleItem->schedule->assignment ? $scheduleItem->schedule->assignment->user->name : '' }}</td>
                            <td class="class">{{ $scheduleItem->schedule->assignment && $scheduleItem->schedule->assignment->kelas ? $scheduleItem->schedule->assignment->kelas->class : '' }}</td>
                            <td class="room">{{ $scheduleItem->schedule->room ? 'R' . $scheduleItem->schedule->room->room_number : '' }}</td>
                            <td class="action">
                                @php
                                    $tenMinutesBeforeStart = Carbon\Carbon::parse($scheduleItem->schedule->start_time)->subMinutes(10);
                                    $currentTime = now();
                                    $end_time = Carbon\Carbon::parse($scheduleItem->schedule->end_time, 'Asia/Bangkok');
                                    $keyLending = $keyLendings->where('schedule_id', $scheduleItem->schedule->id)->first();
                                @endphp

                                @if ($currentTime >= $tenMinutesBeforeStart && $currentTime <= $end_time && !$keyLending)
                                    <button type="button" class="btn btn-success lend-btn"
                                        data-action="start" data-schedule-id="{{ $scheduleItem->schedule->id }}">
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
                                        data-action="end" data-schedule-id="{{ $scheduleItem->schedule->id }}">
                                        <i class="fa-solid fa-key" style="padding-right: 4px"></i>Return Key
                                    </button>
                                @elseif($keyLending)
                                    @php
                                        $twentyMinutesAfterEnd = Carbon\Carbon::parse($scheduleItem->schedule->end_time)->addMinutes(20);
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
    </div>

    <!-- Common Modal Structure with Overlay -->
<div id="keyActionModal" class="modal {{ $errors->keyLending->any() ? 'open' : '' }}">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <h4 id="modalTitle">Modal Title</h4>
        <form id="keyActionForm" method="POST" value="">
            @csrf

            <input type="hidden" id="modalLendAction" name="formActionLend" value='' data-old-action="{{ old('formActionLend') }}">
            <input type="hidden" id="modalTitleForm" name="modalTitle" value='' data-old-value="{{ old('modalTitle') }}">
            <input type="hidden" id="modalMsgForm" name="modalMsg" value='' data-old-value="{{ old('modalMsg') }}">
            <input type="hidden" id="modalMsgFormAdmin" name="modalMsgAdmin" value='' data-old-value="{{ old('modalMsgAdmin') }}">
            <input type="hidden" id="scheduleId" name="schedule_id">
            <input type="hidden" id="actionType" name="action_type">
            <div class="modal-body">
                @if(auth()->user() && auth()->user()->role === 'Admin')
                <p id="modalMessageAdmin">Are you sure you want to Lend Key?</p>
                @else
                <p id="modalMessage">Please enter your password:</p>
                <input type="password" name="password" class="form-control" required>
                <div id="modalAlert" style="margin-top: 10px;"></div>
                @endif

                @error('password', 'keyLending') <span class="text-danger">{{ $message }}</span> @enderror
                <div class="form-actions">
                    <div class="close-user-button">
                        <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                    </div>

                    <div class="save-user-button">
                        @if(auth()->user() && auth()->user()->role === 'Admin')
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i>Yes</button>
                        @else
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i>Submit</button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

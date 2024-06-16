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
                @foreach ($schedule as $index => $schedule)
                    <tr class="user-content">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $schedule->start_time }}</td>
                        <td>{{ $schedule->end_time }}</td>
                        <td>{{ $schedule->assignment ? $schedule->assignment->subject->name : '' }}</td>
                        <td>{{ $schedule->assignment ? $schedule->assignment->user->name : '' }}</td>
                        <td>{{ $schedule->assignment && $schedule->assignment->kelas ? $schedule->assignment->kelas->prodi . '-' . substr($schedule->assignment->kelas->year, -2) . '-' . $schedule->assignment->kelas->class : '' }}</td>
                        <td>{{ $schedule->room ? 'R' . $schedule->room->room_number : '' }}</td>
                        <td>
                        @php
                            $tenMinutesBeforeStart = Carbon\Carbon::parse($schedule->start_time)->subMinutes(10);
                            $twentyMinutesAfterEnd = Carbon\Carbon::parse($schedule->end_time)->addMinutes(20);
                            $currentTime = now();
                            $end_time = Carbon\Carbon::parse($schedule->end_time, 'Asia/Bangkok');
                            $start_time = Carbon\Carbon::parse($schedule->start_time, 'Asia/Bangkok');
                            $keyLending = $keyLendings->where('schedule_id', $schedule->id)->first();
                        @endphp

                        @if ($currentTime >= $tenMinutesBeforeStart && $currentTime <= $end_time && !$keyLending)
                            <button type="button" class="btn btn-success lend-btn" data-action="start" data-schedule-id="{{ $schedule->id }}">
                                Lend Key
                            </button>
                        @elseif ($keyLending)
                            <button type="button" disabled style="background-color:yellow">
                            Key Lent at {{$keyLending->start_time}}
                            </button>
                        @else
                            <button type="button" disabled>
                                Lend Key
                            </button>
                        @endif
                        </td>
                        <td>
                            @if ($keyLending && $keyLending->start_time == $keyLending->end_time)
                                <button type="button" class="btn btn-warning return-btn" data-action="end" data-schedule-id="{{ $schedule->id }}">
                                    Return Key
                                </button>
                            @elseif($keyLending && Carbon\Carbon::parse($keyLending->end_time)->lessThanOrEqualTo($twentyMinutesAfterEnd))
                                <button type="button" disabled style="background-color: yellow">
                                    Key Returned at {{$keyLending->end_time}}
                                </button>
                            @elseif($keyLending && Carbon\Carbon::parse($keyLending->end_time)->greaterThan($twentyMinutesAfterEnd))
                                <button type="button" disabled style="background-color: red">
                                    Returned Late at {{$keyLending->end_time}}
                                </button>
                            @else
                                <button type="button" disabled>
                                        Return Key
                                </button>
                            @endif
                        </td>
                    </tr>
                    <!-- Lend Key Popup -->
                    <div id="lendModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h5>Lend Key Action</h5>
                            <form id="lendForm" action="{{ url('/key-lending/'. $schedule-> id.'/verify-update-start') }}" method="POST">
                                @csrf
                                <input type="hidden" id="lendScheduleId" name="schedule_id">
                                <input type="hidden" name="action_type" value="start">
                                <div class="modal-body">
                                    <p>Please enter your password to lend the key:</p>
                                    <input type="password" name="password" class="form-control" required>
                                    <div id="lendMessage" style="margin-top: 10px;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn close-modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Lend Key</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Return Key Popup -->
                    <div id="returnModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h5>Return Key Action</h5>
                            <form id="returnForm" action="{{ url('/key-lending/'. $schedule->id.'/verify-update-end') }}" method="POST">
                                @csrf
                                <input type="hidden" id="returnScheduleId" name="schedule_id">
                                <input type="hidden" name="action_type" value="end">
                                <div class="modal-body">
                                    <p>Please enter your password to return the key:</p>
                                    <input type="password" name="password" class="form-control" required>
                                    <div id="returnMessage" style="margin-top: 10px;"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn close-modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Return Key</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(document).ready(function () {
        // Handle click on lend button
        $('.lend-btn').click(function () {
            var scheduleId = $(this).data('schedule-id');
            $('#lendScheduleId').val(scheduleId); // Set schedule ID in the form
            var actionUrl = '/key-lending/' + scheduleId + '/verify-update-start';
            $('#lendForm').attr('action', actionUrl); // Update action URL
            $('#lendModal').show(); // Show lend modal
        });

        // Handle click on return button
        $('.return-btn').click(function () {
            var scheduleId = $(this).data('schedule-id');
            $('#returnScheduleId').val(scheduleId); // Set schedule ID in the form
            var actionUrl = '/key-lending/' + scheduleId + '/verify-update-end';
            $('#returnForm').attr('action', actionUrl); // Update action URL
            $('#returnModal').show(); // Show return modal
        });

        // Handle click on close button
        $('.close-modal, .modal .close').click(function () {
            $('.modal').hide();
            $('.modal').find('input[type=password]').val(''); // Clear password field
            $('.modal').find('.alert').remove(); // Remove any alert messages
        });
    });

</script>

@endsection

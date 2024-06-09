@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Schedule</h4>
                    <div class="date-navigation">
                        @if ($selectedDate > $earliestDate)
                        <a href="{{ route('schedule.view', ['date' => $prevDate]) }}" class="prev-date">Previous Date</a>
                        @else
                        <span class="prev-date disabled">Previous Date</span>
                        @endif
                        <span>{{ $selectedDate }} ({{ date('l', strtotime($selectedDate)) }})</span>
                        @if ($selectedDate < $furthestDate)
                        <a href="{{ route('schedule.view', ['date' => $nextDate]) }}" class="next-date">Next Date</a>
                        @else
                        <span class="next-date disabled">Next Date</span>
                        @endif
                        <a href="/view" class="today">Today</a>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->start_time }}</td>
                                <td>{{ $item->end_time }}</td>
                                <td>{{ $item->assignment->subject->name }}</td>
                                <td>{{ $item->assignment->user->name }}</td>
                                <td>{{ $item->assignment->kelas->prodi }}-{{ substr($item->assignment->kelas->year, -2) }}-{{ $item->assignment->kelas->class }}</td>
                                <td>{{ $item->room->room_number }}</td>
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

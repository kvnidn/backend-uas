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
        </div>

    </div>
@endsection

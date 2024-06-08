@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Schedule <a href="{{ url("schedule/create") }}" class="add-user">Add Schedule</a></h4>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Date</th>
                                <th class="user-heading">Start Time</th>
                                <th class="user-heading">End Time</th>
                                <th class="user-heading">Subject Name</th>
                                <th class="user-heading">Lecturer Name</th>
                                <th class="user-heading">Room Number</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule->sortBy('date') as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->date }}</td>
                                <td>{{ $item->start_time }}</td>
                                <td>{{ $item->end_time }}</td>
                                <td>{{ $item->assignment->subject->name }}</td>
                                <td>{{ $item->assignment->user->name }}</td>
                                <td>{{ $item->room->room_number}}</td>
                                <td>
                                    <a href="{{ url('schedule/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
                                    <a href="{{ url('schedule/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
                                    </form>
                                </td>
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

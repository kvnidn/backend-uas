@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                @if(auth()->user()->role == 'Admin')
                    <h4>Schedule <a href="{{ url('schedule/create') }}" class="add-user">Add Schedule</a></h4>
                @else
                    <h4>Schedule</h4>
                @endif
                </div>
                <div class="table-container">
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
                                    <td>{{ $group->first()->assignment->subject->name }}</td>
                                    <td>{{ $group->first()->assignment->user->name }}</td>
                                    <td>{{ $group->first()->assignment->kelas->prodi }}-{{ substr($group->first()->assignment->kelas->year, -2) }}-{{ $group->first()->assignment->kelas->class }}</td>
                                    <td>R{{ $group->first()->room->room_number }}</td>
                                    <td>
                                        @if(auth()->user()->role == 'Admin' || (auth()->user()->role == 'Lecturer' && auth()->user()->name == $group->first()->assignment->user->name))
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
                                                        <td>{{ $item->assignment->subject->name }}</td>
                                                        <td>{{ $item->assignment->user->name }}</td>
                                                        <td>{{ $item->assignment->kelas->prodi }}-{{ substr($item->assignment->kelas->year, -2) }}-{{ $item->assignment->kelas->class }}</td>
                                                        <td>R{{ $item->room->room_number }}</td>
                                                        <td>
                                                            @if(auth()->user()->role == 'Admin' || (auth()->user()->role == 'Lecturer' && auth()->user()->name == $group->first()->assignment->user->name))
                                                            <div class="actions">
                                                                <a href="{{ url('schedule/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
                                                            @endif

                                                            @if(auth()->user()->role == 'Admin')
                                                                <a href="{{ url('schedule/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure?')">Delete</a>
                                                            @endif
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
            </div>
        </div>
    </div>
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

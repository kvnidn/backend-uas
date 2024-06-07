@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Subjects <a href="{{ url("subject/create") }}" class="add-user">Add Subject</a></h4>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Subject Name</th>
                                <th class="user-heading">User</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subject->sortBy('id') as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->user->name ?? 'N/A'}}</td>
                                <td>
                                    <a href="{{ url('subject/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
                                    <a href="{{ url('subject/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
                                    <!-- <form id="delete-form-{{ $item->id }}" action="{{ url('subject/'.$item->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE') -->
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

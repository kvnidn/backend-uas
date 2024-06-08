@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Assignment <a href="{{ url("assignment/create") }}" class="add-user">Add Assignment</a></h4>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Subject Name</th>
                                <th class="user-heading">User</th>
                                <th class="kelas-heading">Class</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignment->sortBy('id') as $index => $item)
                            <tr class="user-content">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->subject->name }}</td>
                                <td>{{ $item->user->name ?? 'N/A'}}</td>
                                <td>{{ $item->kelas->prodi }}-{{ substr($item->kelas->year, -2) }}-{{ $item->kelas->class }}</td>
                                <td>
                                    <a href="{{ url('assignment/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
                                    <a href="{{ url('assignment/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
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

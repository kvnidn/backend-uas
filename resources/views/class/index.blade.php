@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            <div class="">
                <div class="users">
                    <h4>Class <a href="{{ url("class/create") }}" class="add-user">Add Classes</a></h4>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead class="table-header">
                            <tr class="table-header-row">
                                <th class="id-heading">ID</th>
                                <th class="name-heading">Prodi</th>
                                <th class="user-heading">Angkatan</th>
                                <th class="kelas-heading">Kelas</th>
                                <th class="action-heading">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($class->sortBy('year')->sortBy('prodi') as $index => $item)
                            @php $counter = $loop->iteration @endphp
                            <tr class="user-content">
                                <td>{{ $counter }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->year }}</td>
                                <td>{{ $item->class }}</td>
                                <td>
                                    <a href="{{ url('class/'.$item->id.'/edit') }}" class="edit-button">Edit</a>
                                    <a href="{{ url('class/'.$item->id.'/delete') }}" class="delete-button" onclick="return confirm('Are you sure ?')">Delete</a>
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

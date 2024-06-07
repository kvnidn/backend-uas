@extends('layouts/main')

@section('isi')
<div class="">
    <div class="">
        <div class="">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="">
                <div class="">
                    <h4>Create Subject <a href="{{ url("subject") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('/subject/create') }}" method="POST">
                        @csrf

                        <div class="form-name">
                            <label>Subject Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" />
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-user">
                            <label>User</label>
                            <select name="user_id">
                                <option value="" disabled selected>Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="save-user-button">
                            <button type="submit">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

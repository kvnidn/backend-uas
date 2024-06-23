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
                    <h4>Edit Users <a href="{{ url("user") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('user/'.$user->id.'/edit') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-name">
                            <label>Name</label>
                            <input type="text" name="name" value="{{  $user->name }}"/>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-email">
                            <label>Email</label>
                            <input type="text" name="email" value="{{  $user->email }}"/>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-password">
                            <label>Current Password</label>
                            <input type="password" name="password" value="" placeholder="required"/>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-password">
                            <label>New Password</label>
                            <input type="password" name="new_password" value="" placeholder="optional"/>
                            @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-role">
                            <label>Role</label>
                            @if (auth()->user()->role == 'Lecturer')
                            <input type="radio" name="role" value="Lecturer" {{ $user->role == 'Lecturer' ? 'checked': '' }}> Lecturer
                            @elseif (auth()->user()->role == 'Assistant')
                            <input type="radio" name="role" value="Assistant" {{ $user->role == 'Assistant' ? 'checked': '' }}> Assistant
                            @else
                            <input type="radio" name="role" value="Admin" {{ $user->role == 'Admin' ? 'checked': '' }}> Admin
                            <input type="radio" name="role" value="Lecturer" {{ $user->role == 'Lecturer' ? 'checked': '' }}> Lecturer
                            <input type="radio" name="role" value="Assistant" {{ $user->role == 'Assistant' ? 'checked': '' }}> Assistant
                            @endif
                            @error('role') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="save-user-button">
                            <button type="submit">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

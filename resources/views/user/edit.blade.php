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
                        </div>

                        <div class="form-email">
                            <label>Email</label>
                            <input type="text" name="email" value="{{  $user->email }}"/>
                        </div>

                        <div class="form-password">
                            <label>Current Password</label>
                            <input type="password" name="password" value="" placeholder="required"/>
                        </div>

                        <div class="form-password">
                            <label>New Password</label>
                            <input type="password" name="new_password" value="" placeholder="optional"/>
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
                        </div>

                        @if($errors->any())
                            <div class="form-errors">
                                @error('name')
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror

                                @error('email')
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror

                                @error('password')
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror

                                @error('new_password')
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror

                                @error('role')
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror
                            </div>
                        @endif

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

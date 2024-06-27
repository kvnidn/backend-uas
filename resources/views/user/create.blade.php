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
                    <h4>Users <a href="{{ url("user") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('/user/create') }}" method="POST">
                        @csrf

                        <div class="form-name">
                            <label>Name</label>
                            <input type="text" name="name" value="{{  old('name') }}"/>
                        </div>

                        <div class="form-email">
                            <label>Email</label>
                            <input type="text" name="email" value="{{  old('email') }}"/>
                        </div>

                        <div class="form-password">
                            <label>Password</label>
                            <input type="password" name="password" value="{{  old('password') }}"/>
                        </div>

                        <div class="form-role">
                            <label>Role</label>
                            <input type="radio" name="role" value="Admin" {{ old('role') == 'Admin' ? 'checked' : '' }}> Admin
                            <input type="radio" name="role" value="Lecturer" {{ old('role') == 'Lecturer' ? 'checked' : '' }}> Lecturer
                            <input type="radio" name="role" value="Assistant" {{ old('role') == 'Assistant' ? 'checked': '' }}> Assistant
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
                            <button type="submit">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

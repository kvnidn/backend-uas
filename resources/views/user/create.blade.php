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
                            @error('name') <span class="">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-email">
                            <label>Email</label>
                            <input type="text" name="email" value="{{  old('email') }}"/>
                            @error('email') <span class="">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-password">
                            <label>Password</label>
                            <input type="password" name="password" value="{{  old('password') }}"/>
                            @error('password') <span class="">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-role">
                            <label>Role</label>
                            <input type="radio" name="role" value="Admin" {{ old('role') == 'Admin' ? 'checked' : '' }}> Admin
                            <input type="radio" name="role" value="Lecturer" {{ old('role') == 'Lecturer' ? 'checked' : '' }}> Lecturer
                            <input type="radio" name="role" value="Assistant" {{ old('role') == 'Assistant' ? 'checked': '' }}> Assistant
                            @error('role') <span class="">{{ $message }}</span> @enderror
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

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

            @if (session('loginError'))
                <div class="alert alert-success">
                    {{ session('loginError') }}
                </div>
            @endif

            <div class="">
                <div class="">
                    <h3>Login <a href="{{ url("subject") }}" class="back-user"> Back</a></h3>
                </div>
                <div class="form-content">
                    <form action="{{ url('login') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="form-name">
                            <label>Email</label>
                            <input type="text" name="email" value="{{  old('email') }}" autofocus/>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-password">
                            <label>Password</label>
                            <input type="password" name="password" value="{{  old('password') }}"/>
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="save-user-button">
                            <button type="submit">Login</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

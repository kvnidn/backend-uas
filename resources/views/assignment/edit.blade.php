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

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="">
                <div class="">
                    <h4>Edit Subject <a href="{{ url("assignment") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('assignment/'.$assignment->id.'/edit') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-user">
                            <label>User</label>
                            <select name="user_id">
                                <option value="" disabled>Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $assignment->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-user">
                            <label>Class</label>
                            <select name="kelas_id">
                                <option value="" disabled selected>Select a Class</option>
                                @foreach($kelas as $kelas)
                                <option value="{{ $kelas->id }}" {{ $assignment->kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->prodi }}-{{ $kelas->subject->name }}-{{ $kelas->class }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        @if($errors->any())
                            <div class="form-errors">
                                @error('user_id') 
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> 
                                    @endif
                                @enderror
                                
                                @error('kelas_id') 
                                    @if ($message) 
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> 
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

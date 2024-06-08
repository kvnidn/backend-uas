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

                        <div class="form-name">
                            <label>Subject Name</label>
                            <select name="subject_id">
                                <option value="" disabled>Select a user</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
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
                            @error('user_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-user">
                            <label>Class</label>
                            <select name="kelas_id">
                                <option value="" disabled selected>Select a Class</option>
                                @foreach($kelas as $kelas)
                                <option value="{{ $kelas->id }}" {{ $assignment->kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->prodi }}-{{ substr($kelas->year, -2) }}-{{ $kelas->class }}
                                </option>
                                @endforeach
                            </select>
                            @error('kelas_id') <span class="text-danger">{{ $message }}</span> @enderror
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

@extends('layouts.main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            @if ($errors->has('time'))
                <div class="alert alert-danger">
                    {{ $errors->first('time') }}
                </div>
            @endif

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
                    <h4>Create Schedule <a href="{{ url('schedule') }}" class="back-user">Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('schedule/create') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" required>
                            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="time" name="start_time" id="start_time" required>
                            @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="time" name="end_time" id="end_time" required>
                            @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="assignment_id">Assignment</label>
                            <select name="assignment_id" id="assignment_id" required>
                                <option value="" disabled selected>Select an assignment</option>
                                @foreach($assignments as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->user->name }} - {{ $assignment->kelas->prodi }}-{{ $assignment->kelas->subject->name }}-{{ $assignment->kelas->class }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assignment_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="room_id">Room</label>
                            <select name="room_id" id="room_id" required>
                                <option value="" disabled selected>Select a room</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">
                                        R{{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="repeat">Repeat (weeks)</label>
                            <input type="number" name="repeat" id="repeat" min="0" max="52" value="0">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

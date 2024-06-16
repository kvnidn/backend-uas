@extends('layouts.main')

@section('isi')
<div class="">
    <div class="">
        <div class="">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                    <h4>Batch Edit Schedule <a href="{{ url('schedule') }}" class="back-user">Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('schedule/batch-update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        @foreach ($schedules as $schedule)
                                <input type="hidden" name="ids[]" value="{{ $schedule->id }}">
                                <input type="hidden" name="dates[]" value="{{ $schedule->date }}"> <!-- Add this line -->
                            @endforeach

                            <div class="form-group">
                                <label for="start_time">Start Time</label>
                                <input type="time" name="start_time" id="start_time" value="{{$schedule->start_time}}" required>
                                @error('start_time') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="end_time">End Time</label>
                                <input type="time" name="end_time" id="end_time" value="{{$schedule->end_time}}" required>
                                @error('end_time') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="assignment_id">Assignment</label>
                                <select name="assignment_id" id="assignment_id" required>
                                    <option value="" disabled>Select an assignment</option>
                                    @foreach($assignments as $assignment)
                                        <option value="{{ $assignment->id }}" {{ $schedule->assignment_id == $assignment->id ? 'selected' : '' }}>
                                            {{ $assignment->subject->name }} - {{ $assignment->user->name }} - {{ $assignment->kelas->prodi }}-{{ substr($assignment->kelas->year, -2) }}-{{ $assignment->kelas->class }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignment_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="room_id">Room</label>
                                <select name="room_id" id="room_id" required>
                                    <option value="" disabled>Select a room</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ $schedule->room_id == $room->id ? 'selected' : '' }}>
                                            R{{ $room->room_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <hr>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Schedules</button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

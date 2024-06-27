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
                    <h4>Rooms <a href="{{ url("room") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('/room/create') }}" method="POST">
                        @csrf

                        <div class="form-name">
                            <label>Room Number</label>
                            <input type="text" name="room_number" value="{{  old('room_number') }}"/>
                            <!-- @error('room_number') <span class="">{{ $message }}</span> @enderror -->
                        </div>

                        @if($errors->any())
                            <div class="form-errors">
                                @error('room_number')
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

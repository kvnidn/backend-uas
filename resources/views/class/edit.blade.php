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
                    <h4>Edit Class <a href="{{ url("class") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('class/'.$class->id.'/edit') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-prodi">
                            <label>Prodi</label>
                            <select name="prodi">
                                <option value="" disabled>Select a user</option>
                                <option value="TI" {{ $class->prodi === 'TI' ? 'selected' : '' }}>TI</option>
                                <option value="SI" {{ $class->prodi === 'SI' ? 'selected' : '' }}>SI</option>
                            </select>
                            @error('prodi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-year">
                            <label>Year</label>
                            <select name="year">
                                <option value="" disabled selected>Select year</option>
                                @for ($i = date('Y')-10;  $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $class->year === $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-class">
                            <label>Class</label>
                            <select name="class">
                                <option value="" disabled selected>Select Class</option>
                                @foreach (range('A', 'Z') as $char)
                                    <option value="{{ $char }}" {{ $class->class === $char ? 'selected' : ''}}>{{ $char }}</option>
                                @endforeach
                            </select>
                            @error('class') <span class="text-danger">{{ $message }}</span> @enderror
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
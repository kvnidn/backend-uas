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
                    <h4>Create Class <a href="{{ url("class") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('/class/create') }}" method="POST">
                        @csrf

                        <div class="form-prodi">
                            <label>Prodi</label>
                            <select name="prodi">
                                <option value="" disabled selected>Select a Prodi</option>
                                <option value="TI">TI</option>
                                <option value="SI">SI</option>
                            </select>
                            @error('prodi') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-year">
                            <label>Year</label>
                            <select name="year">
                                <option value="" disabled selected>Select year</option>
                                @for ($i = date('Y')-10;  $i <= date('Y'); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-class">
                            <label>Class</label>
                            <select name="class">
                                <option value="" disabled selected>Select Class</option>
                                @foreach (range('A', 'Z') as $char)
                                    <option value="{{ $char }}">{{ $char }}</option>
                                @endforeach
                            </select>
                            @error('class') <span class="text-danger">{{ $message }}</span> @enderror
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

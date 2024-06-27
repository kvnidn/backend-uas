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
                        </div>

                        <div class="form-name">
                            <label>Subject Name</label>
                            <select name="subject_id">
                                <option value="" disabled selected>Select a subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-class">
                            <label>Class</label>
                            <select name="class">
                                <option value="" disabled selected>Select Class</option>
                                @foreach (range('A', 'Z') as $char)
                                    <option value="{{ $char }}">{{ $char }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($errors->any())
                            <div class="form-errors">
                                @error('prodi') 
                                    @if ($message)
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror
                                
                                @error('subject_id')
                                    @if ($message) 
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> <br>
                                    @endif
                                @enderror

                                @error('class')
                                    @if ($message) 
                                        <span class="text-danger">
                                            {{ $message }}
                                        </span> 
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

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
                    <h4>Edit Subject <a href="{{ url("subject") }}" class="back-user"> Back</a></h4>
                </div>
                <div class="form-content">
                    <form action="{{ url('subject/'.$subject->id.'/edit') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-name">
                            <label>Subject Name</label>
                            <input type="text" name="name" value="{{  $subject->name }}"/>
                        </div>

                        @if($errors->any())
                            <div class="form-errors">
                                @error('name')
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

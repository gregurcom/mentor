@extends('layouts.layout')

@section('title', $course->title . ' - edit')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <form action="{{ route('platform.courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="text" name="title" class="form-control" value="{{ $course->title }}">
                    @error('title')
                        <div class="alert alert-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    <textarea name="description" class="form-control mt-2" rows="5">{{ $course->description }}</textarea>
                    @error('description')
                        <div class="alert alert-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="text-center mt-3">
                        <button class="btn btn-outline-dark" type="submit">{{ __('app.button.update') }}</button>
                    </div>
                </form>

                @if (session('status'))
                    <div class="alert alert-danger mt-2">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


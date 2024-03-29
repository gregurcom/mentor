@extends('layouts.layout')

@section('title', 'Mentor - ' . $category->name)

@section('content')
    <div class="container wrapper flex-grow-1 mt-5 mb-3">
        @if (session('status'))
            <div class="alert alert-dark mt-2 text-center">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('platform.course.search') }}" method="GET">
            <div class="row g-1 justify-content-end">
                <div class="col-auto">
                    <div id="search">
                        <i class="fa fa-search"></i>
                    </div>
                    <input type="hidden" name="categoryId" value="{{ $category->id }}">
                    <input id="search-input" type="search" name="searchValue" class="form-control border-dark search-input" placeholder="Search...">
                </div>
            </div>
        </form>
        @error('searchValue')
            <div class="alert alert-danger mt-1">
                {{ $message }}
            </div>
        @enderror

        <div class="mb-5 mt-3 text-center">
            <a href="{{ route('platform.courses.list', $category->id) }}" class="text-dark text-decoration-none head-link h1">{{ $category->name }}</a>
        </div>
        @forelse ($courses as $course)
            <div class="row mb-4">
                <div class="col col-md-8">
                    <div class="mb-2">
                        <a href="{{ route('platform.courses.show', $course->id) }}" class="text-decoration-none text-dark h4">{{ $course->title }}</a>
                        <div class="stars">
                            @if ($course->rates->count('id') > 0)
                                <span>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="star fa fa-star{{ round($course->averageRate()) >= $i ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-1 description">
                        {{ $course->description }}
                    </div>
                    <img src="{{ asset('images/' . $course->author->avatar) }}" width="25" height="20" alt="{{ $course->author->name }}">
                    <a href="#" class="text-decoration-none text-muted">{{ $course->author->name }}</a> ·
                    <span class="text-muted">{{ $course->created_at->isoformat('Do MMM YY') }}</span>
                </div>
                <div class="col col-md-4 d-flex justify-content-center">
                    <img src="{{ asset('images/404.png') }}" class="feed-image" width="250" height="180" alt="{{ $course->title }}">
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                {{ __('app.alert.category-without-courses') }}
            </div>
        @endforelse

        <div class="d-flex justify-content-center text-black">
            {{ $courses->links() }}
        </div>
    </div>
@endsection

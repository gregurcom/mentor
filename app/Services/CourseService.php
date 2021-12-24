<?php

declare(strict_types = 1);

namespace App\Services;

use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CourseService
{
    public function getCourses(Category $category): LengthAwarePaginator
    {
        $currentPage = request()->get('page', 1);

        if (Cache::has($category->id . '-category-' . $currentPage)) {
            $courses = Cache::get($category->id . '-category-' . $currentPage);
        } else {
            $courses = Cache::remember($category->id . '-category-' . $currentPage, now()->addMinutes(30), function () use ($category) {
                return $category->courses()->with(['author', 'rates'])->paginate(10);
            });
        }

        return $courses;
    }

    public function storeCourse(StoreCourseRequest $request): Course
    {
        if ($request->hasFile('image')){
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $imageName = $request->image->hashName();
            $request->image->move(public_path('images'), $imageName);
        }

        return Course::create(array_merge(['user_id' => Auth::id(), 'image' => $imageName], $request->validated()));
    }

    public function searchCourse(SearchRequest $request): AnonymousResourceCollection
    {
        return CourseResource::collection(Course::where('title', 'like', '%' . $request->q . '%')
            ->orWhere('description', 'like', '%' . $request->q . '%')
            ->with(['rates', 'author'])
            ->get());
    }

    public function searchCourseOnCategory(SearchRequest $request): AnonymousResourceCollection
    {
        return CourseResource::collection(
            Course::where('category_id', $request->categoryId)
                ->where(function ($query) use ($request) {
                    return $query->where('title', 'like', '%' . $request->q . '%')
                        ->orWhere('description', 'like', '%' . $request->q . '%');
                })
                ->with(['rates', 'author'])
                ->paginate(10)
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class CourseController extends Controller
{
    public function list(Category $category, CourseService $courseService): View
    {
        $courses = $courseService->getCourses($category);

        return view('platform.category', compact(['courses', 'category']));
    }

    public function show(Course $course): View
    {
        return view('platform.course.index', compact('course'));
    }

    public function create(): View
    {
        $categories = Category::get();

        return view('platform.course.create', compact('categories'));
    }

    public function store(StoreCourseRequest $request, CourseService $courseService): RedirectResponse
    {
        $courseService->storeCourse($request);

        return redirect()->route('dashboard')->with('status', __('app.alert.create-course'));
    }

    public function edit(Course $course): View
    {
        $categories = Category::get();

        return view('platform.course.edit', compact(['course', 'categories']));
    }

    public function update(Course $course, UpdateCourseRequest $request): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()->route('dashboard')->with('status', __('app.alert.edit-course'));
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return back()->with('status', __('app.alert.delete-course'));
    }

    public function search(SearchRequest $request, CourseService $courseService): View
    {
        $courses = $courseService->searchCourseOnCategory($request);
        $category = Category::find($request->categoryId);

        return view('platform.category', compact(['courses', 'category']));
    }
}

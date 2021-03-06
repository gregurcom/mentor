<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\DTO\Factories\CreateLessonDtoFactory;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\LessonService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

final class LessonController extends Controller
{
    public function __construct(private CreateLessonDtoFactory $createLessonDtoFactory) {}

    public function show(Lesson $lesson): View
    {
        $readDuration = $lesson->getReadDuration();

        return view('platform.course.lesson.index', compact(['lesson', 'readDuration']));
    }

    public function create(Course $course): View
    {
        return view('platform.course.lesson.create', compact('course'));
    }

    public function store(StoreLessonRequest $lessonRequest, StoreFileRequest $fileRequest, LessonService $lessonService): RedirectResponse
    {
        DB::transaction(function () use ($lessonRequest, $fileRequest, $lessonService) {
            $dto = $this->createLessonDtoFactory->createFromRequest($lessonRequest);
            $lesson = $lessonService->store($dto);

            if ($fileRequest->hasFile('files')) {
                $lessonService->storeAttachedFiles($lesson, $fileRequest);
            }
            if ($dto->status == 1) {
                // send emails to subscribers with a link to lesson
                $lessonService->sendLessonCreateNotification($lesson);
            }
        });

        return redirect()->route('platform.courses.show', $lessonRequest->course_id)->with('status', __('app.alert.create-lesson'));
    }

    public function edit(Lesson $lesson): View
    {
        $this->authorize('view', $lesson);

        return view('platform.course.lesson.edit', compact('lesson'));
    }

    public function update(Lesson $lesson, UpdateLessonRequest $request): RedirectResponse
    {
        $this->authorize('update', $lesson);
        $lesson->update($request->validated());

        return redirect()->route('platform.courses.show', $lesson->course)->with('status', __('app.alert.edit-lesson'));
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $this->authorize('destroy', $lesson);
        $lesson->delete();

        return back()->with('status', __('app.alert.delete-lesson'));
    }
}

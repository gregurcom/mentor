<?php

declare(strict_types=1);

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitTechSupportRequest;
use App\Mail\TechSupportMail;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

final class TechSupportController extends Controller
{
    public function show(): View
    {
        return view('platform.tech-support');
    }

    public function send(SubmitTechSupportRequest $request): RedirectResponse
    {
        Mail::to(config('app.admin-email'))->send(new TechSupportMail($request->text));

        return redirect()->route('dashboard')->with('status', __('app.alert.tech-support'));
    }
}

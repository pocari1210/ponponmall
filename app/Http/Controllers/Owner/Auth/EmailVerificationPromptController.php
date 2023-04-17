<?php

//namespaceをOwnerにあわせる
namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()

                    //リダイレクト先をOWNER_HOMEに変更
                    ? redirect()->intended(RouteServiceProvider::OWNER_HOME)
                    //prefexで先頭にownerとつける
                    : view('owner.auth.verify-email');
    }
}

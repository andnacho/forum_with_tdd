<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/threads';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function index()
    {
        $user = User::where('confirmation_token', request('token'))
            ->first();

        if (! $user) {
            return redirect(route('threads'))
                ->with([
                    'flash' => 'Unknown token.',
                    'type' => 'danger',
                    'title' => 'It is no working'
                ]);
        }

        $user->markEmailAsVerified();

        return redirect('/threads')
            ->with('flash', 'Your account is now confirmed! You may post to the forum.');
    }
}

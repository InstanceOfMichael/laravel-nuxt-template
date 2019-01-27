<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileEmail;

class EmailController extends Controller
{
    /**
     * Update the user's email information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileEmail $request)
    {
        $user = $request->user();

        $user->email = $request->email;
        $user->email_verified_at = null;

        $user->save();

        $user->sendEmailVerificationNotification();

        return $user;
    }
}

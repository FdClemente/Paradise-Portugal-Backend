<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Settings\LegalSettings;

class PrivacyController extends Controller
{
    public function index()
    {
        $settings = app(LegalSettings::class);

        $privacy = $settings->privacy_policy;

        return view('privacy', compact('privacy'));
    }
}

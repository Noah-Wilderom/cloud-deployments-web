<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;

class TwoFactorController extends Controller
{
    public function view(): InertiaResponse {
        return inertia("Auth/EnableTwoFactor");
    }
}

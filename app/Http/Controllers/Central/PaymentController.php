<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Show the central landing page.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $id = $request->id;
        $token = $request->token;
        $status = $request->status;

        Payment::updateStatus($id, $token, $status);
    }
}

<?php

namespace App\Http\Controllers;

use App\Delivery;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function callback(Request $request)
    {
        $sid = $request->input('MessageSid');
        $status = $request->input('MessageStatus');

        $status = Delivery::firstWhere('');
    }
}

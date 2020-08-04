<?php

namespace App\Http\Controllers;

use App\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\DeliveryStatusUpdated;

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
	Log::info(sprintf('LOG: %s', $request->input('SmsStatus')));
        $sid = $request->input('SmsSid');
        $status = $request->input('SmsStatus');

        $deliveryStatus = Delivery::where('message_sid', '=', $sid)->first();
	$deliveryStatus->status = $status;
	$deliveryStatus->save();

	DeliveryStatusUpdated::dispatch($deliveryStatus);

	return ['status' => $status];
    }
}

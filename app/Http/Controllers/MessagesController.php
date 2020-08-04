<?php

namespace App\Http\Controllers;

use App\Events\DeliveryStatusUpdated;
use App\Events\NewMessageNotification;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Messages;
use App\Delivery;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class MessagesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function getMessages(Request $request)
    {
        $user = auth()->user();
        $length = $request->input('length');
        $column = $request->input('column');
        $orderByDir = $request->input('dir', 'desc');
        $search_input = $request->input('search');

        if ($column === 'status.status') {
            $column = 'deliveries.status';
        }

        $query = Messages::with('status')
            ->where('owner_id', '=', $user->id)
            ->orderBy($column, $orderByDir);

        if ($search_input) {
            $query->where(function($query) use ($search_input) {
                $query->where('name', 'like', '%' . $search_input . '%')
                ->orWhere('order', 'like', '%' . $search_input . '%')
                ->orWhere('phone', 'like', '%' . $search_input . '%')
                ->orWhere('created_at', 'like', '%' . $search_input . '%');
            });
        }

        $data = $query->paginate($length);

        return new DataTableCollectionResource($data);
    }

    /**
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function addMessage(Request $request)
    {
        try {
            $user = auth()->user();
            $message = new Messages([
                'name' => $request->input('name'),
                'order' => $request->input('order'),
                'phone' => $request->input('phone'),
                'message' => $request->input('message'),
            ]);
            $message->owner()->associate($user);
            $message->save();

            if ($user->twilio_phone) {
                $client = new Client($user->twilio_sid, $user->twilio_token);
                $sentMessage = $client->messages->create($message->phone, [
                    'from' => $user->twilio_phone,
                    'body' => $message->message,
                    'statusCallback' => 'https://www.greekfreak.ca/messages-latest/public/delivery/status',
                ]);
                if ($sentMessage->sid) {
                    $delivery = new Delivery([
                        'sender_id' => $user->id,
                        'account_sid' => $user->twilio_sid,
                        'message_sid' => $sentMessage->sid,
                        'status' => $sentMessage->status,
                    ]);
                    $delivery->save();
                    $message->status()->save($delivery);
		    event(new NewMessageNotification($message));
                }
            }
            $message->refresh();

            return Messages::where('id', '=', $message->id)->with('status')->first()->toJson();
        } catch (\Exception $e) {
            return json_encode([$e->__toString()]);
        }
    }

    /**
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function updateMessage(Request $request) {
        $user = auth()->user();
        $message = Messages::find($request->id);
        if ($message->owner->id == $user->id) {
            foreach (['name', 'order', 'phone', 'message', 'picked_up', 'created_at'] as $column) {
                if (isset($request->$column)) {
                    $message->$column = $request->$column;
                }
            }
            $message->save();
        }
        return $message->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * @param \App\Messages
     * @return string
     */
    public function deleteMessage(Messages $message) {
        $user = auth()->user();
        if ($message->owner->id == $user->id) {
            $message->delete();
        }
        return ['success' => 'message deleted'];
    }

    /**
     * @param Request
     * @return array
     */
    public function deliveryTrack(Request $request)
    {
        $user = auth()->user();

        $sent = Delivery::where('message_sid', '=', $_GET['id'])->first();
        $sent->status = 'sent';
        $sent->save();

        event(new DeliveryStatusUpdated($sent));
        return $sent->toJson();
    }

}

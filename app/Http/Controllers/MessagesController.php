<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Messages;
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
        $columns = ['name', 'order', 'phone', 'message', 'created_at', 'picked_up'];
        $length = $request->input('length');
        $column = $request->input('column');
        $orderByDir = $request->input('dir', 'desc');
        $search_input = $request->input('search');

        $query = Messages::select('id', 'name', 'order', 'phone', 'message', 'created_at', 'picked_up')
            ->orderBy($column, $orderByDir)
            ->where('owner_id', '=', $user->id);

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
                $client->messages->create($message->phone, [
                    'from' => $user->twilio_phone,
                    'body' => $message->message,
                ]);
            }
            
            /*$accountSid = getenv("TWILIO_SID");
            $authToken = getenv("TWILIO_AUTH_TOKEN");
            $twilioNumber = getenv("TWILIO_NUMBER");
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($message->phone, [
                'from' => $twilioNumber,
                'body' => $message->message,
            ]);*/

            return $message->toJson(JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return json_encode([
                'error' => $e->getMessages(),
            ]);
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

}
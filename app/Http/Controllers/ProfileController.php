<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected $validationRules = [
        'account' => [
            'name' => 'nullable|max:128',
            'email' => 'nullable|max:255',
            'password' => 'nullable|string|min:8|confirmed'
        ],
        'twilio' => [
            'twilio_phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'twilio_sid' => 'nullable|string|alpha_num',
            'twilio_token' => 'nullable|string|alpha_num',
            'default_message' => 'nullable|string',
        ],
    ];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('profile');
    }

    /**
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function getProfile(Request $request)
    {
        return $this->getMappedResponse();
    }

    /**
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function updateProfile(Request $request)
    {
        $account = auth()->user();
        $columns = Schema::getColumnListing($account->getTable());
        $section = $request->input('_section');
        $data = $request->input('data');

        if (!is_null($section) && in_array($section, ['account', 'twilio'])) {

            $validator = Validator::make($data, $this->validationRules[$section]);

            if ($validator->fails()) {
                $response = $this->getMappedResponse();
                $response['error'] = $validator->errors()->toArray();

                return $response;
            } else {
                foreach ($data as $key => $value) {
                    if (!is_null($value) && in_array($key, $columns)) {
                        if ($key === 'password') {
                            $value = Hash::make($value);
                        }
                        $account->$key = $value;
                    }
                }
                $account->save();
            }
        }

        return $this->getMappedResponse();
    }

    /**
     * @return array
     */
    protected function getMappedResponse()
    {
        $user = auth()->user();

        return [
            'account' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'twilio' => [
                'twilio_phone' => $user->twilio_phone,
                'twilio_sid' => $user->twilio_sid,
                'twilio_token' => $user->twilio_token,
                'default_message' => $user->default_message,
            ],
        ];
    }
}

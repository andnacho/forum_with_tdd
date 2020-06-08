<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/',
            'timeout' => 2.0
        ]);

        $res = $client->request('post', 'siteverify', [
            'query' => [
                'secret' =>	config('services.recaptcha.secret'),
                'response' =>	$value,
                'remoteip' =>	request()->ip()
            ]
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

        return $response['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The recaptcha field is required';
    }
}

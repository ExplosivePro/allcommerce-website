<?php

namespace App\Actions\PaymentGateways;

use App\Models\PaymentGateways\ClientEnabledPaymentProviders;
use App\Models\PaymentGateways\PaymentProviders;
use Lorisleiva\Actions\Action;

class EnableClientGateway extends Action
{
    /**
     * Determine if the user is authorized to make this action.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Execute the action and return a result.
     * @param $uuid
     * @param PaymentProviders $providers
     * @return mixed
     */
    public function handle($uuid, PaymentProviders $providers)
    {
        // Execute the action.
        return $providers->find($uuid);
    }

    /**
     * Assign a Gateway to an Array of Shops
     * @param $result
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function response($result, $request)
    {
        $data = $request->all();
        $client = backpack_user()->client()->first();

        $enabled = ClientEnabledPaymentProviders::firstOrCreate([
            'client_id' => $client->id,
            'provider_id' => $result->id
        ]);

        if(array_key_exists('_token', $data))
        {
            unset($data['_token']);
        }

        $enabled->active = 1;
        $enabled->misc = $data;

        if($enabled->save())
        {
            \Alert::success('Gateway successfully enabled For your account!')->flash();
        }
        else
        {
            \Alert::error('Could not save. Try again')->flash();
        }

        // Return to the referral URL
        return redirect()->back()->withInput();
    }
}

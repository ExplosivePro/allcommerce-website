<?php

namespace App\Actions\SMSProviders;

use App\Models\SMS\ClientEnabledSmsProviders;
use App\Models\SMS\SmsProviders;
use Lorisleiva\Actions\Action;

class ManageClientEnabledSMS extends Action
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
     * @param SmsProviders $providers
     * @return mixed
     */
    public function handle($uuid, SmsProviders $providers)
    {
        // Execute the action.
        return $providers->find($uuid);
    }

    public function response($result, $request)
    {
        $client = backpack_user()->client()->first();

        $data = [];
        $data['entry'] = $result;
        $data['client'] = $client;
        $data['title'] = $client->name.' '.trans('backpack::base.dashboard');

        $data['breadcrumbs'] = [
            env('APP_NAME')     => backpack_url('dashboard'),
        ];

        if($client->account_owner == backpack_user()->id)
        {
            $data['breadcrumbs'][$client->name] = '/access/clients/'.$client->id.'/edit';
        }
        else
        {
            $data['breadcrumbs'][$client->name] = false;
        }

        $data['breadcrumbs']['SMS Providers'] = '/access/sms-providers';
        $data['breadcrumbs']['Manage '.$result->name] = false;

        $data['gateway_name'] = $result->name;

        $gate_attrs = $result->provider_attributes()->get();
        $provider_desc = $gate_attrs->where('name', '=', 'Description')->first();
        $data['form_fields'] = $gate_attrs->where('name', '=', 'config');
        $data['gate_status'] = $gate_attrs->where('name', '=', 'Status')->first();
        $data['commission']  = $gate_attrs->where('name', '=', 'Commission')->first();

        $data['widgets']['before_content'][] = [
            'type' => 'alert',
            'wrapper' => ['class' => 'col-md-12'],
            'class' => 'alert alert-warning col-md-8 text-dark margin-auto',
            'heading' => '',
            'content' => '<small><i>'.$provider_desc->misc[0].'</i></small>'
        ];

        $data['enabled'] = [];
        $enabled = ClientEnabledSmsProviders::whereClientId($client->id)
            ->whereProviderId($result->id)->whereActive(1)->first();

        if(!is_null($enabled))
        {
            $data['enabled'] = $enabled->toArray();
        }

        return view('sms-providers.clients.manage-sms', $data);
    }
}

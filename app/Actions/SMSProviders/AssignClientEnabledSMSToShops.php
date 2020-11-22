<?php

namespace App\Actions\SMSProviders;

use App\Models\PaymentGateways\ShopAssignedPaymentProviders;
use Lorisleiva\Actions\Action;
use App\Models\SMS\SmsProviders;
use App\Models\SMS\ShopAssignedSmsProviders;
use App\Models\SMS\ClientEnabledSmsProviders;
use App\Aggregates\Shops\ShopConfigAggregate;
use App\Exceptions\Shops\CouldNotAssignGatewayToShop;

class AssignClientEnabledSMSToShops extends Action
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

    /**
     * Assign an SMS Provider to an Array of Shops
     * @param $result
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function response($result, $request)
    {
        $data = $request->all();
        $client = backpack_user()->client()->first();

        $client_enabled = ClientEnabledSmsProviders::whereClientId($client->id)
            ->whereProviderId($result->id)->whereActive(1)
            ->first();

        // Check if the provider is enabled with the client or fail
        if(!is_null($client_enabled))
        {
            // Check if 'assigned' exists in data or run alt path
            if(array_key_exists('assigned', $data))
            {
                $map = [];
                // Foreach uuid,
                foreach($data['assigned'] as $shop_id => $one)
                {
                    $map[] = $shop_id;
                    $aggy = ShopConfigAggregate::retrieve($shop_id);
                    // insert a record into shop_assigned_sms_providers
                    $assy = ShopAssignedSmsProviders::firstOrCreate([
                        'shop_uuid' => $shop_id,
                        'client_enabled_uuid' => $client_enabled->id,
                        'provider_uuid' => $result->id,
                        'merchant_uuid' => $aggy->getMerchantId(),
                        'client_uuid' => $client->id
                    ]);

                    $assy->active = 1;
                    $assy->save();

                    try {
                        $aggy->createOrUpdateAssignedSMSProvider($result->toArray(), $assy->id)
                            ->persist();

                        \Alert::success('Assigned '.$aggy->getShopName().' to '.$result->name)->flash();
                    }
                    catch(CouldNotAssignGatewayToShop $e)
                    {
                        $assy->forceDelete();
                        \Alert::warning($e->getMessage().' to '.$aggy->getShopName().'. ')->flash();
                    }
                }

                // get the records for all assigned_records for the shops not listed in assigned
                $doomed_assignments = ShopAssignedPaymentProviders::whereProviderUuid($result->id)
                    ->whereClientUuid($client->id)->whereActive(1)
                    ->whereNotIn('shop_uuid', $map)
                    ->get();

                if(count($doomed_assignments) > 0)
                {
                    // delete them
                    foreach($doomed_assignments as $doomed_assignment)
                    {
                        $doomed_assignment->active = 0;
                        $doomed_assignment->save();

                        $aggy = ShopConfigAggregate::retrieve($shop_id)
                            ->removeAssignedSMSProvider($result->toArray(), $doomed_assignment->id);

                        $doomed_assignment->delete();

                        \Alert::info('Un-assigned '.$aggy->getShopName().' from '.$result->name)->flash();
                        $aggy->persist();
                    }
                }
            }
            else
            {
                // Check for all records in the shop_assigned_payment_providers for
                //   provider_id and client_enabled_id.
                $doomed_assignments = ShopAssignedSmsProviders::whereProviderUuid($result->id)
                    ->whereClientUuid($client->id)
                    ->whereActive(1)
                    ->get();

                if(count($doomed_assignments) > 0)
                {
                    // delete them
                    foreach($doomed_assignments as $doomed_assignment)
                    {
                        // If there is any, deactivate them and softly delete them.
                        $doomed_assignment->active = 0;
                        $doomed_assignment->save();

                        $aggy = ShopConfigAggregate::retrieve($doomed_assignment->shop_uuid)
                            ->removeAssignedSMSProvider($result->toArray(), $doomed_assignment->id);

                        $doomed_assignment->delete();

                        \Alert::info('Un-assigned '.$aggy->getShopName().' from '.$result->name)->flash();

                        $aggy->persist();
                    }

                }
                else
                {
                    \Alert::warning('No Shops to Assign.')->flash();
                }
            }
        }
        else
        {
            \Alert::error('Could not assign Shop(s) to '.$result->name.'. This account is missing a profile for this provider.')->flash();
        }

        // Return to the referral URL
        return redirect()->back();
    }
}

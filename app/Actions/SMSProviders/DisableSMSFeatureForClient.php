<?php

namespace App\Actions\SMSProviders;

use App\Aggregates\Clients\ClientAccountAggregate;
use Lorisleiva\Actions\Action;

class DisableSMSFeatureForClient extends Action
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
     *
     * @return mixed
     */
    public function handle()
    {
        // Execute the action.
        return backpack_user()->client()->first();
    }

    /**
     * Assign an SMS Provider to an Array of Shops
     * @param $result
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function response($result, $request)
    {
        $aggy = ClientAccountAggregate::retrieve($result->id);
        $sms_feature = $result->sms_enabled()->first();
        $sms_feature->active = 0;
        if($sms_feature->save())
        {
            $aggy->disableSMSOnShops()->persist();
            \Alert::success('SMS is Now Disabled. ):')->flash();
        }
        else
        {
            \Alert::error('Was not able to Disable SMS. Please Try Again.')->flash();
        }

        return redirect()->back();
    }
}

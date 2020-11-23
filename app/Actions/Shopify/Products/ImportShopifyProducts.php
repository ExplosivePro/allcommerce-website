<?php

namespace App\Actions\Shopify\Products;

use App\Models\Shops\Shop;
use Lorisleiva\Actions\Action;

class ImportShopifyProducts extends Action
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
     * @param Shop $shops
     * @return mixed
     */
    public function handle($uuid, Shop $shops)
    {
        // Execute the action.
        return $shops->find($uuid);
    }

    /**
     * Assign an SMS Provider to an Array of Shops
     * @param $result
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function response($result, $request)
    {
        /**
         * STEPS
         * 1. See if the shop is a shopify shop for fail w/ error alert
         * 2. Migrate and dispatch the job from the oauth API onboarding
         * 3. Send back success alert that shit will be ready soon
         * @todo - see if pusher will fire an pnotify
         */
        // Return to the referral URL
        return redirect()->back();
    }
}

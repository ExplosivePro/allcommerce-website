<?php

namespace App\Actions\Shopify;

use Ramsey\Uuid\Uuid;
use Lorisleiva\Actions\Action;
use App\Models\Shopify\ShopifyInstalls;

class InstallShop extends Action
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
    }

    public static function run($shop_url)
    {
        $install_model = new ShopifyInstalls();
        $record = $install_model->getByShopUrl($shop_url);

        if($record)
        {
            // if exists, set a new nonce value with success.
            $record->nonce = Uuid::uuid4();
            $record->save();

            $nonce = $record->nonce;
        }
        else
        {
            // If not exists, create record or fail.
            $record = $install_model->insertNonceRecord($shop_url);

            if($record)
            {
                $nonce = $record->nonce;
            }
            else
            {
                $nonce = false;
            }

            // send back just the nonce value with the success
            if($nonce)
            {
                $results = ['success'=> true, 'nonce' => $nonce];
            }
            else
            {
                $results['reason'] = 'Could not generate nonce';
            }
        }
    }
}

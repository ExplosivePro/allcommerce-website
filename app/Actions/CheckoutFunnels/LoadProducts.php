<?php

namespace App\Actions\CheckoutFunnels;

use App\Models\Shops\Shop;
use Lorisleiva\Actions\Action;

class LoadProducts extends Action
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
        return [
            'shop_id' => ['required', 'exists:shops,id']
        ];
    }

    /**
     * Execute the action and return a result.
     * @param Shop $shops
     * @return mixed
     */
    public function handle(Shop $shops)
    {
        $results = ['success' => false, 'options' => ['' => 'No Products Available for this Shop. Try Another']];
        // Execute the action.
        $data = $this->validated();
        $options = ['' => 'Select a Product'];
        $shop = $shops->find($data['shop_id']);
        $products = $shop->products()->get();

        if(count($products) > 0)
        {
            foreach ($products as $product)
            {
                $variants = $product->variants()->get();

                if(count($variants) > 0)
                {
                    foreach ($variants as $variant)
                    {
                        $options[$variant->id] = $product->title.': '.$variant->title.' ($'.$variant->price.')';
                    }
                }
            }

            if(count($options) > 0)
            {
                $results = ['success' => true, 'options' => $options];
            }
        }

        return response($results);
    }
}

<?php

namespace AllCommerce\Http\Controllers\API\Checkouts\Payments;

use AllCommerce\Leads;
use AllCommerce\Shops;
use AllCommerce\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use AllCommerce\Http\Controllers\Controller;
use AllCommerce\DepartmentStore\Library\Sales\Order;

class CreditCardPaymentAPIController extends Controller
{
    protected $leads, $request, $shops_model;

    public function __construct(Request $request, Leads $leads, Shops $shops)
    {
        $this->leads = $leads;
        $this->request = $request;
        $this->shops_model = $shops;
    }

    public function auth_credit_card(Order $ac_order)
    {
        $results = ['success' => false , 'reason' => 'Unknown error, you were not charged.'];

        $data = $this->request->all();

        //run the validationz
        $validated = Validator::make($data, [
            'cc'        => 'bail|required',
            'ccName'    => 'bail|required',
            'ccExpy'    => 'bail|required',
            'ccCvv'     => 'bail|required',
            'price'     => 'bail|required',
            'leadUuid'  => 'bail|required|exists:leads,id',
        ]);

        if($validated->fails())
        {
            foreach($validated->errors()->toArray() as $col => $msg)
            {
                $results['reason'] = $msg[0];
                break;
            }
        }
        else
        {
            $lead = $this->leads->find($data['leadUuid']);

            // get the access token from the merchant_api_tokens table
            $token_record = $this->shops_model->whereId($lead->shop_uuid)
                ->with('oauth_api_token')->first();

            if(!is_null($token_record->oauth_api_token))
            {
                // Use the lead to create or retrieve an order object
                $ac_order->setLeadId($lead->id);
                $ac_order->setAccessToken($token_record->oauth_api_token->token);

                // Use the DepartmentStore to Create a new Order.
                if($ac_order->get())
                {
                    $payload = $data;
                    $payload['orderUuid'] = $ac_order->getOrderId();

                    if($ac_order->getShopType() == 'Shopify')
                    {
                        $payload['shopifyDraftOrderId'] = $ac_order->getShopifyDraftOrder()['id'];
                    }

                    // Use populated Department Store Object to send the Payment
                    if($response = $ac_order->processCreditPaymentAuth($payload))
                    {
                        $results = $response;
                    }
                    else
                    {
                        $results['reason'] = 'Payment Authorization Denied';
                    }
                }
                else
                {
                    $results['reason'] = 'Could not create order. You were not charged.';
                }
            }
        }

        return response($results);
    }

    public function capture_credit_card(Transactions $transactions, Order $ac_order)
    {
        $results = ['success' => false , 'reason' => 'Unknown error, charge was not captured.'];

        $data = $this->request->all();

        if(array_key_exists('transactionId', $data))
        {
            // Get the transaction from the transactions table or fail.
            if(!is_null($transaction = $transactions->find($data['transactionId'])))
            {
                // Get the order record from the transaction
                if(!is_null($order = $transaction->order()->first()))
                {
                    // Get the Shop's API token
                    $token_record = $this->shops_model->whereId($order->shop_uuid)
                        ->with('oauth_api_token')->first();

                    if(!is_null($token_record->oauth_api_token))
                    {
                        // Init a Department Store Order and pass in the transaction id
                        $ac_order->setLeadId($order->lead_uuid);
                        $ac_order->setAccessToken($token_record->oauth_api_token->token);

                        $payload = ['transaction_uuid' => $data['transactionId']];
                        // Call the Capture Payment Method
                        if($response = $ac_order->processCreditPaymentCapture($payload))
                        {
                            // If successful it will return some success data including a url
                            if($response['success'])
                            {
                                // Return success with success_url
                                $results = ['success' => true, 'success_url' => $response['success_url']];
                            }
                        }
                    }
                }
            }
        }

        return response($results);
    }
}

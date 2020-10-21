<?php

namespace AllCommerce\Http\Controllers\API\Checkouts\Payments;

use AllCommerce\Http\Controllers\Controller;
use AllCommerce\Leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditCardPaymentAPIController extends Controller
{
    protected $leads, $request;

    public function __construct(Request $request, Leads $leads)
    {
        $this->leads = $leads;
        $this->request = $request;
    }

    public function auth_credit_card()
    {
        $results = ['success' => false , 'reason' => 'Unknown error, you were not charged.'];

        $data = $this->request->all();

        //run the validationz
        $validated = Validator::make($data, [
            'cc'        => 'bail|required',
            'ccName' => 'bail|required',
            'ccExpy'   => 'bail|required',
            'ccCvv'     => 'bail|required',
            'leadUuid'    => 'sometimes|required|exists:leads,id',
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
            /**
             * STEPS
             * 1. Use the lead UUID to get the details needed to set up a DepartmentStore session.
             * 2. Use the DepartmentStore to  Get the Lead.
             * 3. Use the DepartmentStore to Create a new Order.
             * 4. Use populated Department Store Object to send the Payment
             * 5. Send back the response.
             */
        }

        return response($results);
    }

    public function capture_credit_card()
    {
        $results = ['success' => 'false' , 'reason' => 'Unknown error, charge was not captured.'];

        return response($results);
    }
}

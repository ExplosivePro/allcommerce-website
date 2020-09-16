<?php

namespace AnchorCMS\Http\Controllers\API\Checkouts;

use AnchorCMS\Actions\Leads\CreateOrUpdateLead;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LeadsAPIController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function create_or_update_lead(CreateOrUpdateLead $action)
    {
        $results = ['success' => false];

        $data = $this->request->all();

        $validated = Validator::make($data, [
            'reference' => 'bail|required|in:email,shipping',
            'value' => 'bail|required',
            'checkoutType' => 'bail|required',
            'checkoutId' => 'bail|required',
            'shopUuid' => 'bail|required',
            'emailList' => 'sometimes|required',
            'lead_uuid' => 'sometimes|required|exists:leads,id',
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
            $results = $action->execute($data);
        }

        return response()->json($results);
    }
}

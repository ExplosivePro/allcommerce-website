<?php

namespace AllCommerce\Http\Controllers\Admin;

use AllCommerce\Abilities;
use AllCommerce\Roles;
use AllCommerce\Shops;
use Illuminate\Http\Request;
use AllCommerce\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class InternalAdminJSONController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->request = $request;
    }

    public function abilities(Abilities $abilities)
    {
        $results = ['success' => false, 'reason' => 'You do not have permission to access this resource'];

        $res = [];
        $data = $this->request->all();
        if(array_key_exists('client_id', $data))
        {
            $records = $abilities->whereClientId($data['client_id'])->get();
        }
        else
        {
            $records = $abilities->all();
        }

        if(count($records) > 0)
        {
            foreach ($records as $ability)
            {
                $res[$ability->id] = $ability->title;
            }

            $results = ['success' => true, 'abilities' => $res];
        }

        return response($results, 200);
    }

    public function role_abilities($role, Roles $roles)
    {
        $results = ['success' => false, 'reason' => 'You do not have permission to access this resource'];

        $data = $this->request->all();

        if(array_key_exists('client_id', $data))
        {
            $assigned = $roles->getAssignedAbilities($role, $data['client_id']);
        }
        else
        {
            $assigned = $roles->getAssignedAbilities($role);
        }


        $results = ['success' => true, 'assigned' => []];
        if(count($assigned) > 0)
        {
            $results['assigned'] = $assigned;
        }

        return response($results, 200);
    }

    public function client_roles($client_id, Roles $roles_model)
    {
        $results = ['success' => false, 'reason' => 'No Roles Available'];

        $roles = $roles_model->getClientAssignedRoles($client_id);

        if($roles && (count($roles) > 0))
        {
            $results = ['success' => true, 'roles' => $roles];
        }

        return response($results, 200);
    }

    public function shop_products_as_select(Shops $shops)
    {
        $results = [];

        $data = $this->request->all();

        if(array_key_exists('shop_uuid', $data))
        {
            if(session()->has('active_client')) {
                $client_id = session()->get('active_client');
                $shop = $shops->whereId($data['shop_uuid'])
                    ->whereClientId($client_id)
                    ->with('inventory')
                    ->first();

                if (!is_null($shop))
                {
                    if(count($shop->inventory) > 0)
                    {
                        foreach ($shop->inventory as $product)
                        {
                            $vo = [];
                            $variant_options = $product->variant_options()
                                ->orderBy('position', 'ASC')->get();

                            foreach($variant_options as $option)
                            {
                                $vo[$option->name] = $option->values;
                            }

                            if($product->active == 1)
                            {
                                $results[] = [
                                    'id' => $product->id,
                                    'name' => $product->title,
                                    'variantOptions' => $vo
                                ];
                            }
                        }
                    }
                }
            }
        }

        return response($results);
    }
}

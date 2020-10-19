<?php

namespace AllCommerce\Http\Controllers\Admin\SMS;

use AllCommerce\Clients;
use Illuminate\Http\Request;
use AllCommerce\Http\Controllers\Controller;

class SMSManagementController extends Controller
{
    protected $request, $clients;

    public function __construct(Request $request, Clients $clients)
    {
        $this->clients = $clients;
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'page' => 'sms-manager',
            //'sidebar_menu' => $this->menu_options()->getOptions('sms-manager')
            /*'components' => [
                'dashboard' => [
                    'layout' => 'default',
                    'args' => []
                ]
            ] */
        ];

        $args['client'] = $this->getClientToUse();
        $args['merchant'] = $this->merchantToUse();

        $args['title'] = (!is_null($args['merchant']))
            ? $args['merchant']->name.' | SMS Manager'
            : $args['client']->name.' | SMS Manager'
        ;

        $blade = 'allcommerce.features.sms.sms-index';

        return view($blade, $args);
    }
}

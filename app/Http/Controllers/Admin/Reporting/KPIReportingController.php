<?php

namespace AnchorCMS\Http\Controllers\Admin\Reporting;

use AnchorCMS\Clients;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class KPIReportingController extends Controller
{
    protected $clients, $request;

    public function __construct(Request $request, Clients $clients)
    {
        parent::__construct();
        $this->clients = $clients;
        $this->request = $request;
    }

    public function index()
    {
        $args = [
            'page' => 'kpi-report',
            //'sidebar_menu' => $this->menu_options()->getOptions('sidebar')
            /*'components' => [
                'dashboard' => [
                    'layout' => 'default',
                    'args' => []
                ]
            ] */
        ];

        $client = $this->clients->find(backpack_user()->client_id);

        if(backpack_user()->isHostUser())
        {
            if(session()->has('active_client'))
            {
                $client = $this->clients->find(session()->get('active_client'));
            }
        }

        $args['client'] = $client;

        $args['roi'] = 'none';

        $data = $this->request->all();
        if(array_key_exists('roi', $data))
        {
            switch($data['roi'])
            {
                case 'all':
                case 'google':
                case 'facebook':
                case 'fb':
                    $args['roi'] = $data['roi'];
                    break;
            }
        }

        $blade = 'anchor-cms.reporting.kpi.index';

        if(backpack_user()->cannot('view-kpi-report', $client))
        {
            $blade = 'errors.401';
        }

        return view($blade, $args);
    }
}

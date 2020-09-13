<?php

namespace AnchorCMS\Http\Controllers\Admin\Reporting;

use AnchorCMS\Clients;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;
use Silber\Bouncer\BouncerFacade as Bouncer;

class MailchimpReportingController extends Controller
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
            'page' => 'mailchimp-report',
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

        $blade = 'anchor-cms.reporting.mailchimp.index';

        if(backpack_user()->cannot('view-mailchimp-integration'))
        {
            $blade = 'errors.401';
        }

        return view($blade, $args);
    }
}

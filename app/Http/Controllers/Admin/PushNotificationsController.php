<?php

namespace AnchorCMS\Http\Controllers\Admin;

use AnchorCMS\Clients;
use AnchorCMS\MobileApps;
use Illuminate\Http\Request;
use AnchorCMS\Http\Controllers\Controller;

class PushNotificationsController extends Controller
{
    protected $apps, $request;

    public function __construct(Request $request, MobileApps $apps)
    {
        parent::__construct();
        $this->request = $request;
        $this->apps = $apps;
    }

    public function index()
    {
        $args = [
            'page' => 'push-notifications',
            'apps' => []
            //'sidebar_menu' => $this->menu_options()->getOptions('sidebar')
        ];

        $args['client'] = (session()->has('active_client'))
            ? session()->get('active_client')
            : backpack_user()->client_id;

        $client = Clients::find($args['client']);

        $args['client'] = $client->id;
        $args['client_name'] = $client->name;
        $args['is_host'] = backpack_user()->isHostUser();

        // pass in all the apps with a push_notes driver assigned and an
        // APP URL in its features for the client or empty
        $args['apps'] = $this->apps->getAllAppWithPushNotes($args['client']);

        $blade = 'anchor-cms.push-notes.index';

        if(backpack_user()->cannot('manage-push-notifications'))
        {
            $blade = 'errors.401';
        }

        return view($blade, $args);
    }
}

<?php

namespace App\Http\Controllers\Dashboards;

use App\Aggregates\Shops\ShopConfigAggregate;
use App\Http\Controllers\Controller;
use App\Models\Shops\Shop;
use Illuminate\Http\Request;

class ShopDashboard extends Controller
{
    protected $request;

    /**
     * Create a new controller instance.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index($shop_id, Shop $model)
    {
        $shop = $model->find($shop_id);
        $merchant = $shop->merchant()->first();
        $client = $shop->client()->first();

        $this->data['title'] = $shop->name.' '.trans('backpack::base.dashboard');

        $this->data['breadcrumbs'] = [
            env('APP_NAME')     => backpack_url('dashboard'),
        ];

        if(backpack_user()->client()->first()->account_owner == backpack_user()->id)
        {
            $this->data['breadcrumbs'][$client->name] = '/access/clients/'.$client->id.'/edit';
        }
        else
        {
            $this->data['breadcrumbs'][$client->name] = false;
        }

        $this->data['breadcrumbs'][$merchant->name] = '/access/merchants/'.$merchant->id.'/edit';
        $this->data['breadcrumbs'][$shop->name] = '/access/shop/'.$shop->id.'/edit';
        $this->data['breadcrumbs'][trans('backpack::base.dashboard')] = false;

        $aggy = ShopConfigAggregate::retrieve($shop_id);
        $checklist = $aggy->getActivationChecklist();
        $this->data['dynamic_widgets'] = [
            'left' => [],
            'right' => [
                'type'         => 'div',
                'class'        => 'row col-md-6 mb-2' ,
                'content'      => [
                    [
                        'type'        => 'alert',
                        'class' => 'alert alert-warning col-sm-12 col-md-12 text-dark' ,
                        'heading'     => 'Welcome to <b>All</b>Commerce, '.backpack_user()->name.'!',
                        'content'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                    ],
                    [
                        'type'        => 'alert',
                        'class' => 'alert alert-warning col-sm-12 col-md-12 text-dark' ,
                        'heading'     => 'Welcome to <b>All</b>Commerce, '.backpack_user()->name.'!',
                        'content'     => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
                    ],
                ],
            ]
        ];

        if($checklist['process_complete']['crossed'])
        {
            // if no sales yet, do this!
            $this->data['dynamic_widgets']['left'] = [
                'type'         => 'div',
                'class'        => 'row col-md-6 mb-2' ,
                'content'      => [
                    [
                        'type'    => 'card',
                        'wrapper' => ['class' => 'col-sm-12 col-md-12'],
                        'class'   => 'card card-light col-sm-12 col-md-12 text-center' ,
                        'content' => [
                            'header' => 'OnBoarding Complete!',
                            'body' => '<img src="https://s3.amazonaws.com/pix.iemoji.com/images/emoji/apple/ios-12/256/party-popper.png" />
                                    <p style="padding-top: 1.5em">Set your ads to send your customers to your checkout funnels and go Make some money!</p>'
                        ],
                    ],
                ],
            ];
            // @todo - else, show a cool chart of dataz
        }
        else
        {
            $shop_type = $aggy->getShopType();
            $header_prefix = ($shop_type == 'Shopify') ? '<i class="fab fa-shopify"></i> Shopify Shop' : '<i class="fad fa-globe"></i> Web Shop';
            $card_color = ($shop_type == 'Shopify') ? 'bg-primary' : '';

            $this->data['dynamic_widgets']['left'] = [
                'type'         => 'div',
                'class'        => 'row col-md-6 mb-2' ,
                'content'      => [
                    [
                        'type'    => 'card',
                        'wrapper' => ['class' => 'col-sm-12 col-md-12'],
                        'class'   => 'card '.$card_color,
                        'content' => [
                            'header' => '<b>'.$header_prefix.' Deployment Checklist</b>',
                            'body' => view('shops.deployment-checklist', ['checklist' => $checklist])->render()
                        ],
                    ],
                ],
            ];

            $count = 0;
            $right_side = [];
            foreach($checklist as $info)
            {
                if(!$info['crossed'])
                {
                    $this_card_color = ($shop_type == 'Shopify') ? 'alert-success' : 'alert-warning ';
                    $right_side[] = [
                        'type'        => 'alert',
                        'class'       => 'alert '.$this_card_color.' col-sm-12 col-md-12 text-dark',
                        'heading'     => $info['widget_heading'],
                        'content'     => $info['widget_content'],
                    ];
                    $count++;
                }

                if($count == 2)
                {
                    break;
                }
            }

            if(count($right_side) > 0)
            {
                $this->data['dynamic_widgets']['right']['content'] = $right_side;
            }
        }

        return view('shops.shop-dashboard', $this->data);
    }
}

<?php

namespace App\Aggregates\Shops;

use App\Aggregates\Clients\ClientAccountAggregate;
use App\Aggregates\Merchants\MerchantAggregate;
use App\Exceptions\Shops\CouldNotAssignGatewayToShop;
use App\Models\Shopify\ShopifyInstalls;
use App\Models\Shops\ShopTypes;
use App\StorableEvents\Shopify\ShopifyInstallCompleted;
use App\StorableEvents\Shops\CreditGatewayAssigned;
use App\StorableEvents\Shops\CreditGatewayLimitReached;
use App\StorableEvents\Shops\CreditGatewayRemoved;
use App\StorableEvents\Shops\InventoryPublished;
use App\StorableEvents\Shops\ProcessComplete;
use App\StorableEvents\Shops\ShopApiTokenCreated;
use App\StorableEvents\Shops\ShopCreated;
use App\StorableEvents\Shops\Shopify\InstallRecordCreated;
use App\StorableEvents\Shops\Shopify\NonceCreated;
use App\StorableEvents\Shops\SMSConfigured;
use App\StorableEvents\Shops\SMSProviderAssigned;
use App\StorableEvents\Shops\SMSProviderLimitReached;
use App\StorableEvents\Shops\SMSProviderRemoved;
use App\StorableEvents\Shops\SMSUnconfigured;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ShopConfigAggregate extends AggregateRoot
{
    protected $shop_id, $shop_name;
    protected $merchant_id, $merchant_name;
    protected $client_name, $client_id;

    protected $shop_url, $shop_type;
    protected $active = false;

    protected $assigned_gateways = [
        'credit' => [],
        'express' => [],
        'install' => [],
    ];
    protected $assigned_sms_provider = [];
    protected $activated_checklist = [
        'gateway_assigned'    => false,
        'sms_configured'      => false,
        'platform_installed'  => false,
        'inventory_published' => false,
        'process_complete'    => false,
    ];

    protected $shopify_config = [
        'shop_install_id' => false,
        'nonce' => false,
        'auth_code' => false,
        'access_token' => false,
        'scopes' => false,
    ];

    protected $shop_inventory = [];

    /* MUTATORS */
    public function applyShopCreated(ShopCreated $event)
    {
        $this->shop_id = $event->getShop()['id'];
        $this->shop_name = $event->getShop()['name'];
        $this->merchant_id = $event->getShop()['merchant_id'];
        $this->client_id = $event->getShop()['client_id'];

        $c_aggy = ClientAccountAggregate::retrieve($this->client_id);
        $this->client_name = $c_aggy->getClientName();

        $m_aggy = MerchantAggregate::retrieve($this->merchant_id);
        $this->merchant_name = $m_aggy->getMerchantName();

        $this->active = $event->getShop()['active'] == 1;
        $this->shop_url = $event->getShop()['shop_url'];
        $this->shop_type = $event->getShop()['shop_type'];
    }

    public function applyCreditGatewayAssigned(CreditGatewayAssigned $event)
    {
        $this->assigned_gateways['credit'][$event->getProvider()] = [
            'name' => $event->getName(),
            'shop_assigned_payment_providers_id' => $event->getAssigned()
        ];
        $this->activated_checklist['gateway_assigned'] = true;
    }

    public function applySMSProviderAssigned(SMSProviderAssigned $event)
    {
        $this->assigned_sms_provider[$event->getProvider()] = [
            'name' => $event->getName(),
            'shop_assigned_sms_providers_id' => $event->getAssigned()
        ];

        $this->activated_checklist['gateway_assigned'] = true;
    }

    public function applyCreditGatewayRemoved(CreditGatewayRemoved $event)
    {
        $this->assigned_gateways['credit'] = [];
        $this->activated_checklist['gateway_assigned'] = false;
    }

    public function applySMSProviderRemoved(SMSProviderRemoved $event)
    {
        $this->assigned_sms_provider = [];
    }

    public function applySMSConfigured(SMSConfigured $event)
    {
        $this->activated_checklist['sms_configured'] = true;
    }

    public function applySMSUnconfigured(SMSUnconfigured $event)
    {
        $this->activated_checklist['sms_configured'] = false;
    }

    public function applyNonceCreated(NonceCreated $event)
    {
        $this->shopify_config['nonce'] = $event->getNonce();
    }

    public function applyInstallRecordCreated(InstallRecordCreated $event)
    {
        $this->shopify_config['shop_install_id'] = $event->getInstall()['id'];
    }

    public function applyShopifyInstallCompleted(ShopifyInstallCompleted $event)
    {
        $this->shopify_config['auth_code'] = $event->getInstall()['auth_code'];
        $this->shopify_config['access_token'] = $event->getInstall()['access_token'];
        $this->shopify_config['scopes'] = $event->getInstall()['scopes'];

        $this->activated_checklist['platform_installed'] = true;
    }

    public function applyInventoryPublished(InventoryPublished $event)
    {
        $this->activated_checklist['inventory_published'] = true;
    }

    public function applyProcessComplete(ProcessComplete $event)
    {
        $this->activated_checklist['process_complete'] = true;
    }

    /* ACTIONS */
    public function createShop(array $shop_array)
    {
        $this->recordThat(new ShopCreated($shop_array));

        return $this;
    }

    public function setNewShopApiToken(string $shop_id, string $client_id)
    {
        $this->recordThat(new ShopApiTokenCreated($shop_id, $client_id));

        return $this;
    }

    public function createOrUpdateAssignedGateway(array $provider, $type, $assigned_id)
    {
        switch($type)
        {
            case 'credit':
                return $this->createOrUpdateAssignedCreditGateway($provider, $type, $assigned_id);
                break;

            case 'express':
                return $this->createOrUpdateAssignedExpressGateway($provider, $type, $assigned_id);
                break;

            case 'install':
                return $this->createOrUpdateAssignedInstallGateway($provider, $type, $assigned_id);
                break;
        }
        return $this;
    }

    private function createOrUpdateAssignedCreditGateway(array $provider, $type, $assigned_id)
    {
        if($this->hasCreditGatewayAssigned())
        {
            if(array_key_exists($provider['id'],$this->assigned_gateways['credit']))
            {
                return $this;
            }

            $this->recordThat(new CreditGatewayLimitReached());
            $this->persist();

            throw CouldNotAssignGatewayToShop::shopGatewayLimitReached($provider['name']);
        }

        $this->recordThat(new CreditGatewayAssigned($assigned_id, $provider['id'], $provider['name']));

        return $this;
    }

    private function createOrUpdateAssignedExpressGateway(array $provider, $type, $assigned_id)
    {
        return $this;
    }

    private function createOrUpdateAssignedInstallGateway(array $provider, $type, $assigned_id)
    {
        return $this;
    }

    public function removeAssignedGateway(array $provider, $type, $assigned_id)
    {
        $this->recordThat(new CreditGatewayRemoved($assigned_id, $provider['id'], $provider['name']));
        return $this;
    }

    public function createOrUpdateAssignedSMSProvider(array $provider, $assigned_id)
    {
        if($this->hasSMSProviderAssigned())
        {
            if(array_key_exists($provider['id'], $this->assigned_sms_provider))
            {
                return $this;
            }

            $this->recordThat(new SMSProviderLimitReached());
            $this->persist();

            throw CouldNotAssignGatewayToShop::shopSmsLimitReached($provider['name']);
        }

        $this->recordThat(new SMSProviderAssigned($assigned_id, $provider['id'], $provider['name']));

        return $this;
    }

    public function removeAssignedSMSProvider(array $provider, $assigned_id)
    {
        $this->recordThat(new SMSProviderRemoved($assigned_id, $provider['id'], $provider['name']));
        return $this;
    }

    public function setSMSConfigured()
    {
        $this->recordThat(new SMSConfigured());
        return $this;
    }

    public function unsetSMSConfigured()
    {
        $this->recordThat(new SMSUnconfigured());
        return $this;
    }

    public function installShopifyOnShop(string $nonce)
    {
        $this->recordThat(new NonceCreated($this->shop_id, $nonce));

        return $this;
    }

    public function continueShopifyInstall(ShopifyInstalls $install)
    {
        $this->recordThat(new InstallRecordCreated($install->toArray()));
        return $this;
    }

    public function completeShopifyInstall(ShopifyInstalls $install)
    {
        $this->recordThat(new ShopifyInstallCompleted($install->toArray()));
        return $this;
    }

    public function setInventoryPublished()
    {
        $this->recordThat(new InventoryPublished($this->shop_id));
        return $this;
    }

    public function setChecklistComplete()
    {
        $this->recordThat(new ProcessComplete());
        return $this;
    }

    /* GETTERS */
    public function getShopName()
    {
        return $this->shop_name;
    }

    public function getShopUrl()
    {
        return $this->shop_url;
    }

    public function getMerchantName()
    {
        return $this->merchant_name;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getClientName()
    {
        return $this->client_name;
    }

    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    public function getShopType()
    {
        $results = false;

        if(!is_null($this->shop_type))
        {
            $model = ShopTypes::find($this->shop_type);
            $results = $model->name;
        }

        return $results;
    }

    public function getActivationChecklist()
    {
        $results = $this->activated_checklist;
        $type = $this->getShopType();
        $button_color = ($type == 'Shopify') ? 'btn-warning' : 'btn-primary';
        foreach($results as $col => $val)
        {
            switch($col)
            {
                case 'gateway_assigned':
                    $results[$col] = [
                        'instruction' => 'Assign a Payment Gateway to your shop.',
                        'widget_heading' => 'Assign a Credit Card Gateway!',
                        'widget_content' => 'There\'s a wide array of providers to choose from and more being added regularly! You simply assign a credit card provider to start selling, and when you\'re ready to add alternate methods like PayPal and Sezzle, you can add those as well!
                            <br /><br />
                            <button type="button" class="btn '.$button_color.'" onclick="window.location.href= \'/access/payment-gateways\'"><i class="las la-credit-card"></i> Assign a Gateway</button>
                        ',
                        'crossed' => $val
                    ];
                    break;
                case 'sms_configured':
                    $results[$col] = [
                        'instruction' => 'Configure or disable an SMS Provider Text Messaging Features!',
                        'widget_heading' => 'Configure Your Preferred SMS Provider!',
                        'widget_content' => 'Set yourself up with an SMS Provider to power your 1-Click Checkouts or have you developer(s) set up a notification system effortlessly, or shut it off completely! You can choose from several providers or use AllCommerce\'s built-in always enabled integration powered by Twilio!
                            <br /><small><i>Whether you use the SMS feature, you will need to enable or disabled the feature before you can start selling.</i></small>
                            <br /><br />
                            <button type="button" class="btn '.$button_color.'" onclick="window.location.href= \'/access/sms-providers\'"><i class="las la-mobile-alt"></i> Manage SMS</button>
                        ',
                        'crossed' => $val
                    ];
                    break;

                case 'platform_installed':
                    switch($type)
                    {
                        case 'Shopify':
                            $s = $this->shop_id;
                            $now = date('Y-m-d H:i:s', strtotime('now'));
                            $comment = 'Link your Allcommerce Account with your Shopify Account';
                            $header = 'Link Your Shopify Account!';
                            $content = 'Connect your Shopify Shop to AllCommerce now to start making click funnels!
                                <br /><br />
                                <a class="btn btn-warning" href="/?hmac='.$s.'&shop='.$s.'&timestamp='.$now.'" target="_blank"><i class="fab fa-shopify"></i> Link to Shopify</a>
                            ';
                            break;

                        case 'Web Only':
                            $comment = 'Enable the Checkout Plugin and Reveal Your API Key!';
                            $header = 'Enable the Checkout Plugin!';
                            $content = 'When you enable the checkout the plugin, you will be revealed a key that you will paste into the tracker script that allowed AllCommerce to communicate with your shop and present the user with a checkout!
                                <br /><br />
                                <button type="button" class="btn btn-primary" onclick="window.location.href= \'/access/checkout-plugin\'"><i class="las la-mobile-alt"></i> Manage SMS</button>
                            ';
                            break;

                        default:
                            $comment = 'Assign a Shop Type';
                            $header = 'Ummm...';
                            $content = 'We cannot identify the type for this shop. Remove this shop and start over or contact support
                                <br /><br />
                                <a class="btn btn-dark" href="mailto:developers@capeandbay.com"><i class="las la-pager"></i> Contact Support</a>
                            ';
                    }
                    $results[$col] = [
                        'instruction' => $comment,
                        'widget_heading' => $header,
                        'widget_content' => $content,
                        'crossed' => $val
                    ];
                    break;

                case 'inventory_published':
                    $results[$col] = [
                        'instruction'    => 'Import & Publish Items to Be Used to Deliver Your Checkout',
                        'widget_heading' => 'Let AllCommerce Sell Your Stuff!',
                        'widget_content' => ($type == 'Shopify')
                            ? 'Once you\'ve linked your Shopify Shop, AllCommerce will automatically track your product catalog and prompt you to publish any new items to AllCommerce. 
                               Once they are uploaded into AllCommerce, you can allow them to be used with your checkout funnels! It\'s that easy. 
                                <br /><br />
                                <button type="button" class="btn '.$button_color.'" onclick="window.location.href= \'/access/product-catalog/'.$this->shop_id.'/shopify/import\'"><i class="fad fa-person-dolly"></i> Publish My Stuff!</button>'
                            : 'Manually Import your products to start using them with your shop! Alternatively, you can upload a CSV of product to save some time.
                                 <br /><br />
                                <button type="button" class="btn '.$button_color.'" onclick="window.location.href= \'/access/product-catalog\'"><i class="fad fa-person-dolly"></i> Publish My Stuff!</button>
                            ',
                        'crossed' => $val
                    ];
                    break;

                case 'process_complete':
                    $url = '/access/checkout-funnels';
                    $btn_text = ($type == 'Shopify') ? 'Visit My Funnel' : 'Manage My Funnels!';
                    $results[$col] = [
                        'instruction' => 'Activate Your First Checkout Funnel!',
                        'widget_heading' => 'You Are Almost Done!',
                        'widget_content' => 'Go visit your checkout funnel! If there is something missing, come back here and run point!
                            Is everything is good to go, your shop is now ready for customers! 
                            <br /><br />
                            <button type="button" class="btn '.$button_color.'" onclick="window.location.href= \''.$url.'\'"><i class="fad fa-person-dolly"></i> '.$btn_text.'</button>
                        ',
                        'crossed' => $val
                    ];
            }
        }

        return $results;
    }

    public function hasCreditGatewayAssigned()
    {
        return (count($this->assigned_gateways['credit']) > 0);
    }

    public function hasSMSProviderAssigned()
    {
        return (count($this->assigned_sms_provider) > 0);
    }

    public function getShopInstallRecord()
    {
        $results = false;

        if($this->shopify_config['shop_install_id'])
        {
            $results = $this->shopify_config;
        }

        return $results;
    }

    public function getShopInventory()
    {
        return $this->shop_inventory;
    }

}

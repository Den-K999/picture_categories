<?php
namespace Controller;

use DreamCommerce\ShopAppstoreLib\Resource\Metafield as ShopMetafield;
use DreamCommerce\ShopAppstoreLib\Resource\MetafieldValue;

class Metafield extends ControllerAbstract
{

    /**
     * @var array configuration placeholder
     */
    protected $client = array();
    protected $shop = '';

    public function __construct($client)
    {
        $this->client = $client;
        $this->shop = $_GET['shop'];
    }

    public function checkAvailability()
    {
        $metafieldTreeResource = new ShopMetafield($this->client);
        $metafields = $metafieldTreeResource->limit(50)->get();

        $isMetaShop = false;
        foreach ($metafields as $metafield){
            if($metafield['key'] == 'shop' && $metafield['namespace'] == 'pirkspark'){
                $isMetaShop = true;
            }
        }

        if(!$isMetaShop){
            $this->createMetafield();
        }
    }

    public function createMetafield()
    {
        $metaFieldResource = new ShopMetafield($this->client);
        $object = "system";
        $data = array(
            'namespace' => 'pirkspark',
            'key' => 'shop',
            'type' => ShopMetafield::TYPE_STRING,
            'description'=>'shop_id'
        );

        $metafieldId = $metaFieldResource->post($object, $data);

        if ($metafieldId) {
            $metafieldValueResource = new MetafieldValue($this->client);
            $data = array(
                'metafield_id' => $metafieldId,
                'object_id' => '0',
                'value' => $this->shop
            );

            $result = $metafieldValueResource->post($data);
        }

    }
}
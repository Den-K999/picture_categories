<?php

namespace Controller;

use DreamCommerce\ShopAppstoreLib\Resource\Category;
use DreamCommerce\ShopAppstoreLib\Resource\CategoriesTree;

class Index extends ControllerAbstract
{
    public $tree = '';
    public $categoriesArray = array();

    public function indexAction()
    {
        $config = require './src/bootstrap.php';

        $client = $this->app->getClient();
        $this->getCategories($client);

        $metafield = new Metafield($client);

        $metafield->checkAvailability();

        $db = new Db($config);
        $db->checkAvailability();
        $this['setting'] = $db->getSetting();
    }

    private function getCategories($client)
    {
        $categoriesTreeResource = new CategoriesTree($client);
        $categoriesTree = $categoriesTreeResource->get();

        $categoriesResource = new Category($client);
        $categories = $categoriesResource->limit(50)->get();

        if ($categories->pages > 1) {
            for ($i = 1; $i <= $categories->pages; $i++) {
                $categories = $categoriesResource->page($i)->get();
                foreach ($categories as $key => $category) {
                    $this->categoriesArray[$category["category_id"]] = $category["translations"][$_GET['locale']]["name"];
                }
            }
        } else {
            foreach ($categories as $key => $category) {
                $this->categoriesArray[$category["category_id"]] = $category["translations"][$_GET['locale']]["name"];
            }
        }

        foreach ($categoriesTree as $key => $value) {
            $this->traverseArray($value, $this->tree, 1);
        }

        $this['category_tree'] = $this->tree;
    }

    private function traverseArray($value, &$string, $index)
    {
        $string = $string . '<div class="category level_' . ($index) . '" data-element-id=' . $value['id'] . ' data-element-name="' . $this->categoriesArray[$value['id']] . '" data-element-shop=' . $_GET['shop'] . '><span class="cat-name">' . $this->categoriesArray[$value['id']] . '<img class="img-set" src="assets/images/imgset.svg"><button onclick="showCategoryInfo(' . $value['id'] . ',\'' . $this->categoriesArray[$value['id']] . '\',\'' . $_GET['shop'] . '\')" class="toggle-edition"><img src="assets/images/edit-solid.svg"></button></span>';
        if (!empty($value['children'])) {
            $string = $string . '<div class="subcategories level_' . ($index) . '">';
            foreach ($value['children'] as $children) {
                $this->traverseArray($children, $string, $index + 1);
            }
            $string = $string . '</div>';
        }
        $string = $string . '</div>';
    }
}

// openModal(' . $value['id'] . ')
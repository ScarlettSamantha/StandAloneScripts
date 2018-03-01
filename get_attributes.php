<?php

# Include Magento autoloader file
include_once 'app/Mage.php';

# Initialize Magento on our script
Mage::app();

$products = Mage::getModel('catalog/product')->getCollection()
    ->addAttributeToSelect("*")
    ->load();

foreach($products as $product){
    $attributes = $product->getAttributes();
    foreach ($attributes as $attribute) {
        echo($attribute);
    }
}
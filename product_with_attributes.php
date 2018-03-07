<?php
ini_set('max_execution_time', 0);
ini_set('memory_limit', '4086M');

include_once 'app/Mage.php';
Mage::app();

#$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
$products = [];

foreach (Mage::app()->getStores() as $_eachStoreId => &$val)
{
	foreach (Mage::getModel('catalog/category')->setStoreId($_eachStoreId)->getCollection()->addAttributeToSelect('*') as &$category) {
		$p = Mage::getModel('catalog/product')
			->getCollection()
			->setPageSize(10000000)
			->addAttributeToSelect('*')
			->addCategoryFilter($category)
			->load();
		foreach ($p as $k => &$v) {
			$products[$k] = $v;
		}
	}
}

function load_attribute_set(&$cache, $id)
{
	$attributes = Mage::getResourceModel('catalog/product_attribute_collection')
		->setAttributeSetFilter($id)
		->getItems();
	$cache[$id] = [];
	foreach($attributes as $k => &$v)
	{
		$cache[$id][$k] = $v;
	}
}

$attributeSetCache = [];
$data = [];
$c = count($products);
$i = 0;
foreach($products as &$product)
{
	$i++;
	foreach($data[0] as $k => &$v){
		$data[$i][$v] = null;
	}
	$pi = 0;
	$attributeSetId = $product->getAttributeSetId();

	if( ! array_key_exists($attributeSetId, $attributeSetCache))
		load_attribute_set($attributeSetCache, $attributeSetId);

	$attributes = $product->getAttributes();

	if( ! empty($attributeSetCache[$attributeSetId]))
		$attributes = array_merge($attributes, $attributeSetCache[$attributeSetId]);

	$pc = count($attributes);
	foreach($attributes as &$attribute){
		$pi++;
		if ($i !== 1 || $pi !== 1)
			echo "\r";

		echo "Processing product " . $i . '/' . $c . ' - Attribute: ' . $pi . '/' . $pc;
		if( ! in_array($attribute->getAttributeCode(), $data[0]))
		{
			$data[0][] = $attribute->getAttributeCode();
			$data[$i][$attribute->getAttributeCode()] = null;
		}
		$data[$i][$attribute->getAttributeCode()] = $attribute->getFrontend()->getValue($product);
	}
}

$fp = realpath(__DIR__) . '/product_attribute_dump.csv';
echo PHP_EOL . "Dumping to " . $fp . PHP_EOL;
$fh = fopen($fp, 'w+');
ftruncate($fh, 0);
foreach ($data as $row) {
	fputcsv($fh, $row);
}
fflush($fh);
fclose($fh);



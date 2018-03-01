<?php
include_once 'app/Mage.php';
Mage::app();

$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');

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
	$pc = count($product->getAttributes());
	foreach($product->getAttributes() as &$attribute){
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



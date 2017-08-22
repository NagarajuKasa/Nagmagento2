<?php
use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$obj = $bootstrap->getObjectManager();
$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

echo "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever sin";

echo "<pre>";
//customer session
$quote = $obj->get('Magento\Checkout\Model\Session')->getQuote()->load(2);
print_r($quote->getOrigData());

//product Information 
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

$product = $objectManager->get('Magento\Catalog\Model\Product')->load(71);
echo "Product Name ==".$product->getName()."<br>";

//Load attribute value using attribute code
echo "Product Price Using Attribute ==".$product->getResource()->getAttribute('price')->getFrontend()->getValue($product);
echo "<br>";

//Load Product  Custom Options
$customOptions = $objectManager->get('Magento\Catalog\Model\Product\Option')->getProductOptionCollection($product);
//print_r($customOptions);
foreach ($customOptions as $key => $value) 
{
	echo $key."====".$value->getTitle()."<br>"; 
	
}


//Order Information
$OrderNumber = "000000001";
$order = $objectManager->get('Magento\Sales\Model\Order')->load($OrderNumber, 'increment_id');
foreach ($order->getAllItems() as $item) 
{
            echo  "Product Id ==".$item->getProductId()."<br>";
            echo  "product  Sku==".$item->getSku()."<br>";;
            echo  "product is ==".$item->getName()."<br>";; 
            
}
echo "<br>";


//web site Id
$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
echo "WEB SITE ID ==". $websiteId = $storeManager->getWebsite()->getWebsiteId()."<br>";


//Currency Symbol
$currencyCode = $storeManager->getStore()->getCurrentCurrencyCode();
$_Symbol = $objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($currencyCode);
echo "Currency Symbol ==".$_Symbol->getCurrencySymbol()."<br>";;


//Customer Data 
$customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory'); 
$customer=$customerFactory->create();
$customer->setWebsiteId($websiteId);
//$customer->loadByEmail('example@gmail.com');// load customer by email address
//echo $customer->getEntityId();
$customer->load('1');// load customer by using ID
$data= $customer->getData();
echo "Customer First Name ==".$data['firstname'];
//print_r($data);
echo "<br>";

//Customer Data 2 way
$customerData = $objectManager->create('Magento\Customer\Model\Customer')->load(1);
echo "Customer Name is ==".$customerData->getName().'<br>';



//To get media base URL:

echo $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
echo "<br>";


//To get link base url:
echo $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
echo "<br>";


//Quote Details from Sales Flat Quote
$quoteId='1';
$quote =$objectManager->get('Magento\Quote\Model\Quote')->load($quoteId);
echo "Sub Total".$quote->getSubtotal()."<br>";
echo "Base Sub Total".$quote->getBaseSubtotal()."<br>";
echo "Grand Total".$quote->getGrandTotal()."<br>";
//print_r($Quote);

?>




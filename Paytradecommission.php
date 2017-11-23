<?php
namespace StackExchange\Paytrade\Controller\Index;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Session;
class Paytradecommission extends \Magento\Framework\App\Action\Action
{

	protected $formKey;
  protected $request;
	protected $cart;
	protected $product;
	protected $_session;
	protected $_helper;
	
  public function __construct(
	Context $context,
	\Magento\Framework\Data\Form\FormKey $formKey,
	Cart $cart,
	\Magento\Catalog\Model\Product $product,
	\Magento\Customer\Model\Session $session,
	\Stackexchange\Paytrade\Helper\Data $helper,
	array $data = []) {
		$this->formKey = $formKey;
		$this->cart = $cart;
		$this->product = $product;
		$this->_session = $session;
		$this->_helper = $helper;
        parent::__construct($context);
    }
	
     
	 public function execute()
	 {    
	    try{
			  
			if($this->_session->isLoggedIn())
		    {
				//If Trade User Logged in
				$tradeProductId  =  $this->_helper->getTradeProductId(); // Get the trade product id
				$tradeCommissionAmount = 0;
				$selectedOrdersList=$this->getRequest()->getParam('orderinc');
				$paytradeSelectedOrdersList = array();
				
				foreach($selectedOrdersList as $orderKey=>$orderCommisionPrice)
				{
					$tradeAccountForm = explode('||', $orderCommisionPrice);
					$paytradeSelectedOrdersList[$orderKey]=$tradeAccountForm[0]; //for trade orders list
					$tradeCommissionAmount += $tradeAccountForm[1]; // for trade commission amount 
				
				}
				
				$this->_session->setTradeCommissionOrdersList($paytradeSelectedOrdersList);// set the trade Orders List
			    $this->_session->setTradeCommissionAmmount($tradeCommissionAmount);// set the Trade commission Amount
			   			   
			    $params = array(
                'form_key' => $this->formKey->getFormKey(),
                'product' =>$tradeProductId, //product Id
                'qty'   =>1 //quantity of product                
                );				
			
				  $_product = $this->product->load($tradeProductId);
				  $this->cart->truncate();
				  $this->cart->addProduct($_product, $params);
				  $this->cart->save();
				  $this->messageManager->addSuccess(__('Item has been successfully added to cart.'));			  
				  $this->_redirect('checkout/cart');
		  
		  }else
		  {
			  //If Trade User Not Logged in
			  $this->_redirect('customer/account/login');
              $this->messageManager->addError(__('Your session has expired Please login and try again.'));
			  
		  }
		}catch(\Exception $e) 
	    {
			//If Trade User Logged in Expired			
			$this->_redirect('paytrade/index/index');
			$this->messageManager->addError(__('Please select at least one order to proceed payment'));
			
		   
        }catch(\Exception $e) 
		{
            $this->messageManager->addException($e, __('error.'));
			
        }
			
	 }

}

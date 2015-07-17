<?php

if (!defined('_PS_VERSION_'))
  exit;

class EquiShopping extends Module {
	
	private $_html = '';
	private $_seller_id;
	
	public function __construct()
	{
		$this->name = 'equishopping';
		$this->tab = 'advertising_marketing';
		$this->version = 1.0;
		$this->author = 'Guillaume NATALI';
		$this->need_instance = 0;

		$this->_seller_id = Configuration::get('EQUISHOPPING_SELLERID');
		
		parent::__construct();

		$this->displayName = $this->l('EquiShopping');
		$this->description = $this->l('Tracking des commandes pour EquiShopping.');
	}

	public function install()
	{
		if ( !parent::install() OR !$this->registerHook('orderConfirmation') )
			return false;
		return true;
	}
	
	public function uninstall()
	{
		if (!Configuration::deleteByName('EQUISHOPPING_SELLERID') OR !parent::uninstall())
			return false;
		return true;
	}
	
	private function _postProcess()
	{
		if (Tools::isSubmit('btnSubmit'))
		{
			Configuration::updateValue('EQUISHOPPING_SELLERID', Tools::getValue('seller_id'));
		}
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('OK').'" /> '.$this->l('Settings updated').'</div>';
	}
	
	private function _displayForm()
	{
		$this->_html .=
		'<form action="'.Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
				<table border="0" width="500" cellpadding="0" cellspacing="0" id="form">
					<tr><td colspan="2">'.$this->l('Saisir votre identifiant').'.<br /><br /></td></tr>
					<tr><td width="130" style="height: 35px;">'.$this->l('Seller id').'</td><td><input type="text" name="seller_id" value="'.Tools::htmlentitiesUTF8(Tools::getValue('seller_id', $this->_seller_id)).'" style="width: 300px;" /></td></tr>
					<tr><td colspan="2" align="center"><br /><input class="button" name="btnSubmit" value="'.$this->l('Update settings').'" type="submit" /></td></tr>
				</table>
			</fieldset>
		</form>';
	}
	
	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';

		if (Tools::isSubmit('btnSubmit'))
		{
			$this->_postProcess();
		}
		else
			$this->_html .= '<br />';

		$this->_displayForm();

		return $this->_html;
	}
	
	public function hookOrderConfirmation($param)
	{
		if (!$this->active)
			return ;
		
		if (!$this->_seller_id)
			return ;
		
		global $smarty;
		$order = $param['objOrder'];
		$customer = new Customer($order->id_customer);
		$address = new Address($order->id_address_invoice);
		
		$smarty->assign(array(
			'seller_id' => $this->_seller_id,
			'order_id' => $order->id,
			'total_exc_vat' => $order->getTotalProductsWithoutTaxes(),
			'total_inc_vat' => $order->getTotalProductsWithTaxes(),
			'vat' => $order->getTotalProductsWithTaxes()-
					$order->getTotalProductsWithoutTaxes(),
			'shipping' => $order->total_shipping,
			'currency' => $param['currencyObj']->iso_code,
			'cli_id' => $order->id_customer,
			'cli_email' => $customer->email,
			'cli_firstname' => $customer->firstname,
			'cli_lastname' => $customer->lastname,
			'cli_city' => $address->city,
			'cli_country' => $adress->country,
			'payment_method' => $order->payment
		));
		
		$products = $order->getProducts();
		
		$smarty->assign('products',$products);
			
		return $this->display(__FILE__, 'equishopping.tpl');
	}
}
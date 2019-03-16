<?php

class ControllerExtensionModuleOffertheday extends Controller {
	public function index() {
		$this->load->model('tool/image');
		$this->load->model('setting/setting');
		$this->load->model('extension/module/offertheday');
		 	
		$this->document->addStyle('catalog/view/theme/default/stylesheet/offertheday.css');
        
        $result = $this->model_setting_setting->getSetting('offertheday');
        
		if (is_file(DIR_IMAGE . $result['offertheday_back_image'])) {
			$image = $result['offertheday_back_image'];
			$thumb = $result['offertheday_back_image'];
		} else {
			$image = $result['offertheday_back_image_default'];
			$thumb = $result['offertheday_back_image_default'];
		}
		$data['image'] = 'image/' . $image;
		
		if($this->request->server['REQUEST_METHOD'] == 'GET'){
			return $this->load->view('extension/module/offertheday', $data);
		} elseif ($this->request->server['REQUEST_METHOD'] == 'POST'){
			$this->getOffer();
		}
	}
	public function getOffer() {
		$product_specials = $this->model_extension_module_offertheday->getProductsOffer();
		if(count($product_specials) > 0){
			foreach ($product_specials as $product_special) {
				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price     = $this->currency->format($this->tax->calculate($product_special['price'], $product_special['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$old_price = $this->currency->format($this->tax->calculate($product_special['old_price'], $product_special['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price     = '';
					$old_price = '';
				}

				$data = array(
					'customer_group_id' => $product_special['customer_group_id'],
					'product_name'      => $product_special['name'],
					'product_id'        => $product_special['product_id'],
					'priority'          => $product_special['priority'],
					'new_price'         => $price,
					'old_price'         => $old_price,
					'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
					'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] :  '' ,
					'href'              => $this->url->link('product/product', 'product_id=' . $product_special['product_id'])
				);			
			}
		} else {
			$data = false;
		} 
		$this->response->setOutput(json_encode($data));
		
	}
}

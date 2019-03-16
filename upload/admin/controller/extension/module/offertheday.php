<?php

class ControllerExtensionModuleOffertheday extends Controller {
	public function index() {
		$this->load->language('extension/module/offertheday');
		$this->load->model('extension/module/offertheday');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		$data['token'] = $this->session->data['token'];
		 
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);
		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}
		if (isset($this->request->get['error']) && ($this->request->get['error'] == 'fill')){
			$data['error_warning']  = $this->language->get('error_fill'); 
		} else {
			$data['error_warning']  = '';
		}
		$data['heading_title']  = $this->language->get('heading_title');
	    $data['setting']        = $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'] . '&type=setting', true);
	    $data['text_edit']      = $this->language->get('text_edit');
		$data['button_cancel']  = $this->language->get('button_cancel');
		$data['button_save']    = $this->language->get('button_save');
	    $data['button_setting'] = $this->language->get('button_setting');
	    $data['header']         = $this->load->controller('common/header');
		$data['column_left']    = $this->load->controller('common/column_left');
		$data['footer']         = $this->load->controller('common/footer');
	    
		if(($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['product_offer_delete'])){
			$this->delOffer($this->request->post['product_offer_delete']);
		} elseif (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['product_offer_setting'])){
			$this->editSetting($this->request->post['product_offer_setting']);
		} elseif (($this->request->server['REQUEST_METHOD'] == 'POST') && (isset($this->request->post['product_offer']) || isset($this->request->post['product_special']))){
			$this->addOffer();
		} elseif (($this->request->server['REQUEST_METHOD'] == 'GET') && isset($this->request->get['type']) && ($this->request->get['type'] == 'autoload')){
			$this->autoload();
		} elseif (($this->request->server['REQUEST_METHOD'] == 'GET') && isset($this->request->get['type']) && ($this->request->get['type'] == 'setting')){
			$this->getSettings($data);
		} else{
			$this->getForm($data);
		}
		
	} /* index */
	
	public function editSetting($data_settings){
		if(isset($data_settings) && !empty($data_settings)){
			$this->load->model('setting/setting');
			foreach($data_settings as $key => $value){
				$this->model_setting_setting->editSettingValue('offertheday', $key, $value);
			}
		}
		$this->response->redirect($this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'] . '&type=setting', true));
	}
	
	public function getSettings($data) {
		$this->load->model('tool/image');
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension_setting'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=setting', true)
		);
		$data['action']        = $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'] . '&type=setting', true);
		$data['cancel']        = $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'], true);
		$data['text_enabled']  = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['entry_status']  = $this->language->get('entry_status');
	
		$result = $this->model_setting_setting->getSetting('offertheday');
        
        $data['status'] = $result['offertheday_status'];
			
		if (is_file(DIR_IMAGE . $result['offertheday_back_image'])) {
			$image = $result['offertheday_back_image'];
			$thumb = $result['offertheday_back_image'];
		} else {
			$image = $result['offertheday_back_image_default'];
			$thumb = $result['offertheday_back_image_default'];
		}
		$data['image'] = array(
			'link'       => $image,
			'image'      => $image,
			'thumb'      => $this->model_tool_image->resize($thumb, 100, 100)
		);
		
		$this->response->setOutput($this->load->view('extension/module/offertheday_setting', $data));
	}
	
	public function getForm($data) {
		$this->load->model('customer/customer_group');
        $this->load->model('extension/module/offertheday');
		
		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}
		
		$data['cancel']               = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		$data['entry_product']        = $this->language->get('entry_product');		
		$data['help_product']         = $this->language->get('help_product');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_date_start']     = $this->language->get('entry_date_start');
		$data['entry_date_end']       = $this->language->get('entry_date_end');
		$data['entry_price']          = $this->language->get('entry_price');
		$data['entry_priority']       = $this->language->get('entry_priority');
		$data['entry_product_id']     = $this->language->get('product_id');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		
		$product_specials = $this->model_extension_module_offertheday->getProductsOffer();
		
		$data['product_specials'] = array();

		foreach ($product_specials as $product_special) {
			$data['product_specials'][] = array(
				'customer_group_id' => $product_special['customer_group_id'],
				'product_name'      => $product_special['name'],
				'product_id'        => $product_special['product_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] :  ''
			);
		}
		
		$this->response->setOutput($this->load->view('extension/module/offertheday', $data));
    }
   
	public function install() {
        $this->load->model('extension/module/offertheday');
        $this->load->model('setting/setting');
        
        $this->model_extension_module_offertheday->install();
        $settings = array( 
			'offertheday_status'             => 0, 
			'offertheday_back_image'         => 'catalog/offertheday/default.jpg',
			'offertheday_back_image_default' => 'catalog/offertheday/default.jpg'
			);
		$this->model_setting_setting->editSetting('offertheday', $settings);

    }

    public function uninstall() {
        $this->load->model('setting/setting');
        $this->load->model('extension/module/offertheday');
        
		$this->model_setting_setting->deleteSetting('offertheday');
        $this->model_extension_module_offertheday->uninstall();
    }
    
    public function addOffer() {
		
		if(isset($this->request->post['product_special']) && isset($this->request->post['product_offer'])){
			$product_special = $this->request->post['product_special'];
			unset($this->request->post['product_special']);
			$product_offer_temp = $this->request->post['product_offer'];
			unset($this->request->post['product_offer']);
			$products_offer = $product_offer_temp + $product_special;
			
			foreach($product_special as $product_del){
				$this->delOffer($product_del['product_id']);
			}
			
		} elseif(isset($this->request->post['product_special']) && !isset($this->request->post['product_offer'])){
			$products_offer = $this->request->post['product_special'];
			unset($this->request->post['product_special']);
			foreach($products_offer as $product_del){
				$this->delOffer($product_del['product_id']);
			}
			
		} elseif(!isset($this->request->post['product_special']) && isset($this->request->post['product_offer'])){
			$products_offer = $this->request->post['product_offer'];
			unset($this->request->post['product_offer']);
		}
			
		
		if(isset($products_offer)){

			foreach($products_offer as &$product_offer){

				if(!empty($product_offer['product_id']) && !empty($product_offer['customer_group_id']) && !empty($product_offer['price']) && !empty($product_offer['date_end'])){
					if(empty($product_offer['priority'])){
						$product_offer['priority'] = 0;
					}
					if(empty($product_offer['date_start'])){
						$product_offer['date_start'] = date('Y-m-d');	
					}	
					$a[] = $this->model_extension_module_offertheday->addOffer($product_offer);
				} else {
					$error_values = 1;
				}
			} /* end foreach */
		} 
		if(isset($error_values)){
			$this->response->redirect($this->url->link('extension/module/offertheday&error=fill', 'token=' . $this->session->data['token'], true));
		} else {
			$this->response->redirect($this->url->link('extension/module/offertheday', 'token=' . $this->session->data['token'], true));
		}
    }
    
    public function delOffer($product_offer) {
		$this->model_extension_module_offertheday->delOffer($product_offer);
    }
    
    public function autoload(){
		$json = array();

		if (isset($this->request->get['filter_name']) && isset($this->request->get['type']) && ($this->request->get['type'] == 'autoload')) {

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$filter_data = array(
				'filter_name'   => $filter_name,
				'filter_status' => 1,
				'start'         => 0,
				'limit'         => 5
			);

			$this->load->model('extension/module/offertheday');
			$results =  $this->model_extension_module_offertheday->getProducts($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
}

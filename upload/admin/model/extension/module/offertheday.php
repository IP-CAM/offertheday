<?php

class ModelExtensionModuleOffertheday extends Model {
	public function install() {
        $this->db->query(" CREATE TABLE `" . DB_PREFIX . "offertheday_settings` ( `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, `product_id` INT(11) NOT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "offertheday_settings`");
    }
    
    public function getProductsOffer(){
		$query = $this->db->query("SELECT DISTINCT ps.*, pd.name AS name FROM " . DB_PREFIX . "offertheday_settings os LEFT JOIN " . DB_PREFIX . "product_special ps ON (ps.product_id = os.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = os.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->rows;
		
	}
	
    public function addOffer($product_offer) {
       if (isset($product_offer)) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "offertheday_settings SET product_id = '" . (int)$product_offer['product_id'] . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_special SET product_id = '" . (int)$product_offer['product_id'] . "', customer_group_id = '" . (int)$product_offer['customer_group_id'] . "', priority = '" . (int)$product_offer['priority'] . "', price = '" . (float)$product_offer['price'] . "', date_start = '" . $this->db->escape($product_offer['date_start']) . "', date_end = '" . $this->db->escape($product_offer['date_end']) . "'");
		}
    }
    
    public function delOffer($product_id){
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "offertheday_settings WHERE product_id = '" . (int)$product_id . "'");
	}
	
	public function getProducts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

			$sql .= " AND p.product_id NOT IN (SELECT product_id FROM " . DB_PREFIX . "offertheday_settings)";
		

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
		
	}
}

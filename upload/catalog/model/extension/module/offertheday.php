<?php
class ModelExtensionModuleOffertheday extends Model {
	
    public function getProductsOffer(){
		$query = $this->db->query("
		SELECT ps.*, pd.name AS name, p.tax_class_id, p.price AS old_price
		FROM " . DB_PREFIX . "offertheday_settings os 
		LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = os.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_special ps ON (ps.product_id = os.product_id) 
		LEFT JOIN " . DB_PREFIX . "product_description pd ON (pd.product_id = os.product_id) 
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) 
		ORDER BY ps.date_end ASC LIMIT 1");
		return $query->rows;
	}
	
}

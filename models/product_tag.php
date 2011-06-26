<?php
class ProductTag extends AppModel {

	var $name = 'ProductTag';

	var $belongsTo = array(
			'Product' => array(
				'className'  => 'Product',
				'foreignKey' => 'product_id'
			),

			'Tag' => array(
				'className' => 'Tag',
				'foreignKey' => 'tag_id'
			)
		);
}
?>

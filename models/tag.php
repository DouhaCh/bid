<?php
class Tag extends AppModel {

	var $name = 'Tag';

	var $hasMany = array(
			'ProductTag'  => array(
				'className'  => 'ProductTag',
				'foreignKey' => 'tag_id',
				'dependent'  => true
			)
		);
	/*var $hasAndBelongsToMany = array(
        'Tag' =>
            array(
            	'className'              => 'Product',
             	'joinTable'              => 'products_tags',
             	'foreignKey'             => 'tag_id',
                'associationForeignKey'  => 'product_id',
                'unique'                 => true,
            )
    	);*/
}
?>

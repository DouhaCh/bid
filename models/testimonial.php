<?php
App::import('Vendor', 'phpthumb', array('file' => 'phpthumb'.DS.'phpthumb.php'));

class Testimonial extends AppModel {
	
	var $name = 'Testimonial';
	
	var $belongsTo = array('User', 'Auction');
	
	var $hasMany = array(
		'Vote' => array (
			'className' => 'Vote',
			'limit'	=>	10
		)
	);	
	
	var $actsAs = array(
					'Containable',
					'ImageUpload' => array(
								'image' => array(
									'required' 		=> true,
									'directory'           => 'img/testi_images/',
									'allowed_mime'        => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
									'allowed_extension'   => array('.jpg', '.jpeg', '.png', '.gif'),
									'allowed_size'        => 2097152,
									'random_filename'     => true,
									'resize' => array(
										'thumb' => array(
											'directory' => 'img/testi_images/thumbs/',
											'width'     => IMAGE_THUMB_WIDTH,
											'height'    => IMAGE_THUMB_HEIGHT,
											'phpThumb' => array(
												'far' => 1,
												'bg'  => 'FFFFFF'
											)
										),
				
										'max' => array(
											'directory'   => 'img/testi_images/max/',
											'width'       => IMAGE_MAX_WIDTH,
											'height'      => IMAGE_MAX_HEIGHT,
											'phpThumb' => array(
												'zc' => 0
											)
										)
									)
								)
							)
	);
	
	function __construct($id = false, $table = null, $ds = null){
				parent::__construct($id, $table, $ds);
	
				$this->validate = array(
					'content' => array(
						'minlength' => array(
							'rule' => array('minLength', '1'),
							'message' => __('A message is required.', true)
							)
					)
				);
		}
	function storeImage($image_array) {
			$sizes=array(
				'thumb' => array(
					'directory' => 'img/testi_images/thumbs/',
					'width'     => IMAGE_THUMB_WIDTH,
					'height'    => IMAGE_THUMB_HEIGHT,
					'phpThumb' => array(
						'far' => 1,
						'bg'  => 'FFFFFF'
					)
				),
				'max' => array(
					'directory'   => 'img/testi_images/max/',
					'width'       => IMAGE_MAX_WIDTH,
					'height'      => IMAGE_MAX_HEIGHT,
					'phpThumb' => array(
						'zc' => 0
					)
				)
			);
			
			$uniqueFileName = sha1(uniqid(rand(), true));
	                $extension = explode('.', $image_array['name']);
	                $path = '/img/testi_images/max'.DS. $uniqueFileName . '.' . $extension[count($extension)-1];	                
	                $saveAs    = realpath(WWW_ROOT . 'img/testi_images') .DS. $uniqueFileName . '.' . $extension[count($extension)-1];
			// Attempt to move uploaded file
			if(!move_uploaded_file($image_array['tmp_name'], $saveAs)) {
				return false;
			}

			foreach($sizes as $name => $size){
				$this->generateThumbnailWrap($saveAs, $size);
			}	
			
			return $path;
		}
	/*	
	function paginate ($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		$args = func_get_args();
		$uniqueCacheId = '';
		foreach ($args as $arg) {
			$uniqueCacheId .= serialize($arg);
		}
		if (!empty($extra['contain'])) {
			$contain = $extra['contain'];
		}
		if(!empty($extra['joins'])){
			$joins = $extra['joins'];
		}
		if (!empty($extra['group'])) {
			$group = $extra['group'];
		}
		$uniqueCacheId = md5($uniqueCacheId);
		$pagination = Cache::read('pagination-'.$this->alias.'-'.$uniqueCacheId, 'short');
		if (empty($pagination)) {
			$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain', 'joins'));
			Cache::write('pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination, 'short');
		}
		return $pagination;
	}

	function paginateCount ($conditions = null, $recursive = 0, $extra = array()) {
		$args = func_get_args();
		$uniqueCacheId = '';
		foreach ($args as $arg) {
			$uniqueCacheId .= serialize($arg);
		}
		$uniqueCacheId = md5($uniqueCacheId);
		if (!empty($extra['contain'])) {
			$contain = $extra['contain'];
		}
		if(!empty($extra['joins'])){
			$joins = $extra['joins'];
		}
		if (!empty($extra['group'])) {
			$group = $extra['group'];
		}
		$paginationcount = Cache::read('paginationcount-'.$this->alias.'-'.$uniqueCacheId, 'short');
		if (empty($paginationcount)) {
			$paginationcount = $this->find('count', compact('conditions', 'contain', 'recursive', 'joins', 'group'));
			Cache::write('paginationcount-'.$this->alias.'-'.$uniqueCacheId, $paginationcount, 'short');
		}
		return $paginationcount;
	}*/
}
?>
<?php
App::import('Vendor', 'phpthumb', array('file' => 'phpthumb'.DS.'phpthumb.php'));
class CategoriesController extends AppController {

	var $name = 'Categories';
	
	var $uses = array('Category', 'Auction');

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('index', 'view', 'getlist');
		}
	}

	function index() {
 		$this->Category->recursive = 0;
 		$this->paginate = array('conditions' => array('Category.parent_id' => 0), 'order' => array('Category.name' => 'asc'));
		$categories = $this->paginate();
		foreach($categories as $key => $category) {
			if(empty($category['Category']['image'])) {
				$image = $this->Category->Product->Image->find('first', array('conditions' => array('Product.category_id' => $category['Category']['id'], 'Image.image !=' => ''), 'order' => 'rand()', 'recursive' => 0, 'fields' => 'Image.image'));
				$categories[$key]['Category']['random_image'] = $image['Image']['image'];
			}
		}
		$this->set('categories', $categories);
		$this->pageTitle = __('Categories', true);
	}

	function view($id = null) {
		App::import('vendor', 'vietdecode/vietdecode');
		
		if (is_numeric($id)) {
			//old style, redirect
			$auction=$this->Category->findById($id);
			$this->redirect($this->CategoryLinkFlat($id, vietdecode($auction['Category']['name'])));
		} else {
			preg_match('/([0-9]+)$/U', $id, $matches);
			$id=$matches[0];
		}
		
		
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid Category.', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Category->contain();
		$category = $this->Category->read(null, $id);
		if (empty($category)) {
			$this->Session->setFlash(__('Invalid Category.', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('current_category',$id);
		$this->set('category', $category);
		$this->set('parents', $this->Category->getpath($id));
		$this->set('categories', $this->Category->find('all', array('conditions' => array('Category.parent_id' => $category['Category']['id']), 'order' => array('Category.name' => 'asc'))));

		$this->paginate = array('contain' => 'Product', 'conditions' => array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . date('Y-m-d H:i:s') . "'", 'Product.category_id' => $id, 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate('Auction');

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Category->Product->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key]['Auction'] = $auction['Auction'];
				$auctions[$key]['Product'] = $auction['Product'];
			}
		}

		$this->set('auctions', $auctions);
		
		$this->pageTitle = $category['Category']['name'];
		if(!empty($category['Category']['meta_description'])) {
			$this->set('meta_description', $category['Category']['meta_description']);
		}
		if(!empty($category['Category']['meta_keywords'])) {
			$this->set('meta_keywords', $category['Category']['meta_keywords']);
		}
	}

	function getlist($parent = null, $find = 'list', $count = null){
		if($parent == 'parent') {
			if($find !== 'all') {
				$this->Category->recursive = -1;
			}
			$categories = $this->Category->find($find, array('contain' => '', 'conditions' => array('Category.parent_id' => 0), 'order' => array('Category.name' => 'asc')));

		} else {
			$categories = $this->Category->generateTreeList(null, null, null, '-');
		}
		if($count == 'count') {
			foreach($categories as $key => $category) {
				$category_id = $category['Category']['id'];
				$categories[$key]['Category']['count'] = $this->Category->Product->Auction->find('count', array('conditions' => "Product.category_id = $category_id AND Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . date('Y-m-d H:i:s') . "'"));
				$children = $this->Category->children($category_id, false);
				if(!empty($children)) {
					foreach ($children as $child) {
						$category_id = $child['Category']['id'];
						$categories[$key]['Category']['count'] += $this->Category->Product->Auction->find('count', array('conditions' => "Product.category_id = $category_id AND Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . date('Y-m-d H:i:s') . "'"));
					}
				}
			}
		}

		return $categories;
	}

	function admin_index($id = 0) {
 		if($id > 0) {
 			$this->set('parents', $this->Category->getpath($id));
 		}
 		$this->paginate = array('conditions' => array('Category.parent_id' => $id), 'order' => array('Category.name' => 'asc'));
		$this->set('categories', $this->paginate('Category'));
	}

	function admin_add() {
		if (!empty($this->data)) {
			if(empty($this->data['Category']['parent_id'])) {
				$this->data['Category']['parent_id'] = 0;
			}
			$this->Category->create();
			echo $this->data['Category']['image']['tmp_name'];
			
			if (is_uploaded_file($this->data['Category']['image']['tmp_name'])) {
				$this->data['Category']['image']=$this->__saveImages($this->data['Category']['image']);				
			}			
			
			//debug($this->data['Category']['image']);
			
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The Category has been added successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the category please review the errors below and try again.', true));
			}
		}
		$parentCategories = $this->Category->ParentCategory->generateTreeList(null, null, null, '--');
		$this->set(compact('parentCategories'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Category', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if(!empty($this->data['Category']['image_delete'])) {
				$this->data['Category']['image']['delete'] = 1;
			}
			if(empty($this->data['Category']['parent_id'])) {
				$this->data['Category']['parent_id'] = 0;
			}
			if (is_uploaded_file($this->data['Category']['image']['tmp_name'])) {
				$this->data['Category']['image']=$this->__saveImages($this->data['Category']['image']);				
			}			
			else unset($this->data['Category']['image']);
			if ($this->Category->save($this->data)) {
				$this->Session->setFlash(__('The category was updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$category = $this->Category->read(null, $id);
				$this->data['Category']['image'] = $category['Category']['image'];
				$this->Session->setFlash(__('There was a problem updating the category please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Category->read(null, $id);
		}
		$parentCategories = $this->Category->ParentCategory->generateTreeList(null, null, null, '--');
		$this->set(compact('parentCategories'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Category', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Category->del($id)) {
			$this->Session->setFlash(__('The category was successfully deleted.', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	function __saveImages($image){
						$size=array(
					'directory'   => 'img/categories_images/',
					'width'       => IMAGE_MAX_WIDTH,
					'height'      => IMAGE_MAX_HEIGHT,
					'phpThumb' => array(
						'zc' => 0
					)
				);
			
			// Save path
			//$saveAs = realpath('img/product_images/') . DS . $image_array['name'];
			$uniqueFileName = sha1(uniqid(rand(), true));
	        $extension = explode('.', $image['name']);
	        $saveAs    = realpath(WWW_ROOT . 'img/categories_images/') .DS. $uniqueFileName . '.' . $extension[count($extension)-1];
	                
			// Attempt to move uploaded file
			if(!move_uploaded_file($image['tmp_name'], $saveAs)) {
				return false;
			}

		
			//$this->generateThumbnailWrap($saveAs, $size);
			
			/* $this->create();
			$success=$this->save(array('Image'=>array(	'Image.product_id'=>$image_array['product_id'],
									'Image.image'=>basename($saveAs),
									'Image.order'=>$image_array['order'],
									'Image.created'=>date('Y-m-d H:i:s'),
									'Image.modified'=>date('Y-m-d H:i:s'))));
			
			*/
			
			return basename($saveAs);
			
		}
}
?>
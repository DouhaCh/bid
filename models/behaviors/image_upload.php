<?php

App::import('Core', array('Folder', 'File'));
App::import('Vendor', 'phpthumb', array('file' => 'phpthumb'.DS.'phpthumb.php'));

class ImageUploadBehavior extends ModelBehavior{
    var $options = array(
        'required'		    => false,
		'directory'         => 'img/upload',
		'allowed_mime' 	    => array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'),
		'allowed_extension' => array('.jpg', '.jpeg', '.png', '.gif'),
		'allowed_size'	    => 1048576,
		'random_filename'   => true,
        'resize' => array(
            'max' => array(
                'directory' => 'img/upload/max',
                'width' => 640,
                'height' => 480,
                'phpThumb' => array(
                    'zc' => 0
                )
            ),

            'thumb' => array(
                'directory' => 'img/upload/thumbs',
                'width' => 320,
                'height' => 240,
                'phpThumb' => array(
                    'zc' => 0
                )
            )
        )
    );

    /**
	 * Array of errors
	 */
	var $errors = array();

	var $__fields;
	
	function setup(&$model, $config = array()){
		$config_temp = array();
		
		foreach($config as $field => $options){
		    // Check if given field exists
		    if(!$model->hasField($field)){
			unset($config[$field]);
			unset($model->data[$model->name][$field]);
		
			continue;
		    }
		
				if(substr($options['directory'], -1) != '/'){
			$options['directory'] = $options['directory'] . DS;
		    }
		
		    foreach($options['resize'] as $name => $resize){
			if(isset($options['resize'][$name]['directory']) && substr($options['resize'][$name]['directory'], -1) != '/'){
			    $options['resize'][$name]['directory'] = $options['resize'][$name]['directory'] . DS;
			}
		    }
		
		    $config_temp[$field] = $options;
		}
		
		$this->__fields = $config_temp;
	}
	
	function beforeSave(&$model) {
		/*if(count($this->__fields) > 0) {
		    foreach($this->__fields as $field => $options){
			// Check for model data whether has been set or not
			if(!isset($model->data[$model->name][$field])){
			    continue;
			}
		
			// Check the data if it's not an array
			if(isset($model->data) && !is_array($model->data[$model->name][$field])){
			    unset($model->data[$model->name][$field]);
			    continue;
			}
		
			// Check any error occur
			if($model->data[$model->name][$field]['error'] > 0){
			    // if error == 4 then we are not loading a file, so lets see if we want to delete it
			    if(!empty($model->data[$model->name][$field]['delete'])) {
				// lets delete the old images
				$current = $model->findById($model->data[$model->name][$field]['delete']);
				if(!empty($current[$model->name][$field])) {
					$this->removeImages($current[$model->name][$field], $options);
				}
				$model->data[$model->name][$field] = '';
			    } else {
				unset($model->data[$model->name][$field]);
			    }
			    continue;
			}
		
			// Create final save path
		
			if(!isset($options['random_filename']) || !$options['random_filename']) {
			    $saveAs = realpath($options['directory']) . DS . $model->data[$model->name][$field]['name'];
			} else {
			    // Remove any file which did exist for this model
			    if(!empty($model->data[$model->name]['id'])) {
							$current = $model->findById($model->data[$model->name]['id']);
		
				// lets delete the old images
				if(!empty($current[$model->name][$field])) {
					$this->removeImages($current[$model->name][$field], $options);
				}
			    }
		
			    if(!isset($options['random_filename']) || !$options['random_filename']) {
				$saveAs = realpath(WWW_ROOT . $options['directory']) .DS. $model->data[$model->name][$field]['name'];
				} else {
				    $uniqueFileName = sha1(uniqid(rand(), true));
				    $extension = explode('.', $model->data[$model->name][$field]['name']);
				    $saveAs    = realpath(WWW_ROOT . $options['directory']) .DS. $uniqueFileName . '.' . $extension[count($extension)-1];
			    }
			}
		
			// Attempt to move uploaded file
			if(!move_uploaded_file($model->data[$model->name][$field]['tmp_name'], $saveAs)) {
			    unset($model->data[$model->name][$field]);
			    continue;
			}
		
			// Update model data
			$model->data[$model->name]['type'] = $model->data[$model->name][$field]['type'];
			$model->data[$model->name]['size'] = $model->data[$model->name][$field]['size'];
			$model->data[$model->name][$field] = basename($saveAs);
		
			if(!empty($options['resize'])){
			    foreach($options['resize'] as $name => $resize){
				$this->generateThumbnail($saveAs, $resize);
			    }
			}
		    }
		}*/
		
		return true;
	}

	function beforeDelete(&$model) {
		if(count($this->__fields) > 0){
			$model->read(null, $model->id);
			if (isset($model->data)) {
				foreach($this->__fields as $field => $options){
					if(!empty($model->data[$model->name][$field])) {
						$this->removeImages($model->data[$model->name][$field], $options);
					}
				}
			}
		}
		return true;
	}
	
	function removeImages($file, $options) {
		$file_with_ext = WWW_ROOT . $options['directory'] . $file;
		if(file_exists($file_with_ext)) {
			unlink($file_with_ext);
		}
		
		foreach($options['resize'] as $name => $resize){
			$resizePath = WWW_ROOT . $resize['directory'] . $file;
			if(file_exists($resizePath)){
				unlink($resizePath);
			}
		}
	}

	function generateThumbnailWrap($model, $saveAs, $options){
		$this->generateThumbnail($saveAs, $options);    
	}

    
	function generateThumbnail($saveAs, $options){
		$destination = WWW_ROOT . $options['directory'] . DS . basename($saveAs);
		//pr($options);
		//echo "Dest: $destination<br>";
		$ext = substr(basename($saveAs), strrpos(basename($saveAs), '.') + 1);
		if($ext == '.jpg' || $ext == '.jpeg'){
			$format = 'jpeg';
		}elseif ($ext == 'png'){
			$format = 'png';
		}elseif ($ext == 'gif'){
			$format = 'gif';
		}else{
			$format = 'jpeg';
		}
		
		$phpThumb = new phpthumb();
		$phpThumb->setSourceFilename($saveAs);
		$phpThumb->setCacheDirectory(CACHE);
		$phpThumb->setParameter('w', $options['width']);
		$phpThumb->setParameter('h', $options['height']);
		$phpThumb->setParameter('f', $format);
		
		if(!empty($options['phpThumb'])){
			foreach($options['phpThumb'] as $name => $value){
				if(!empty($value)){
					$phpThumb->setParameter($name, $value);
				}
			}
		}
		//pr($phpThumb->debugmessages);
		if ($phpThumb->generateThumbnail()){
			if($phpThumb->RenderToFile($destination)){
				chmod($destination, 0644);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

?>

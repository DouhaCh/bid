<?php  
    /** 
    * Helper for PHPSpeedy class (PHP5 only!) 
    * 
    * @author      Marcel Raaijmakers (Marcelius) 
    * @copyright   Copyright 2009, Marcel Raaijmakers 
    * @license     http://www.opensource.org/licenses/mit-license.php The MIT License 
    */ 
    class PhpSpeedyHelper extends AppHelper { 
        /** 
        * Storage of the view object 
        */ 
        private $view; 
     
        /** 
        * Constructor 
        */ 
        public function __construct(){     
            $this->view = ClassRegistry::getObject('view'); 
        } 
         
        /** 
        * Trigger 
        */ 
        public function afterLayout(){ 

            if (Configure::read('debug') == 0){ 
                $r = App::import('vendor', 'php_speedy/php_speedy'); 
     			echo '123';
                global $compressor; 
     
                if ($compressor instanceof compressor){ 
                    $compressor->return_content = true; 
                    $this->view->output = $compressor->finish($this->view->output); 
                } 
             
                return parent::afterLayout(); 
            } 
        } 
    } 
?>
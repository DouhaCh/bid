<?php
class Vote extends AppModel {

	var $name = 'Vote';

	var $belongsTo = array('Testimonial', 'User');
	
}
<?php
	Router::connect('/', array('controller' => 'auctions', 'action' => 'home'));
	
	/* Admin Stuff */
	Router::connect('/admin', array('controller' => 'dashboards', 'action' => 'index', 'admin' => true));
	Router::connect('/admin/stats', array('controller' => 'dashboards', 'action' => 'stats', 'admin' => true));
	Router::connect('/admin/users/login', array('controller' => 'users', 'action' => 'login', 'admin' => false));
	Router::connect('/admin/users/logout', array('controller' => 'users', 'action' => 'logout', 'admin' => false));
	
	Router::connect('/cam-nhan/*', array('controller' => 'testimonials', 'action' => 'index'));
	/* Pages Routing */
	Router::connect('/page/*', array('controller' => 'pages', 'action' => 'view'));
	Router::connect('/thong-tin', array('controller' => 'pages', 'action' => 'index'));
	Router::connect('/thong-tin/*', array('controller' => 'pages', 'action' => 'view'));
	Router::connect('/thong-bao/*', array('controller' => 'news', 'action' => 'view'));
	Router::connect('/thong-bao', array('controller' => 'news', 'action' => 'index'));
	
	Router::connect('/lien-he', array('controller' => 'pages', 'action' => 'contact'));
	
	Router::connect('/suggestion', array('controller' => 'pages', 'action' => 'suggestion')); 
	Router::connect('/store', array('controller' => 'pages', 'action' => 'store'));
	
	/* Users */ 
	Router::connect('/thay-doi-thong-tin', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/thay-doi-mat-khau', array('controller' => 'users', 'action' => 'changepassword'));
	
	/* Offline mode */
	Router::connect('/offline', array('controller' => 'settings', 'action' => 'offline'));
	
	Router::connect('/theo-doi', array('controller' => 'watchlists'));
	
	Router::connect('/nap-xu', array('controller' => 'packages', 'action' => 'index'));
	Router::connect('/gioi-thieu-ban', array('controller' => 'invites', 'action' => 'index'));
	
	Router::connect('/ca-nhan', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/dang-nhap/', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/dang-ky/', array('controller' => 'users', 'action' => 'register'));
	/* New daemon urls 
	Router::connect('/dcleaner', array('controller' => 'daemons', 'action' => 'cleaner'));
	Router::connect('/dwinner', array('controller' => 'daemons', 'action' => 'winner'));
	*/
	/* Router for rss */
	Router::parseExtensions('rss');
	
	Router::connect('/dau-gia/:id', array('controller' => 'auctions', 'action' => 'view'), array('id'=>'.*-.*','pass'=>array('id')));
	Router::connect('/dau-gia', array('controller' => 'auctions', 'action' => 'index'));
	Router::connect('/danh-muc/:id', array('controller' => 'categories', 'action' => 'view'), array('id'=>'.*-.*','pass'=>array('id')));
	Router::connect('/da-ban', array('controller' => 'auctions', 'action' => 'closed'));
	Router::connect('/dau-gia-hot', array('controller' => 'auctions', 'action' => 'featured'));
	Router::connect('/dau-gia-chien-thang', array('controller' => 'auctions', 'action' => 'won'));

	Router::parseExtensions('json');
?>
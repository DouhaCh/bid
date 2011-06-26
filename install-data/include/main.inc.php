<?php

/******************************************************************************************
 ******************************************************************************************
 *
 * phpPennyAuction Installer v0.1
 * Last updated: 04-Oct-2010
 *
 * DO NOT EDIT BELOW THIS LINE UNLESS YOU ARE SURE WHAT YOU ARE DOING!
 *
 *******************************************************************************************
 *******************************************************************************************/

if (FROM_SETUP!==true) exit;

define('DEFAULT_SALT', '07a6b2214c954ba069dbf8196d315f83a30baef9');
define('DS', '/');

if (!isset($_SESSION['setup']) || !is_array($_SESSION['setup'])) {
	//load defaults
	$_SESSION['setup']['config']['db_host']='localhost';
	$_SESSION['setup']['config']['time_zone']='America/New_York';
	$_SESSION['setup']['config']['site_currency']='USD';
	$_SESSION['setup']['config']['site_url']='www.YOURDOMAIN.com';
	$_SESSION['setup']['config']['site_domain']='YOURDOMAIN.com';
	$_SESSION['setup']['config']['site_name']='Your Site Name';
	$_SESSION['setup']['config']['admin_login']='admin';
}

if (isset($_POST['step'])) {
	switch ($_POST['step']) {
		case 'install1':
			//*** final validation
			$_SESSION['setup']['config']=array_merge($_SESSION['setup']['config'],$_POST['config']);
			
			$err=false;
			foreach ($_SESSION['setup']['config'] as $k=>$v) {
				if (!$v) {
					$err='Please complete all required fields.';
					continue;
				}
			}
		
			if (!$err) {
				//*** Proceed with installation
				require('views/install.php');
				exit;
			}
		
		case 'genparams':
			//store and test dbparams settings
			$_SESSION['setup']['config']=array_merge($_SESSION['setup']['config'],$_POST['config']);
			@mysql_connect($_SESSION['setup']['config']['db_host'], $_SESSION['setup']['config']['db_user'], $_SESSION['setup']['config']['db_pass']);
			$conn_err=mysql_error();
			@mysql_select_db($_SESSION['setup']['config']['db_name']);
			$sel_err=mysql_error();
			
			$res=@mysql_query("SHOW TABLES;");
			if (@mysql_num_rows($res)>0) {
				$existing_tables=true;
			} else {
				$existing_tables=false;
			}
			@mysql_free_result($res);
			
			if ($conn_err || $sel_err) {
				$err="Could not connect to database. The following error(s) occured:<br>$conn_err $sel_err";
			} elseif ($existing_tables) {
				$err="The database you specified has one or more tables in it already. You cannot use this installer to upgrade an existing installation.";
			} else {
				//OK to continue, go to general parameters
				$config=$_SESSION['setup']['config'];
				require('install-data/include/views/genparams.php');
				exit;
			}
		
		case 'dbparams':
			$config=$_SESSION['setup']['config'];
			require('install-data/include/views/dbparams.php');
			exit;
		
		case 'servercheck':
			if ($_POST['install_radio']=='2') {
				$install_path=$_POST['custom_dir'];
			} else {
				$install_path=$_SERVER['DOCUMENT_ROOT'];
			}
			
			if ($install_path[strlen($install_path)-1]=='/') {
				//*chomp*
				$install_path=substr($install_path, 0, strlen($install_path)-1);
			}
			
			if (existingInstallation($install_path)) {
				fatalError('Sorry, an existing phpPennyAuction installation already exists in the path you specified.<br>'.
					'You <strong>cannot use this installer</strong> to upgrade an existing installation. Please contact phpPennyAuction support for help.');
				
			}
			
			$_SESSION['setup']['config']['install_path']=$install_path;
			
			$stop_error=false;
			
			//*** PHP version check
			if (strnatcmp(phpversion(),'5.2.1') >= 0 && !preg_match('/^6/', phpversion())) {
				$check['php_version']=array('ok', 'You\'re running PHP v'.phpversion());
			} else {
				$check['php_version']=array('error', 'Script requires PHP 5.2.x or 5.3.x. You\'re running '.phpversion());
				$stop_error=true;
			}
			
			//*** PHP safe mode check, non blocking error
			if (ini_get('safe_mode')=='On') {
				$check['safe_mode']=array('warn', 'Safe mode is on. Whilst the software will operate in safe mode, this setting can cause issues down the road. We recommend disabling safe mode before continuing.');
			} else {
				$check['safe_mode']=array('ok', 'Safe mode is off');
			}
			
			//*** PHP magic quotes check, non blocking error
			if (get_magic_quotes_gpc()) {
				$check['magic_quotes']=array('warn', 'Magic quotes are on. Whilst the software will operate with magic_quotes, this setting can cause issues down the road. We recommend disabling magic_quotes before continuing.');
			} else {
				$check['magic_quotes']=array('ok', 'Magic quotes are off');
			}

			//*** Can we write to the install path?
			if (is_writable($install_path)) {
				$check['install_path']=array('ok', 'Install path is writable');
			} elseif (!is_dir($install_path)) {
				$check['install_path']=array('error', 'Install path doesn\'t exist');
				$stop_error=true;
			} else {
				$check['install_path']=array('error', 'Cannot write to install path '.$install_path);
				$stop_error=true;
			}
			
			//*** Is ioncube installed?
			if (extension_loaded('ionCube Loader')) {
				$check['ioncube']=array('ok', 'ionCube loaders are installed');
			} else {
				$check['ioncube']=array('error', 'ionCube loaders NOT installed. They must be installed before continuing. Please review the <a href="https://members.phppennyauction.com/index.php?_m=knowledgebase&_a=view&parentcategoryid=4&pcid=0&nav=0" target="_blank">KB Articles</a> for help with installing them, or open a ticket at the <a href="https://members.phppennyauction.com/" target="_blank">phpPennyAuction Support Center.</a>');
				$stop_error=true;
			}
			
			//*** is curl installed on the server?
			if (commandExists('curl --version')) {
				$check['curl']=array('ok', 'cURL command installed');
			} else {
				$check['curl']=array('error', 'Shell cURL command not present. Please install it with apt-get install curl or yum install curl.');
				$stop_error=true;
			}
			
			//*** is curl php module installed?
			if (function_exists('curl_setopt')) {
				$check['curl_php']=array('ok', 'PHP cURL module is installed');
			} else {
				$check['curl_php']=array('error', 'PHP cURL module is NOT installed. It must be installed before continuing.');
				$stop_error=true;
			}
			
			//*** is unzip installed?
			if (commandExists('unzip')) {
				$check['unzip']=array('ok', 'Shell unzip command exists');
			} else {
				$check['unzip']=array('error', 'Shell unzip command not present. Please install it with \'apt-get install unzip\' or \'yum install unzip\'.');
				$stop_error=true;
			}
			
			
			require('install-data/include/views/servercheck.php');
			exit;
		default:
	}
	
}


//show default page
require('install-data/include/views/welcome.php');



function commandExists($cmd) {
	$handle = popen($cmd, 'r');
	$read = fread($handle, 2096);
	pclose($handle);
	if (!$read or strstr($read, 'not found')) {
		return false;
	} else {
		return true;
	}
}

function existingInstallation($install_path) {
	$key_files=array(	'app/config/config.php',
				'views/helpers/paypal.php',
				'app/views/helpers/paypal.php',
				'controllers/users_controller.php',
				'models/bid.php',
				'app/models/bid.php',
				'app_controller.php',
				'/app/app_controller.php'
				);
	
	foreach ($key_files as $file) {
		if (file_exists($install_path . DS . $file)) {
			return true;	
		}
	}
	
	return false;
}

function explode_quoted($delim=',', $str, $enclose='"', $preserve=false){
	$resArr = array();
	$n = 0;
	$expEncArr = explode($enclose, $str);
	foreach($expEncArr as $EncItem){
		if($n++%2){
			array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
		}else{
			$expDelArr = explode($delim, $EncItem);
			array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
			$resArr = array_merge($resArr, $expDelArr);
		}
	}
	return $resArr;
}

function fatalError($err) {
	require('views/fatalerror.php');
	exit;
}

function pr($arr) {
	echo "<pre>";
	print_r($arr);
	echo "</pre>";
}
?>

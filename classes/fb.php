<?php

namespace Facebook;

class Fb {
	
	protected static $instance;
	protected static $user;
	
	function _init() {
		
		require_once dirname(__FILE__) . '/base_facebook.php';
		require_once dirname(__FILE__) . '/facebook.php';
		
		\Config::load('facebook::facebook', 'facebook');
		
		static::$instance = new Facebook(array(
			'appId'  => \Config::get('facebook.app_id'),
			'secret' => \Config::get('facebook.secret'),
		));
		
		static::$user = static::$instance->getUser();
		
	}
	
	public static function require_auth() {
		
		if ( ! empty(static::$user))
			return true;
		
		\Config::load('facebook::facebook', 'facebook');
		$login_url 		= static::$instance->getLoginUrl(array('redirect_uri'=>\Uri::current(),'client_id'=>\Config::get('facebook.app_id')));
		
		\Uri::redirect($login_url);
	}
	
	public static function __callStatic($method,$args) {
		$method = lcfirst(str_replace(' ','',ucwords(str_replace('_',' ',$method)))); // screw camelcase
		return call_user_func_array(array(self::$instance,$method),$args);
	}
	
}
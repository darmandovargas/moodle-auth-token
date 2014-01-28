<?php
/* 
 * This file can be place on the root of your Magento instance.
 * Make sure to replace propper values for your setup.
 */
require_once ( "app/Mage.php" );
umask(0);

Mage::app("default");
Mage::getSingleton("core/session", array("name" => "frontend"));
$session = Mage::getSingleton("customer/session");
$session = Mage::getSingleton('customer/session', array('name' => 'frontend'));
$username = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
$email = $username;

$fn = Mage::getSingleton('customer/session')->getCustomer()->getFirstname();
$ln = Mage::getSingleton('customer/session')->getCustomer()->getLastname();

$customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();

if ($customerAddressId){
  $address = Mage::getModel('customer/address')->load($customerAddressId);
  
  $city = $address->getCity();
  $country = $address->getCountryId();
}

if($session->isLoggedIn()){
	$secret_salt = '<YOUR MOODLE AUTH TOKEN SECRET SALT>';
	$timestamp = time();
	$user = $username;
	$newuser = 1;
	//$cohortname = '99999';
	if(empty($city)) $city = '<DEFAULT CITY>';
	if(empty($country)) $country = '<DEFAULT COUNTRY>';
	
	$token = crypt($timestamp.$user.$email,$secret_salt);
	
	$url = '<YOUR MOODLE AUTH TOKEN URL>';
	
	$sso_url = $url.'?user='.$user.
	                '&token='.$token.
	                '&timestamp='.$timestamp.
	                '&email='.$email.
	                '&newuser='.$newuser.
	                '&fn='.$fn.
	                '&ln='.$ln.
	                '&city='.$city.
	                '&country='.$country;

	header("Location: ".$sso_url);
}else{
  $loginurl = Mage::getBaseUrl().'/customer/account/login';
	header("Location: ".$loginurl);

}

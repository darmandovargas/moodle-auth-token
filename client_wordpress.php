
<?php
/*
 * This file is designed to replace login.php on the root of your current Wordpress theme.
 * wp-content/themes/theme_name/login.php
 */
 if(is_user_logged_in()){
	$secret_salt = '<YOUR MOODLE AUTH TOKEN SECRET SALT>'; 
	$timestamp = time();
	$newuser = 1;
  $current_user = wp_get_current_user();
  $user=$current_user->user_login;
	//$cohortname = '99999';
	$email=$current_user->user_email ;
	$fn = $current_user->user_firstname;
	$ln = $current_user->user_lastname;
	
	/* Specific values for WooCommerce */
	$city = get_user_meta($current_user->id, 'billing_city', true);
  $country = get_user_meta($current_user->id , 'billing_country', true);
  /* Specific values for WooCommerce */
  
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
 }
 else{
 	$loginurl = wp_login_url();
 	header("Location:".$loginurl);
 }
?>

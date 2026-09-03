<?php

$vitrina = ['786', '712', '527', '804', '813', '674'];
$vitrina_ios = ['786', '712', '527', '804', '813', '674'];
$vitrina_android = ['786', '712', '527', '804', '813', '674'];

$offers_array = [
	'786' => [
		'id_offer' => '786',
		'link' => 'https://link.lead-cash.com/click?pid=1578&offer_id=786',
		'logo' => '',
		'name' => 'Wonga.co.za',
		'star-value' => '4.7',
		'star-voted' => '2140 voted',
		'plus' => 'Fast payout to bank account',
		'approve' => 'Approval 93%',
		'amount' => 'R 10,000',
		'rate' => 'from 0% first loan',
		'age' => '18 - 65',
		'term' => 'up to 90 days',
		'bank' => true,
		'mastercard' => true,
		'visa' => true,
		'eft' => true,
		'payshap' => true,
	],
	'712' => [
		'id_offer' => '712',
		'link' => 'https://link.lead-cash.com/click?pid=1578&offer_id=712',
		'logo' => '',
		'name' => 'Century.co.za',
		'star-value' => '4.5',
		'star-voted' => '1688 voted',
		'plus' => 'Online decision in minutes',
		'approve' => 'Approval 89%',
		'amount' => 'R 8,500',
		'rate' => 'from 0.4% per day',
		'age' => '21 - 70',
		'term' => 'up to 6 months',
		'bank' => true,
		'mastercard' => true,
		'visa' => true,
		'eft' => true,
		'payshap' => false,
	],
	'527' => [
		'id_offer' => '527',
		'link' => 'https://link.lead-cash.com/click?pid=1578&offer_id=527',
		'logo' => '',
		'name' => 'Lendplus.co.za',
		'star-value' => '4.4',
		'star-voted' => '1325 voted',
		'plus' => 'Flexible repayment options',
		'approve' => 'Approval 87%',
		'amount' => 'R 7,000',
		'rate' => 'competitive rates',
		'age' => '18 - 75',
		'term' => 'up to 120 days',
		'bank' => true,
		'mastercard' => false,
		'visa' => true,
		'eft' => true,
		'payshap' => true,
	],
	'804' => [
		'id_offer' => '804',
		'link' => 'https://link.lead-cash.com/click?pid=1578&offer_id=804',
		'logo' => '',
		'name' => 'Creditbar.co.za',
		'star-value' => '4.6',
		'star-voted' => '1190 voted',
		'plus' => 'Simple application process',
		'approve' => 'Approval 90%',
		'amount' => 'R 6,000',
		'rate' => 'from 0% first loan',
		'age' => '18 - 68',
		'term' => 'up to 60 days',
		'bank' => true,
		'mastercard' => true,
		'visa' => true,
		'eft' => true,
		'payshap' => true,
	],
	'813' => [
		'id_offer' => '813',
		'link' => 'https://link.lead-cash.com/click?pid=1578&offer_id=813',
		'logo' => '',
		'name' => 'Clickcredit.co.za',
		'star-value' => '4.3',
		'star-voted' => '980 voted',
		'plus' => 'Instant EFT available',
		'approve' => 'Approval 85%',
		'amount' => 'R 5,500',
		'rate' => 'from 0.5% per day',
		'age' => '20 - 65',
		'term' => 'up to 45 days',
		'bank' => true,
		'mastercard' => true,
		'visa' => false,
		'eft' => true,
		'payshap' => false,
	],
	'674' => [
		'id_offer' => '674',
		'link' => 'https://link.lead-cash.com/click?pid=1578&offer_id=674',
		'logo' => '',
		'name' => 'Primeloans.co.za',
		'star-value' => '4.2',
		'star-voted' => '864 voted',
		'plus' => 'Clear terms and conditions',
		'approve' => 'Approval 84%',
		'amount' => 'R 9,000',
		'rate' => 'from 0.35% per day',
		'age' => '18 - 70',
		'term' => 'up to 180 days',
		'bank' => true,
		'mastercard' => true,
		'visa' => true,
		'eft' => true,
		'payshap' => true,
	],
];

function yf_capture_tracking()
{
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	if (!empty($_SERVER['REDIRECT_QUERY_STRING'])) {
		$_SESSION['QUERY_STRING'] = $_SERVER['REDIRECT_QUERY_STRING'] . '&a';
	} elseif (!empty($_SERVER['QUERY_STRING'])) {
		$_SESSION['QUERY_STRING'] = $_SERVER['QUERY_STRING'] . '&b';
	}

	if (!empty($_GET)) {
		$_SESSION['QUERY_GET'] = $_GET;
	}

	if (!empty($_GET['source'])) $_SESSION['source'] = $_GET['source'];
	if (!empty($_GET['k_router_campaign'])) $_SESSION['k_router_campaign'] = $_GET['k_router_campaign'];
	if (!empty($_GET['utm_source'])) $_SESSION['utm_source'] = $_GET['utm_source'];
	if (!empty($_GET['utm_campaign'])) $_SESSION['utm_campaign'] = $_GET['utm_campaign'];
	if (!empty($_GET['utm_medium'])) $_SESSION['utm_medium'] = $_GET['utm_medium'];
	if (!empty($_GET['utm_term'])) $_SESSION['utm_term'] = $_GET['utm_term'];
	if (!empty($_GET['utm_content'])) $_SESSION['utm_content'] = $_GET['utm_content'];
	if (!empty($_GET['utm_creative'])) $_SESSION['utm_creative'] = $_GET['utm_creative'];
	if (!empty($_GET['wmid'])) {
		$_SESSION['pid'] = $_GET['wmid'];
		$_SESSION['wmid'] = $_GET['wmid'];
	} elseif (!empty($_GET['pid'])) {
		$_SESSION['pid'] = $_GET['pid'];
		$_SESSION['wmid'] = $_GET['pid'];
	}

	if (!empty($_GET['pixel'])) $_SESSION['pixelfb'] = $_GET['pixel'];

	if (!empty($_GET['click_id'])) {
		$_SESSION['click_id'] = $_GET['click_id'];
		setcookie('click_id', $_GET['click_id'], time() + (86400 * 30), '/');
		$_COOKIE['click_id'] = $_GET['click_id'];
	}
	if (!empty($_GET['offerid'])) $_SESSION['offerid'] = $_GET['offerid'];

	if (!empty($_GET['fbclid'])) {
		setcookie('fbclid', $_GET['fbclid'], time() + 7 * 24 * 60 * 60, '/');
		$_COOKIE['fbclid'] = $_GET['fbclid'];
	}

	foreach (['sub_id_10', 'sub_id_11', 'sub_id_12', 'sub_id_13', 'sub_id_14', 'sub_id_15', 'sub_id_20', 'sub_id_21', 'sub_id_22'] as $key) {
		if (!empty($_GET[$key])) {
			$_SESSION[$key] = $_GET[$key];
			setcookie($key, $_GET[$key], time() + 7 * 24 * 60 * 60, '/');
			$_COOKIE[$key] = $_GET[$key];
		}
	}
}

function yf_detect_os()
{
	$ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
	if (preg_match('/iphone|ipad|ipod/i', $ua)) {
		return 'ios';
	}
	if (preg_match('/android/i', $ua)) {
		return 'android';
	}
	return 'desktop';
}

<?php

$vitrina = ['1001', '1002', '1003'];
$vitrina_ios = ['1001', '1002', '1003'];
$vitrina_android = ['1001', '1002', '1003'];

$offers_array = [
	'1001' => [
		'id_offer' => '1001',
		'link' => '#',
		'logo' => 'image/mfo/cashza.svg',
		'name' => 'CashZA',
		'star-value' => '4.6',
		'star-voted' => '1284 voted',
		'plus' => 'Bank & Instant EFT transfer',
		'approve' => 'Approval 91%',
		'amount' => 'R 8,000',
		'rate' => 'from 0% first loan',
		'age' => '18 - 65',
		'term' => 'up to 6 months',
		'bank' => true,
		'mastercard' => true,
		'visa' => true,
		'eft' => true,
		'payshap' => true,
	],
	'1002' => [
		'id_offer' => '1002',
		'link' => '#',
		'logo' => 'image/mfo/quickrand.svg',
		'name' => 'QuickRand',
		'star-value' => '4.4',
		'star-voted' => '976 voted',
		'plus' => 'Fast online decision',
		'approve' => 'Approval 88%',
		'amount' => 'R 5,000',
		'rate' => 'from 0.5% per day',
		'age' => '21 - 70',
		'term' => 'up to 90 days',
		'bank' => true,
		'mastercard' => true,
		'visa' => true,
		'eft' => true,
		'payshap' => false,
	],
	'1003' => [
		'id_offer' => '1003',
		'link' => '#',
		'logo' => 'image/mfo/safeloan.svg',
		'name' => 'SafeLoan',
		'star-value' => '4.3',
		'star-voted' => '812 voted',
		'plus' => 'Flexible repayment options',
		'approve' => 'Approval 86%',
		'amount' => 'R 6,500',
		'rate' => 'competitive rates',
		'age' => '18 - 75',
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

function yf_vitrina_key($os)
{
	if ($os === 'ios') {
		return 'vitrina_ios';
	}
	if ($os === 'android') {
		return 'vitrina_android';
	}
	return 'vitrina';
}

function yf_build_qurl($sub1)
{
	$qurl = '&sub1=' . urlencode($sub1);

	if (!empty($_COOKIE['subid'])) {
		$qurl .= '&sub8=' . urlencode($_COOKIE['subid']);
	} elseif (!empty($_COOKIE['_subid'])) {
		$qurl .= '&sub8=' . urlencode($_COOKIE['_subid']);
	}

	if (!empty($_COOKIE['sub3'])) {
		$qurl .= '&sub3=' . urlencode($_COOKIE['sub3']);
	}

	if (!empty($_SESSION['source'])) {
		$qurl .= '&sub4=' . urlencode($_SESSION['source']);
	}

	$email = $_SESSION['email'] ?? urldecode($_COOKIE['email'] ?? '');
	if (!empty($email)) {
		$qurl .= '&sub5=' . urlencode($email);
	}

	if (!empty($_COOKIE['lc_uid'])) {
		$qurl .= '&sub6=' . urlencode($_COOKIE['lc_uid']);
	}

	if (!empty($_SESSION['wmid'])) {
		$qurl .= '&sub7=' . urlencode($_SESSION['wmid']);
	}

	$utm_term = $_SESSION['utm_term'] ?? '';
	$utm_creative = $_SESSION['utm_creative'] ?? '';
	if (!empty($utm_term) || !empty($utm_creative)) {
		$qurl .= '&sub2=' . urlencode($utm_term . ' ' . $utm_creative);
	}

	if (!empty($_COOKIE['site_id'])) {
		$qurl .= '&sub20=' . urlencode($_COOKIE['site_id']);
	}

	return $qurl;
}

function yf_get_offer_link($link, $qurl)
{
	$base = (string) $link;
	if ($base === '' || $base === '#') {
		return '#';
	}
	return $base . $qurl;
}

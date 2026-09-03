<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['ok' => false, 'error' => 'method']);
	exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
	$data = $_POST;
}

function yf_fail($field, $code = 'invalid') {
	http_response_code(422);
	echo json_encode(['ok' => false, 'error' => $code, 'field' => $field]);
	exit;
}

function yf_has_keyboard_walk($value) {
	$s = strtolower(preg_replace('/[^a-z]/', '', $value));
	if (strlen($s) < 4) {
		return false;
	}
	$rows = ['qwertyuiop', 'asdfghjkl', 'zxcvbnm', 'abcdefghijklmnopqrstuvwxyz'];
	foreach ($rows as $row) {
		$rev = strrev($row);
		for ($i = 0; $i <= strlen($s) - 4; $i++) {
			$chunk = substr($s, $i, 4);
			if (strpos($row, $chunk) !== false || strpos($rev, $chunk) !== false) {
				return true;
			}
		}
	}
	return false;
}

function yf_looks_like_junk_text($value) {
	$raw = trim((string) $value);
	$compact = strtolower(preg_replace('/[^a-z]/', '', $raw));
	if (strlen($compact) < 2) {
		return true;
	}
	if (preg_match('/^(.)\1{2,}$/', $compact)) {
		return true;
	}
	if (preg_match('/(.)\1{3,}/', $compact)) {
		return true;
	}
	$unique = count(array_unique(str_split($compact)));
	if ($unique < min(3, strlen($compact))) {
		return true;
	}
	if (yf_has_keyboard_walk($compact)) {
		return true;
	}
	$junk = ['test','testing','asdf','qwer','qwerty','zxcv','admin','user','name','firstname','lastname','surname','none','null','bot','spam','abc','abcd','xxx','xxxx','aaa','bbb','ccc','demo','sample','fake'];
	if (in_array($compact, $junk, true)) {
		return true;
	}
	if (!preg_match('/[aeiouy]/i', $compact)) {
		return true;
	}
	return false;
}

function yf_valid_name($value) {
	$name = trim(preg_replace('/\s+/', ' ', (string) $value));
	if (strlen($name) < 2 || strlen($name) > 40) {
		return false;
	}
	if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/u", $name)) {
		return false;
	}
	if (yf_looks_like_junk_text($name)) {
		return false;
	}
	return $name;
}

function yf_valid_phone($value) {
	$phone = preg_replace('/\D+/', '', (string) $value);
	if (!preg_match('/^\d{9}$/', $phone)) {
		return false;
	}
	if (preg_match('/^(\d)\1{8}$/', $phone)) {
		return false;
	}
	if ($phone === '123456789' || $phone === '987654321') {
		return false;
	}
	if (!preg_match('/^[6-8]\d{8}$/', $phone)) {
		return false;
	}
	return $phone;
}

function yf_valid_email($value) {
	$email = strtolower(trim((string) $value));
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return false;
	}
	$parts = explode('@', $email, 2);
	if (count($parts) !== 2) {
		return false;
	}
	[$local, $domain] = $parts;
	$localClean = preg_replace('/[^a-z0-9]/', '', $local);
	if (strlen($localClean) < 2) {
		return false;
	}
	$junkLocals = ['test','testing','asdf','qwer','qwerty','admin','user','email','mail','none','null','bot','spam','xxx','abcd','demo','fake','sample'];
	if (in_array($localClean, $junkLocals, true)) {
		return false;
	}
	if (preg_match('/^(.)\1{3,}$/', $localClean) || yf_has_keyboard_walk($localClean)) {
		return false;
	}
	$badDomains = ['test.com','email.com','mail.com','example.com','asdf.com','qwerty.com'];
	if (in_array($domain, $badDomains, true)) {
		return false;
	}
	return $email;
}

function yf_valid_said($value) {
	$id = preg_replace('/\D+/', '', (string) $value);
	if (!preg_match('/^\d{13}$/', $id)) {
		return false;
	}
	$mm = (int) substr($id, 2, 2);
	$dd = (int) substr($id, 4, 2);
	if ($mm < 1 || $mm > 12 || $dd < 1 || $dd > 31) {
		return false;
	}
	if (preg_match('/^(\d)\1{12}$/', $id) || $id === '0000000000000' || $id === '1234567890123') {
		return false;
	}
	$sum = 0;
	for ($i = 0; $i < 12; $i++) {
		$n = (int) $id[$i];
		if ($i % 2 === 0) {
			$sum += $n;
		} else {
			$n *= 2;
			$sum += intdiv($n, 10) + ($n % 10);
		}
	}
	$check = (10 - ($sum % 10)) % 10;
	if ($check !== (int) $id[12]) {
		return false;
	}
	return $id;
}

if (!empty($data['company_website'])) {
	yf_fail('bot', 'bot');
}

$startedAt = isset($data['form_started_at']) ? (int) $data['form_started_at'] : 0;
if ($startedAt > 0) {
	$elapsedMs = (int) round(microtime(true) * 1000) - $startedAt;
	if ($elapsedMs >= 0 && $elapsedMs < 2000) {
		yf_fail('bot', 'too_fast');
	}
}

$firstName = yf_valid_name($data['first_name'] ?? '');
$surname = yf_valid_name($data['surname'] ?? '');
$phone = yf_valid_phone($data['phone'] ?? '');
$email = yf_valid_email($data['email'] ?? '');
$saId = yf_valid_said($data['sa_id'] ?? '');
$employment = trim((string) ($data['employment_type'] ?? ''));
$allowedEmployment = ['Lawyer', 'Military personnel', 'Self-employed individual', 'Pensioner', 'Other'];

if ($firstName === false) {
	yf_fail('first_name');
}
if ($surname === false) {
	yf_fail('surname');
}
if ($phone === false) {
	yf_fail('phone');
}
if ($email === false) {
	yf_fail('email');
}
if ($saId === false) {
	yf_fail('sa_id');
}
if (!in_array($employment, $allowedEmployment, true)) {
	yf_fail('employment_type');
}

$order = [
	'first_name' => $firstName,
	'surname' => $surname,
	'phone' => $phone,
	'email' => $email,
	'sa_id' => $saId,
	'employment_type' => $employment,
	'created_at' => date('Y-m-d H:i:s'),
	'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
	'ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250),
];

$line = json_encode($order, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($line === false) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'encode']);
	exit;
}

$file = __DIR__ . '/orders.txt';
$ok = file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
if ($ok === false) {
	http_response_code(500);
	echo json_encode(['ok' => false, 'error' => 'write']);
	exit;
}

$_SESSION['order'] = $order;
$_SESSION['ename'] = $order['first_name'];
$_SESSION['email'] = $order['email'];
$_SESSION['phone'] = $order['phone'];

$ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
if (preg_match('/iphone|ipad|ipod/i', $ua)) {
	$os = 'ios';
} elseif (preg_match('/android/i', $ua)) {
	$os = 'android';
} else {
	$os = 'desktop';
}

$dataset = [
	$order['first_name'],
	$order['surname'],
	$order['phone'],
	$order['email'],
	$order['sa_id'],
	$order['employment_type'],
	$os,
];
$sub3 = rawurlencode(implode(',', array_map(static function ($item) {
	return preg_replace('/\s+/', '-', (string) $item);
}, $dataset)));

setcookie('sub3', $sub3, time() + 86400 * 30, '/');
$_COOKIE['sub3'] = $sub3;
setcookie('email', rawurlencode($order['email']), time() + 86400 * 30, '/');
$_COOKIE['email'] = rawurlencode($order['email']);
setcookie('vitrina', 'vitrina_' . $os, time() + 86400 * 7, '/');
$_COOKIE['vitrina'] = 'vitrina_' . $os;

echo json_encode(['ok' => true, 'redirect' => 'offers.php']);

<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require_once __DIR__ . '/setting.php';
yf_capture_tracking();

$page = basename($_SERVER['SCRIPT_NAME'] ?? '');

switch ($page) {
  case 'index.php':
    $landingTitle = 'YesFinance South Africa - Find loan options online';
    $headerMode = 'default';
    break;
  case 'datasteps.php':
    $landingTitle = 'Get Started - YesFinance';
    $headerMode = 'application';
    break;
  case 'about-us.php':
    $landingTitle = 'About Us - YesFinance';
    $headerMode = 'default';
    break;
  case 'contacts.php':
    $landingTitle = 'Contacts - YesFinance';
    $headerMode = 'default';
    break;
  case 'cookies-policy.php':
    $landingTitle = 'Cookie Policy - YesFinance';
    $headerMode = 'default';
    break;
  case 'privacy-policy.php':
    $landingTitle = 'Privacy Policy - YesFinance';
    $headerMode = 'default';
    break;
  case 'terms-and-conditions.php':
    $landingTitle = 'Terms & Conditions - YesFinance';
    $headerMode = 'default';
    break;
  case 'offers.php':
    $landingTitle = 'Personal offers - YesFinance';
    $headerMode = 'default';
    break;
  default:
    $landingTitle = 'YesFinance';
    $headerMode = 'default';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo htmlspecialchars($landingTitle, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="stylesheet" href="css/style.css?v=<?php echo @filemtime(__DIR__."/css/style.css") ?: time(); ?>">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php" aria-label="YesFinance home"><img src="img/logo.svg" alt="YesFinance"></a>
    <nav class="nav" aria-label="Primary navigation">
      <a href="about-us.php">About us</a>
      <a href="index.php#how-it-works">How it works</a>
      <a href="index.php#partners"><?php echo $headerMode === 'application' ? 'Our Lending Network' : 'Partners'; ?></a>
      <a href="index.php#faq">FAQ</a>
      <?php if ($headerMode !== 'application') { ?>
      <a href="contacts.php">Contacts</a>
      <?php } ?>
    </nav>
    <?php if ($headerMode === 'application') { ?>
    <a class="btn btn-outline btn-small" href="index.php">Home</a>
    <?php } else { ?>
    <a class="btn btn-outline btn-small" href="datasteps.php">Get started</a>
    <?php } ?>
  </div>
</header>

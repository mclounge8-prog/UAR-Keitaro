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
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1078072214728765');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1078072214728765&ev=PageView&noscript=1" alt=""></noscript>
  <script type="application/javascript">
  // Keitaro JS Adapter pattern:
  // https://docs.keitaro.io/en/landing-pages-and-offers/js-adapter.html
  // https://docs.keitaro.io/en/landing-pages-and-offers/placeholders.html
  function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    var value = v ? v[2] : null;
    return value && value !== 'undefined' ? value : null;
  }

  function setCookie(name, value, days) {
    if (value === null || typeof value === 'undefined' || value === 'undefined') return;
    var d = new Date();
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * (days || 30));
    document.cookie = name + '=' + value + ';path=/;expires=' + d.toUTCString();
  }

  function isRealSubId(value) {
    return !!(value && String(value) !== 'undefined' && String(value).indexOf('{') === -1 && String(value).trim() !== '');
  }

  function getSubId() {
    var params = new URLSearchParams(window.location.search);
    if (!'{subid}'.match('{')) {
      return '{subid}';
    }
    var el = document.getElementById('yfKeitaroSubid');
    if (el && isRealSubId(el.value)) {
      return el.value;
    }
    try {
      var stored = sessionStorage.getItem('yf_subid');
      if (isRealSubId(stored)) return stored;
    } catch (e) {}
    return params.get('_subid') || params.get('subid') || getCookie('subid') || getCookie('_subid') || null;
  }

  function getToken() {
    var params = new URLSearchParams(window.location.search);
    if (!'{token}'.match('{')) {
      return '{token}';
    }
    return params.get('_token') || params.get('token') || getCookie('token') || null;
  }

  function getPixel() {
    var params = new URLSearchParams(window.location.search);
    if (!'{pixel}'.match('{')) {
      return '{pixel}';
    }
    return params.get('pixel') || getCookie('pixel') || null;
  }

  function resolveSubId() {
    var subid = getSubId();
    if (!isRealSubId(subid)) return null;
    setCookie('subid', subid, 30);
    setCookie('_subid', subid, 30);
    try { sessionStorage.setItem('yf_subid', subid); } catch (e) {}
    var el = document.getElementById('yfKeitaroSubid');
    if (el) el.value = subid;
    return subid;
  }

  function replaceSubidPlaceholders(subid) {
    if (!isRealSubId(subid)) return;
    var subIdRegExp = /\{subid\}/g;
    document.querySelectorAll('input[type="hidden"]').forEach(function (input) {
      if (String(input.value).indexOf('{subid}') !== -1) {
        input.value = String(input.value).replace(subIdRegExp, subid);
      }
    });
    document.querySelectorAll('a[href]').forEach(function (link) {
      var href = link.getAttribute('href') || '';
      if (href.indexOf('{subid}') !== -1) {
        link.setAttribute('href', href.replace(subIdRegExp, subid));
      }
    });
  }

  function applySub8ToOfferLinks() {
    var subid = resolveSubId();
    if (!isRealSubId(subid)) return;
    replaceSubidPlaceholders(subid);
    document.querySelectorAll('a.offer-card__cta').forEach(function (link) {
      try {
        var url = new URL(link.getAttribute('href'), window.location.href);
        url.searchParams.set('sub8', subid);
        link.href = url.toString();
      } catch (e) {}
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    var params = new URLSearchParams(window.location.search);
    var subid = resolveSubId();
    var token = getToken();
    var pixel = getPixel();

    if (token) {
      params.set('_token', token);
      setCookie('token', token, 30);
    }
    if (pixel) setCookie('pixel', pixel, 30);
    if (isRealSubId(subid)) {
      params.set('_subid', subid);
      params.set('subid', subid);
      setCookie('subid', subid, 30);
    }

    replaceSubidPlaceholders(subid);

    document.querySelectorAll('a[href]').forEach(function (link) {
      try {
        var url = new URL(link.href, window.location.href);
        params.forEach(function (v, k) {
          if (v && v !== 'undefined') url.searchParams.set(k, v);
        });
        link.href = url.toString();
      } catch (e) {}
    });

    applySub8ToOfferLinks();
  });

  window.getSubId = getSubId;
  window.resolveSubId = resolveSubId;
  window.applySub8ToOfferLinks = applySub8ToOfferLinks;
  window.isRealSubId = isRealSubId;
  </script>
</head>
<body>
<input type="hidden" id="yfKeitaroSubid" name="_subid" value="{subid}">
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

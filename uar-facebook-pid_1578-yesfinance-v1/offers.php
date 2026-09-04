<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

require_once __DIR__ . '/setting.php';
yf_capture_tracking();

$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower((string) $_SERVER['HTTP_USER_AGENT']) : '';
$isIos = (strpos($userAgent, 'iphone') !== false)
  || (strpos($userAgent, 'ipad') !== false)
  || (strpos($userAgent, 'ipod') !== false)
  || (strpos($userAgent, 'ios') !== false);
$isAndroid = strpos($userAgent, 'android') !== false;

if ($isIos) {
  $os = 'ios';
  $offerIds = !empty($vitrina_ios) ? $vitrina_ios : $vitrina;
  $sub1 = 'vitrina_ios';
} elseif ($isAndroid) {
  $os = 'android';
  $offerIds = !empty($vitrina_android) ? $vitrina_android : $vitrina;
  $sub1 = 'vitrina_android';
} else {
  $os = 'desktop';
  $offerIds = $vitrina;
  $sub1 = 'vitrina';
}

setcookie('vitrina', $sub1, time() + 86400 * 7, '/');
$_COOKIE['vitrina'] = $sub1;

$qurl = '&sub1=' . urlencode($sub1);

$sub8 = '';
if (!empty($_COOKIE['subid'])) {
  $sub8 = (string) $_COOKIE['subid'];
} elseif (!empty($_COOKIE['_subid'])) {
  $sub8 = (string) $_COOKIE['_subid'];
} elseif (!empty($_GET['subid'])) {
  $sub8 = (string) $_GET['subid'];
} elseif (!empty($_GET['_subid'])) {
  $sub8 = (string) $_GET['_subid'];
} elseif (!empty($_SESSION['subid'])) {
  $sub8 = (string) $_SESSION['subid'];
}

if ($sub8 !== '' && strpos($sub8, '{') === false) {
  $qurl .= '&sub8=' . urlencode($sub8);
} else {
  $qurl .= '&sub8={subid}';
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

$offers = [];
foreach ((array) $offerIds as $offerId) {
  $key = (string) $offerId;
  if (isset($offers_array[$key]) && is_array($offers_array[$key])) {
    $offers[] = $offers_array[$key];
  }
}

include __DIR__ . '/header.php';
?>
<main class="page offers-page">
  <section class="section offers-hero">
    <div class="container offers-hero__inner">
      <p class="offers-hero__eyebrow">Matched options</p>
      <h1>Offers for you</h1>
      <p class="offers-hero__lead">Based on your application, here are lenders you can review now. Compare terms and choose what fits you best.</p>
    </div>
  </section>

  <section class="section offers-list-section">
    <div class="container offers-list">
<?php if (empty($offers)): ?>
      <div class="offers-empty">
        <h2>No offers available right now</h2>
        <p>Please try again later or contact support.</p>
      </div>
<?php else: ?>
<?php foreach ($offers as $offer): ?>
<?php
  $baseLink = (string) ($offer['link'] ?? '');
  $finalLink = $baseLink . $qurl;
?>
      <article class="offer-card" data-offer-id="<?php echo htmlspecialchars((string) $offer['id_offer'], ENT_QUOTES, 'UTF-8'); ?>">
        <div class="offer-card__top">
          <div class="offer-card__brand">
<?php if (!empty($offer['logo'])): ?>
            <img class="offer-card__logo" src="<?php echo htmlspecialchars((string) $offer['logo'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars((string) $offer['name'], ENT_QUOTES, 'UTF-8'); ?>" width="56" height="56">
<?php endif; ?>
            <div>
              <h2 class="offer-card__name"><?php echo htmlspecialchars((string) $offer['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
              <div class="offer-card__rating">
                <span class="offer-card__stars" aria-hidden="true">★★★★★</span>
                <span><?php echo htmlspecialchars((string) $offer['star-value'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="offer-card__votes"><?php echo htmlspecialchars((string) $offer['star-voted'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
            </div>
          </div>
          <a class="btn btn-primary offer-card__cta" href="<?php echo htmlspecialchars($finalLink, ENT_QUOTES, 'UTF-8'); ?>" rel="nofollow sponsored">Get money</a>
        </div>

        <ul class="offer-card__highlights">
          <li><?php echo htmlspecialchars((string) $offer['plus'], ENT_QUOTES, 'UTF-8'); ?></li>
          <li><?php echo htmlspecialchars((string) $offer['approve'], ENT_QUOTES, 'UTF-8'); ?></li>
        </ul>

        <dl class="offer-card__meta">
          <div>
            <dt>Amount</dt>
            <dd><?php echo htmlspecialchars((string) $offer['amount'], ENT_QUOTES, 'UTF-8'); ?></dd>
          </div>
          <div>
            <dt>Rate</dt>
            <dd><?php echo htmlspecialchars((string) $offer['rate'], ENT_QUOTES, 'UTF-8'); ?></dd>
          </div>
          <div>
            <dt>Age</dt>
            <dd><?php echo htmlspecialchars((string) $offer['age'], ENT_QUOTES, 'UTF-8'); ?></dd>
          </div>
          <div>
            <dt>Term</dt>
            <dd><?php echo htmlspecialchars((string) $offer['term'], ENT_QUOTES, 'UTF-8'); ?></dd>
          </div>
        </dl>

        <div class="offer-card__payments" aria-label="Payment methods">
<?php if (!empty($offer['bank'])): ?><span>Bank transfer</span><?php endif; ?>
<?php if (!empty($offer['eft'])): ?><span>Instant EFT</span><?php endif; ?>
<?php if (!empty($offer['payshap'])): ?><span>PayShap</span><?php endif; ?>
<?php if (!empty($offer['visa'])): ?><span>Visa</span><?php endif; ?>
<?php if (!empty($offer['mastercard'])): ?><span>Mastercard</span><?php endif; ?>
        </div>
      </article>
<?php endforeach; ?>
<?php endif; ?>
    </div>
  </section>
</main>
<?php include __DIR__ . '/footer.php'; ?>

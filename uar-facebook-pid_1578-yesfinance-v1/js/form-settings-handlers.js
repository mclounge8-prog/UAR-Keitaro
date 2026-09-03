(function () {
  function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
  }

  function setCookie(name, value, days) {
    var d = new Date();
    d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
    document.cookie = name + '=' + value + ';path=/;expires=' + d.toGMTString();
  }

  function getSubId() {
    var params = new URLSearchParams(document.location.search.substr(1));
    if (!'{subid}'.match('{')) {
      return '{subid}';
    }
    if (params.get('_subid')) return params.get('_subid');
    if (params.get('subid')) return params.get('subid');
    if (getCookie('subid')) return getCookie('subid');
    return null;
  }

  function getToken() {
    var params = new URLSearchParams(document.location.search.substr(1));
    if (!'{token}'.match('{')) {
      return '{token}';
    }
    if (params.get('_token')) return params.get('_token');
    if (params.get('token')) return params.get('token');
    if (getCookie('token')) return getCookie('token');
    return null;
  }

  function getPixel() {
    var params = new URLSearchParams(document.location.search.substr(1));
    if (!'{pixel}'.match('{')) {
      return '{pixel}';
    }
    if (params.get('pixel')) return params.get('pixel');
    if (getCookie('pixel')) return getCookie('pixel');
    return null;
  }

  function getOSType() {
    var ua = navigator.userAgent || '';
    if (/iPhone|iPad|iPod/i.test(ua)) return 'ios';
    if (/Android/i.test(ua)) return 'android';
    return 'desktop';
  }

  if (typeof URLSearchParams === 'function') {
    document.addEventListener('DOMContentLoaded', function () {
      var subid = getSubId();
      var token = getToken();
      var pixel = getPixel();
      if (pixel) setCookie('pixel', pixel, 30);
      if (token) setCookie('token', token, 30);
      if (subid) setCookie('subid', subid, 30);
    });
  }

  window.yfGetCookie = getCookie;
  window.yfSetCookie = setCookie;
  window.yfGetOSType = getOSType;
})();

(() => {
  const amount = document.getElementById('amount');
  function money(n) {
    return 'R ' + new Intl.NumberFormat('en-ZA').format(Number(n));
  }
  function updateCalculator() {
    if (!amount) return;
    const a = Number(amount.value);
    ['amountHeadline', 'amountOutput', 'summaryAmount'].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.textContent = money(a);
    });
  }
  amount?.addEventListener('input', updateCalculator);
  updateCalculator();
})();

(() => {
  const form = document.getElementById('applicationForm');
  if (!form) return;

  const steps = [...form.querySelectorAll('.form-step')];
  const next = document.getElementById('continueBtn');
  const back = document.getElementById('backBtn');
  const label = document.getElementById('stepLabel');
  const bar = document.getElementById('progressFill');
  const phone = document.getElementById('phone');
  const saId = document.getElementById('saId');
  const email = document.getElementById('email');
  const suggest = document.getElementById('emailSuggest');
  const employment = document.getElementById('employment');
  const employmentTrigger = document.getElementById('employmentTrigger');
  const employmentList = document.getElementById('employmentList');
  const companyWebsite = document.getElementById('companyWebsite');

  let current = 1;
  let submitting = false;
  const startedAt = Date.now();

  const digits = (v) => String(v || '').replace(/\D/g, '');
  const zaDomains = [
    'gmail.com',
    'yahoo.com',
    'outlook.com',
    'hotmail.com',
    'icloud.com',
    'webmail.co.za',
    'mweb.co.za',
    'telkomsa.net',
    'vodamail.co.za',
    'absamail.co.za',
    'yahoo.co.za',
    'live.com'
  ];

  const junkNames = [
    'test', 'testing', 'asdf', 'qwer', 'qwerty', 'zxcv', 'admin', 'user', 'name',
    'firstname', 'lastname', 'surname', 'none', 'null', 'undefined', 'bot', 'spam',
    'abc', 'abcd', 'xxx', 'xxxx', 'aaa', 'bbb', 'ccc', 'demo', 'sample', 'fake'
  ];

  const junkEmailLocals = [
    'test', 'testing', 'asdf', 'qwer', 'qwerty', 'admin', 'user', 'email', 'mail',
    'none', 'null', 'bot', 'spam', 'xxx', 'abcd', 'demo', 'fake', 'sample'
  ];

  phone.addEventListener('input', () => {
    let d = digits(phone.value).replace(/^27/, '').replace(/^0/, '').slice(0, 9);
    phone.value = d.replace(/(\d{2})(\d{3})(\d{0,4})/, (_, a, b, c) => [a, b, c].filter(Boolean).join(' '));
  });

  saId.addEventListener('input', () => {
    saId.value = digits(saId.value).slice(0, 13);
  });

  function setError(el, bad, message) {
    const f = el.closest('.app-field');
    const err = f?.querySelector('.field-error');
    if (err && message) err.textContent = message;
    err?.classList.toggle('show', !!bad);
    if (el.tagName === 'INPUT' || el.tagName === 'BUTTON') {
      el.setAttribute('aria-invalid', bad ? 'true' : 'false');
    }
  }

  function hasKeyboardWalk(v) {
    const s = v.toLowerCase().replace(/[^a-z]/g, '');
    if (s.length < 4) return false;
    const rows = ['qwertyuiop', 'asdfghjkl', 'zxcvbnm', 'abcdefghijklmnopqrstuvwxyz'];
    return rows.some((row) => {
      for (let i = 0; i <= s.length - 4; i++) {
        const chunk = s.slice(i, i + 4);
        if (row.includes(chunk) || row.split('').reverse().join('').includes(chunk)) return true;
      }
      return false;
    });
  }

  function looksLikeJunkText(v) {
    const raw = String(v || '').trim();
    const compact = raw.toLowerCase().replace(/[^a-z]/g, '');
    if (compact.length < 2) return true;
    if (/^(.)\1{2,}$/.test(compact)) return true;
    if ((compact.match(/(.)\1{3,}/) || []).length) return true;
    if (new Set(compact).size < Math.min(3, compact.length)) return true;
    if (hasKeyboardWalk(compact)) return true;
    if (junkNames.includes(compact)) return true;
    if (!/[aeiouy]/i.test(compact)) return true;
    return false;
  }

  function validName(v) {
    const name = String(v || '').trim().replace(/\s+/g, ' ');
    if (name.length < 2 || name.length > 40) return { ok: false, message: 'Please enter a real name.' };
    if (!/^[A-Za-zÀ-ÖØ-öø-ÿ' -]+$/.test(name)) return { ok: false, message: 'Use letters only in your name.' };
    if (looksLikeJunkText(name)) return { ok: false, message: 'Please enter a real name.' };
    return { ok: true, value: name };
  }

  function validPhone(v) {
    const pd = digits(v);
    if (!/^\d{9}$/.test(pd)) return { ok: false, message: 'Please enter a valid South African mobile number.' };
    if (/^(\d)\1{8}$/.test(pd)) return { ok: false, message: 'Please enter a real mobile number.' };
    if (pd === '123456789' || pd === '987654321') return { ok: false, message: 'Please enter a real mobile number.' };
    if (!/^[6-8]\d{8}$/.test(pd)) return { ok: false, message: 'Please enter a valid South African mobile number.' };
    return { ok: true, value: pd };
  }

  function validEmail(v) {
    const emailValue = String(v || '').trim().toLowerCase();
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(emailValue)) {
      return { ok: false, message: 'Please enter a valid email address.' };
    }
    const [local, domain] = emailValue.split('@');
    const localClean = local.replace(/[^a-z0-9]/g, '');
    if (localClean.length < 2) return { ok: false, message: 'Please enter a real email address.' };
    if (junkEmailLocals.includes(localClean)) return { ok: false, message: 'Please enter a real email address.' };
    if (/^(.)\1{3,}$/.test(localClean)) return { ok: false, message: 'Please enter a real email address.' };
    if (hasKeyboardWalk(localClean)) return { ok: false, message: 'Please enter a real email address.' };
    if (['test.com', 'email.com', 'mail.com', 'example.com', 'asdf.com', 'qwerty.com'].includes(domain)) {
      return { ok: false, message: 'Please enter a real email address.' };
    }
    return { ok: true, value: emailValue };
  }

  function validSAID(v) {
    const id = digits(v);
    if (!/^\d{13}$/.test(id)) return { ok: false, message: 'Please enter a valid South African ID number.' };
    const mm = +id.slice(2, 4);
    const dd = +id.slice(4, 6);
    if (mm < 1 || mm > 12 || dd < 1 || dd > 31) {
      return { ok: false, message: 'Please enter a valid South African ID number.' };
    }
    if (/^(\d)\1{12}$/.test(id) || id === '0000000000000' || id === '1234567890123') {
      return { ok: false, message: 'Please enter a valid South African ID number.' };
    }
    let sum = 0;
    for (let i = 0; i < 12; i++) {
      let n = +id[i];
      if (i % 2 === 0) sum += n;
      else {
        n *= 2;
        sum += Math.floor(n / 10) + (n % 10);
      }
    }
    if ((10 - (sum % 10)) % 10 !== +id[12]) {
      return { ok: false, message: 'Please enter a valid South African ID number.' };
    }
    return { ok: true, value: id };
  }

  function hideSuggest() {
    if (!suggest) return;
    suggest.classList.remove('is-open');
    suggest.innerHTML = '';
  }

  function showSuggest(local, domainPart) {
    if (!suggest) return;
    const list = zaDomains.filter((d) => !domainPart || d.startsWith(domainPart.toLowerCase()));
    if (!local || !list.length) {
      hideSuggest();
      return;
    }
    suggest.innerHTML = list
      .map((d) => `<li role="option"><button type="button" data-domain="${d}">@${d}</button></li>`)
      .join('');
    suggest.classList.add('is-open');
  }

  function updateEmailSuggest() {
    if (!email) return;
    const value = email.value.replace(/\s/g, '');
    if (email.value !== value) email.value = value;
    const at = value.indexOf('@');
    if (at === -1) {
      hideSuggest();
      return;
    }
    const local = value.slice(0, at);
    const domainPart = value.slice(at + 1);
    if (!local) {
      hideSuggest();
      return;
    }
    if (zaDomains.includes(domainPart.toLowerCase())) {
      hideSuggest();
      return;
    }
    showSuggest(local, domainPart);
  }

  if (email && suggest) {
    email.addEventListener('input', updateEmailSuggest);
    email.addEventListener('keyup', updateEmailSuggest);
    email.addEventListener('focus', updateEmailSuggest);
    email.addEventListener('blur', () => setTimeout(hideSuggest, 160));
    suggest.addEventListener('mousedown', (e) => e.preventDefault());
    suggest.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-domain]');
      if (!btn) return;
      const local = email.value.trim().split('@')[0] || '';
      email.value = `${local}@${btn.dataset.domain}`;
      hideSuggest();
      const check = validEmail(email.value);
      setError(email, !check.ok, check.message);
    });
  }

  function closeEmployment() {
    if (!employmentList || !employmentTrigger) return;
    employmentList.hidden = true;
    employmentTrigger.setAttribute('aria-expanded', 'false');
    employmentTrigger.closest('.custom-select')?.classList.remove('is-open');
  }

  function openEmployment() {
    if (!employmentList || !employmentTrigger) return;
    employmentList.hidden = false;
    employmentTrigger.setAttribute('aria-expanded', 'true');
    employmentTrigger.closest('.custom-select')?.classList.add('is-open');
  }

  if (employmentTrigger && employmentList && employment) {
    employmentTrigger.addEventListener('click', () => {
      if (employmentList.hidden) openEmployment();
      else closeEmployment();
    });

    employmentList.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-value]');
      if (!btn) return;
      employment.value = btn.dataset.value;
      employmentTrigger.textContent = btn.dataset.value;
      employmentTrigger.classList.add('has-value');
      employmentList.querySelectorAll('button').forEach((b) => b.classList.toggle('is-active', b === btn));
      closeEmployment();
      setError(employmentTrigger, false);
    });

    document.addEventListener('click', (e) => {
      if (!e.target.closest('[data-custom-select]')) closeEmployment();
    });
  }

  function validate() {
    let ok = true;
    const s = steps[current - 1];

    if (companyWebsite && companyWebsite.value.trim() !== '') return false;

    if (current === 1) {
      const fn = document.getElementById('firstName');
      const sn = document.getElementById('surname');
      const fnCheck = validName(fn.value);
      const snCheck = validName(sn.value);
      setError(fn, !fnCheck.ok, fnCheck.message || 'Please enter your first name.');
      setError(sn, !snCheck.ok, snCheck.message || 'Please enter your surname.');
      if (!fnCheck.ok || !snCheck.ok) ok = false;
      else {
        fn.value = fnCheck.value;
        sn.value = snCheck.value;
      }

      const phoneCheck = validPhone(phone.value);
      setError(phone, !phoneCheck.ok, phoneCheck.message);
      if (!phoneCheck.ok) ok = false;

      const emailCheck = validEmail(email.value);
      setError(email, !emailCheck.ok, emailCheck.message);
      if (!emailCheck.ok) ok = false;
      else email.value = emailCheck.value;
    }

    if (current === 2) {
      const idCheck = validSAID(saId.value);
      setError(saId, !idCheck.ok, idCheck.message);
      if (!idCheck.ok) ok = false;
    }

    if (current === 3) {
      const allowed = ['Lawyer', 'Military personnel', 'Self-employed individual', 'Pensioner', 'Other'];
      const bad = !allowed.includes(employment.value);
      setError(employmentTrigger, bad, 'Please select your employment type.');
      if (bad) ok = false;
    }

    if (!ok) s.querySelector('[aria-invalid="true"]')?.focus();
    return ok;
  }

  function show(n) {
    current = n;
    steps.forEach((s, i) => s.classList.toggle('active', i === n - 1));
    label.textContent = `Step ${n} of 3`;
    bar.style.width = `${(n / 3) * 100}%`;
    bar.classList.remove('progress-fill--pulse');
    void bar.offsetWidth;
    bar.classList.add('progress-fill--pulse');
    back.hidden = n === 1;
    next.textContent = n === 3 ? 'Finish →' : 'Continue →';
    next.disabled = false;
    hideSuggest();
    closeEmployment();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function writeSubsBeforeSave(payload) {
    const os = (typeof window.yfGetOSType === 'function' ? window.yfGetOSType() : 'desktop');
    const dataset = [
      payload.first_name,
      payload.surname,
      payload.phone,
      payload.email,
      payload.sa_id,
      payload.employment_type,
      os
    ];
    const sub3 = encodeURIComponent(dataset.map((i) => String(i).replace(/\s+/g, '-')).join(','));
    if (typeof window.yfSetCookie === 'function') {
      window.yfSetCookie('sub3', sub3, 30);
      window.yfSetCookie('email', encodeURIComponent(payload.email || ''), 30);
      window.yfSetCookie('vitrina', 'vitrina_' + os, 7);
    }
  }

  async function finish() {
    if (submitting) return;
    if (companyWebsite && companyWebsite.value.trim() !== '') return;

    submitting = true;
    next.disabled = true;
    next.textContent = 'Saving...';

    const payload = {
      first_name: document.getElementById('firstName').value.trim(),
      surname: document.getElementById('surname').value.trim(),
      phone: digits(phone.value),
      email: email.value.trim().toLowerCase(),
      sa_id: digits(saId.value),
      employment_type: employment.value.trim(),
      company_website: companyWebsite ? companyWebsite.value.trim() : '',
      form_started_at: startedAt
    };

    writeSubsBeforeSave(payload);

    try {
      const res = await fetch('save_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
        credentials: 'same-origin'
      });
      const text = await res.text();
      let data = {};
      try {
        data = JSON.parse(text);
      } catch (e) {
        throw new Error('bad_response');
      }
      if (!res.ok || !data.ok) throw new Error(data.error || 'save');
      window.location.href = data.redirect || 'offers.php';
    } catch (err) {
      submitting = false;
      next.disabled = false;
      next.textContent = 'Finish →';
      alert('Could not save your application. Please check your details and try again.');
    }
  }

  next.addEventListener('click', () => {
    if (!validate()) return;
    if (current < 3) show(current + 1);
    else finish();
  });

  back.addEventListener('click', () => {
    if (current > 1) show(current - 1);
  });
})();

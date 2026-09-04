<?php
include ('header.php');
?>
<main class="application-page">
  <div class="application-shell">
    <form class="application-card" id="applicationForm" novalidate>
      <input type="hidden" name="_subid" value="{subid}">
      <input type="hidden" name="subid" value="{subid}">

      <div class="hp-field" aria-hidden="true">
        <label for="companyWebsite">Company website</label>
        <input id="companyWebsite" name="company_website" type="text" tabindex="-1" autocomplete="off">
      </div>

      <div class="application-top">
        <img class="application-logo" src="img/logo.svg" alt="YesFinance">
        <div class="secure-mini" aria-live="polite">
          <span class="secure-mini__icon">♢</span>
          <span class="secure-mini__text">Your data<br>is secure</span>
        </div>
      </div>

      <div class="step-label" id="stepLabel">Step 1 of 3</div>
      <div class="progress-track">
        <div class="progress-fill" id="progressFill" style="width:33.333%">
          <span class="progress-glow" aria-hidden="true"></span>
        </div>
      </div>

      <section class="form-step active" data-step="1">
        <h1>Let's get to know you</h1>
        <p class="step-intro">Please provide your full name, mobile number and email.</p>

        <div class="app-field">
          <label for="firstName">First name</label>
          <input
            class="app-input"
            id="firstName"
            name="first_name"
            type="text"
            autocomplete="given-name"
            placeholder="First name"
            maxlength="60"
            required
          >
          <div class="field-error">Please enter your first name.</div>
        </div>

        <div class="app-field">
          <label for="surname">Surname</label>
          <input
            class="app-input"
            id="surname"
            name="surname"
            type="text"
            autocomplete="family-name"
            placeholder="Surname"
            maxlength="60"
            required
          >
          <div class="field-error">Please enter your surname.</div>
        </div>

        <div class="app-field">
          <label for="phone">Phone number</label>
          <div class="phone-wrap">
            <div class="phone-prefix">
              <span class="mini-za">🇿🇦</span> +27
            </div>
            <input
              class="app-input"
              id="phone"
              name="phone"
              type="tel"
              inputmode="numeric"
              autocomplete="tel-national"
              placeholder="82 123 4567"
              maxlength="12"
              required
            >
          </div>
          <div class="field-help">Enter your South African mobile number without +27.</div>
          <div class="field-error">Please enter a valid South African mobile number.</div>
        </div>

        <div class="app-field app-field--email">
          <label for="email">Email</label>
          <div class="email-suggest">
            <input
              class="app-input"
              id="email"
              name="email"
              type="text"
              inputmode="email"
              autocomplete="off"
              placeholder="name@gmail.com"
              maxlength="120"
              required
              aria-autocomplete="list"
              aria-controls="emailSuggest"
            >
            <ul class="email-suggest__list" id="emailSuggest" role="listbox"></ul>
          </div>
          <div class="field-help">After @ choose a domain from the list.</div>
          <div class="field-error">Please enter a valid email address.</div>
        </div>
      </section>

      <section class="form-step" data-step="2">
        <h1>Verify your identity</h1>
        <p class="step-intro">Please enter your South African ID number.</p>

        <div class="app-field">
          <label for="saId">South African ID number</label>
          <input
            class="app-input"
            id="saId"
            name="sa_id"
            type="text"
            inputmode="numeric"
            autocomplete="off"
            placeholder="13-digit ID number"
            maxlength="13"
            required
          >
          <div class="field-help">Your ID number should contain 13 digits.</div>
          <div class="field-error">Please enter a valid South African ID number.</div>
        </div>

        <div class="info-note">
          <span class="info-icon">♢</span>
          <div>
            <strong>Why we ask for this</strong><br>
            Your ID number helps us process your loan-matching request and may be shared with participating credit providers in accordance with our Privacy Policy.
          </div>
        </div>
      </section>

      <section class="form-step" data-step="3">
        <h1>Tell us about your employment</h1>
        <p class="step-intro">What is your employment type?</p>

        <div class="app-field">
          <label id="employmentLabel">Employment type</label>
          <div class="custom-select" data-custom-select>
            <input type="hidden" id="employment" name="employment_type" value="" required>
            <button
              class="custom-select__trigger"
              type="button"
              id="employmentTrigger"
              aria-haspopup="listbox"
              aria-expanded="false"
              aria-labelledby="employmentLabel"
            >Select your employment type</button>
            <ul class="custom-select__list" id="employmentList" role="listbox" hidden>
              <li role="option"><button type="button" data-value="Lawyer">Lawyer</button></li>
              <li role="option"><button type="button" data-value="Military personnel">Military personnel</button></li>
              <li role="option"><button type="button" data-value="Self-employed individual">Self-employed individual</button></li>
              <li role="option"><button type="button" data-value="Pensioner">Pensioner</button></li>
              <li role="option"><button type="button" data-value="Other">Other</button></li>
            </ul>
          </div>
          <div class="field-error">Please select your employment type.</div>
        </div>

        <div class="info-note">
          <span class="info-icon">✓</span>
          <div>
            <strong>Almost done.</strong><br>
            Your information will be used to help match your request with participating credit providers. Loan approval is not guaranteed.
          </div>
        </div>
      </section>

      <div class="app-actions">
        <button class="app-continue" type="button" id="continueBtn">Continue →</button>
        <button class="app-back" type="button" id="backBtn" hidden>Back</button>
      </div>
      <div class="privacy-line">🔒 We use reasonable security measures to protect your information.</div>

    </form>
  </div>
</main>
<?php
include ('footer.php');
?>

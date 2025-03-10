<?php session_start(); ?>
<?php require("botdetect.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
  <title>PHP Login Form CAPTCHA Sample</title>
  <link type="text/css" rel="Stylesheet" href="stylesheet.css" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />
</head>
<body>
  <form method="post" action="process_login.php" id="form1">

    <h1>PHP Login Form CAPTCHA Sample</h1>

    <fieldset>
      <legend>CAPTCHA included in PHP Login form validation</legend>

      <div class="input">
        <label for="Username">Username:</label>
        <input type="text" name="Username" id="Username" class="textbox" value="<?php if (isset($_REQUEST['Username'])) { echo $_REQUEST['Username']; } ?>" />
      </div>
      
      <div class="input">
        <label for="Password">Password:</label>
        <input type="password" name="Password" id="Password" class="textbox" />
      </div>

      <?php // authentication failed, show error message
        $error = '';
        if (isset($_REQUEST['error'])) {
          $error = $_REQUEST['error'];
        }
        if ('Format' == $error) { ?>
          <p class="incorrect">Invalid authentication info</p><?php
        } else if ('Auth' == $error) { ?>
          <p class="incorrect">Authentication failed</p><?php
        }
      ?>

      <div class="input">
      <?php // Adding BotDetect Captcha to the page
        $LoginCaptcha = new Captcha('LoginCaptcha');
        $LoginCaptcha->UserInputID = 'CaptchaCode';
        $LoginCaptcha->ImageWidth = 200;
        $LoginCaptcha->CodeLength = 4;
        $LoginCaptcha->CodeStyle = CodeStyle::Alpha;

        // only show the Captcha if it hasn't been already solved for the current message
        if(!$LoginCaptcha->IsSolved) { ?>
          <label for="CaptchaCode">Retype the characters from the picture:</label>
          <?php echo $LoginCaptcha->Html(); ?>
          <input type="text" name="CaptchaCode" id="CaptchaCode" class="textbox" /><?php

          // CAPTCHA validation failed, show error message
          if ('Captcha' == $error) { ?>
            <span class="incorrect">Incorrect code</span><?php
          }
        }
      ?>
      </div>

      <input type="submit" name="SubmitButton" id="SubmitButton" value="Submit"  />

    </fieldset>

    <div id="notes">
      <div class="note">
        <h3>Description</h3>
        <p>This sample project shows how to add BotDetect CAPTCHA validation to simple PHP login forms.</p>
        <p>To prevent bots from trying to guess the login info by brute force submission of a large number of common values, the visitor first has to prove they are human (by solving the CAPTCHA), and only then is their username and password submission checked against the authentication data store.</p>
        <p>Also, if they enter an invalid username + password combination three times, they have to solve the CAPTCHA again. This prevents attempts in which the attacker would first solve the CAPTCHA themselves, and then let a bot brute-force the authentication info.</p>
        <p>To keep the example code simple, the sample doesn't access a data store to authenticate the user, but <strong>accepts all logins with usernames and passwords at least 5 characters long as valid</strong>.</p>
      </div>
      <div class="note warning">
        <h3>Beta Release Warning</h3>
        <p>BotDetect PHP Captcha Library Beta is a work in progress, and we need you to guide our efforts towards a polished product. Please <a href="http://captcha.com/contact.html?topic=php&amp;utm_source=installation&amp;utm_medium=php&amp;utm_campaign=3.0.Beta3">let us know</a> if you encounter any bugs, implementation issues, or a usage scenario you would like to discuss.</p>
      </div>

      <?php if (Captcha::IsFree()) { ?>
      <div class="note warning">
        <h3>Free Version Limitations</h3>
        <ul>
          <li>The free version of BotDetect includes a randomized <code>BotDetect™</code> trademark in the background of 50% of all Captcha images generated.</li>
          <li>It also has limited sound functionality, replacing the CAPTCHA sound with "SOUND DEMO" for randomly selected 50% of all CAPTCHA codes.</li>
          <li>Lastly, the bottom 10 px of the CAPTCHA image are reserved for a link to the BotDetect website.</li>
        </ul>
        <p>These limitations are removed if you <a href="http://captcha.com/shop.html?utm_source=installation&amp;utm_medium=php&amp;utm_campaign=3.0.Beta3" title="BotDetect CAPTCHA online store, pricing information, payment options, licensing &amp; upgrading">upgrade</a> your BotDetect license.</p>
      </div>
      <?php } ?>
    </div>
  </form>
</body>
</html>
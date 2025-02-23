<?php session_start(); ?>
<?php require("botdetect.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
  <title>BotDetect PHP CAPTCHA Randomization Sample</title>
  <link type="text/css" rel="Stylesheet" href="stylesheet.css" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />
</head>
<body>
  <form method="post" action="" id="form1">

    <h1>BotDetect PHP CAPTCHA Randomization Sample</h1>

    <fieldset>
      <legend>PHP CAPTCHA validation</legend>
      <label for="CaptchaCode">Retype the characters from the picture:</label>

      <?php // Adding BotDetect Captcha to the page
        $RandomizedCaptcha = new Captcha("RandomizedCaptcha");
        $RandomizedCaptcha->UserInputID = "CaptchaCode";
        echo $RandomizedCaptcha->Html();
      ?>

      <div class="validationDiv">
        <input name="CaptchaCode" type="text" id="CaptchaCode" />
        <input type="submit" name="ValidateCaptchaButton" value="Validate" id="ValidateCaptchaButton" />

        <?php // when the form is submitted
          if ($_POST) {
            // validate the Captcha to check we're not dealing with a bot
            $isHuman = $RandomizedCaptcha->Validate();

            if (!$isHuman) {
              // Captcha validation failed, show error message
              echo "<span class=\"incorrect\">Incorrect code</span>";
            } else {
              // Captcha validation passed, perform protected action
              echo "<span class=\"correct\">Correct code</span>";
            }
          }
        ?>
      </div>

    </fieldset>

    <div id="notes">
      <div class="note">
        <h3>Description</h3>
        <p>This sample project shows how to easily randomize various Captcha library parameters, beyond the basic image and sound style randomization used by default.</p>
        <p>Randomly using different Captcha image and sound generation algorithms and other parameter values (such as code length and style) can significantly improve the Captcha security.</p>
        <p>This is the recommended approach to Captcha property setting, since it takes full advantage of the 60 Captcha image and 10 Captcha sound generation algorithms shipped with BotDetect, as well as built-in randomization features.</p>
        <p>Setting <code>Captcha</code> control properties in the <code>CaptchaConfig.php</code> file also reduces the amount of Session state used by the library.</p>
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
<?php session_start(); ?>
<?php require("botdetect.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
  <title>PHP jQuery Validation Captcha Sample</title>
  <link type="text/css" rel="Stylesheet" href="stylesheet.css" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link type="text/css" rel="Stylesheet" href="<?php echo CaptchaUrls::LayoutStylesheetUrl() ?>" />
  <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="js/jquery.validate.min.js"></script>
</head>
<body>
  <form method="post" action="process_form.php" id="form1">

    <h1>PHP jQuery Validation Captcha Sample</h1>

    <fieldset>
      <legend>CAPTCHA included in PHP form validation</legend>

      <div class="input">
        <label for="Name">Name:</label>
        <input type="text" name="Name" id="Name" class="textbox" value="<?php echo getValue('Name');?>" />
        <?php echo getValidationStatus('Name'); ?>
      </div>

      <div class="input">
        <label for="Email">Email:</label>
        <input type="text" name="Email" id="Email" class="textbox" value="<?php echo getValue('Email');?>" />
        <?php echo getValidationStatus('Email'); ?>
      </div>

      <div class="input">
        <label for="Message">Short message:</label>
        <textarea id="Message" name="Message" rows="5" cols="40" class="inputbox"><?php echo getValue('Message');?></textarea>
        <?php echo getValidationStatus('Message'); ?>
      </div>


      <div class="input">
      <?php // Adding BotDetect Captcha to the page

      $jQueryValidatedCaptcha = new Captcha("jQueryValidatedCaptcha");
      $jQueryValidatedCaptcha->UserInputID = "CaptchaCode";
      $jQueryValidatedCaptcha->CodeLength = 3;
      $jQueryValidatedCaptcha->ImageWidth = 150;
      $jQueryValidatedCaptcha->ImageStyle = ImageStyle::Graffiti2;

      // only show the Captcha if it hasn't been already solved for the current message
      if(!$jQueryValidatedCaptcha->IsSolved) { ?>
        <label for="CaptchaCode">Retype the characters from the picture:</label>

        <?php echo $jQueryValidatedCaptcha->Html(); ?>
        <input type="text" name="CaptchaCode" id="CaptchaCode" class="textbox" />
        <?php echo getValidationStatus('CaptchaCode');
      }  ?>
      </div>

      <input type="submit" name="SubmitButton" id="SubmitButton" value="Submit"/>

    </fieldset>

    <script type="text/javascript" src="js/validation_rules.js"></script>

    <?php 
      // remember user input if validation fails
      function getValue($fieldName) {
        $value = '';
        if (isset($_REQUEST[$fieldName])) { 
          $value = $_REQUEST[$fieldName];
        }
        return $value;
      }
      
      // server-side validation status helper function
      function getValidationStatus($fieldName) {
        // validation status param, e.g. "NameValid" from "Name"
        $requestParam = $fieldName . 'Valid';
        if ((isset($_REQUEST[$requestParam]) && $_REQUEST[$requestParam] == 0)) {
          // server-side field validation failed, show error indicator
          $messageHtml = "<label class='incorrect' for='{$fieldName}'>*</label>";
        } else {
          // server-side field validation passed, no message shown
          $messageHtml = '';
        }
        return $messageHtml;
      }
    ?>

    <div id="notes">
      <div class="note">
        <h3>Description</h3>
        <p>This sample project shows how to integrate BotDetect PHP CAPTCHA validation with <a target="_blank" href="http://jqueryvalidation.org/validate">jQuery Validation</a> client-side form validation.</p>
        <p>It uses the Captcha Form Sample as a starting point, and adds client-side jQuery Validation rules for all form fields.</p>
        <p>Client-side validation is not secure by itself (it can be bypassed trivially), so the sample also shows how the protected form action must always be secured by server-side CAPTCHA validation first, and use client-side validation only to improve the user experience.</p>
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
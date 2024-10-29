<?php
session_start();
if ($_POST['captcha'] == $_SESSION['captcha_code']) {
    echo "CAPTCHA verified!";
} else {
    echo "CAPTCHA failed. Please try again.";
}

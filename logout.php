<?php
// 1. Start the session to gain access to the session data.
session_start();

// 2. Unset all of the session variables.
// This removes all data stored in $_SESSION.
$_SESSION = array();

// 3. Destroy the session.
// This completely ends the session, invalidating the session cookie.
session_destroy();

// 4. Redirect the user to the login page.
// The user is now logged out and sent to the main login screen.
header("Location: login.php");
exit(); // Important: exit() ensures no further code is executed after the redirect.
?>

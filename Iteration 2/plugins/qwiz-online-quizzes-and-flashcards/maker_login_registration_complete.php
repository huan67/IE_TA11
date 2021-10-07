<?php
include 'plugin_globals.php';
if ($debug[0]) {
   error_log ('[maker_login_registration_complete.php] $_REQUEST: ' . print_r ($_REQUEST, true));
}
$qname            = $_REQUEST['qname'];
$i_qwiz           = $_REQUEST['i_qwiz'];
$maker_session_id = $_REQUEST['maker_session_id'];
$username         = $_REQUEST['username'];
if ($qname != 'qwiz_' && $qname != 'qcard_') {
   exit;
}
if (! is_numeric ($i_qwiz)) {
   exit;
}
$maker_session_id = filter_var (urldecode ($maker_session_id), FILTER_SANITIZE_STRING);
$username         = filter_var (urldecode ($username),         FILTER_SANITIZE_STRING);
$escaped_username = addslashes ($username);
?>
<!DOCTYPE HTML>
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta charset="UTF-8">
   <title>
      Return to quiz/deck page
   </title>
   <script>
         alert ('Sorry, could not find browser tab with quiz/flashcard deck');
      window.close ();
</script>
</head>
<body>
</body>
</html>

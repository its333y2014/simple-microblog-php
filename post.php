<?php
function error($e) {
  $ret = array('response' => false, 'errmsg' => $e);
  echo json_encode($ret);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
  isset($_POST['user']) && isset($_POST['message'])) {
  
  $c = new mysqli("localhost", "its333", "android2014", "its333");
  if ($c->connect_errno) {
    error("Unable to connect to the database");
    die();
  }

  $u = $c->real_escape_string($_POST['user']);
  $m = $c->real_escape_string($_POST['message']);

  if (strlen($u) >= 1024 && strlen($m) >= 2048) {
    error("Invalid message");
    die();
  }

  $r = $c->query("INSERT INTO microblog (user, message) VALUES ('{$u}','{$m}');");
  if ($r) {
    $ret = array('response' => true, 'errmsg' => '');
    echo json_encode($ret);
  }
  else {
    error('Unable to update the database');
    die();
  }
}
else {
  error("Invalid request");
  die();
}
?>

<?php
  function error($e) {
    $ret = array('response' => false, 'errmsg' => $e, 'timestamp' => 0, 'msg' => array());
    echo json_encode($ret);
  }

  if ($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['time'])) {
    $c = new mysqli("localhost", "its333", "android2014", "its333");
    if ($c->connect_errno) {
      error("Unable to connect to the database");
      die();
    }
    
    $t = $_GET['time'];
    $r = $c->query("SELECT user, message, unix_timestamp(ctime) t FROM microblog 
      WHERE unix_timestamp(ctime) > {$t} ORDER BY t ASC;");
    
    if ($r) {
      $msg = array();
      $maxtime = $t;
      while($row = $r->fetch_assoc()) {
        $m = array();
        $m['user'] = $row['user'];
        $m['message'] = $row['message'];
        $m['time'] = $row['t'];
        $msg[] = $m;
        if ($row['t'] > $maxtime) {
          $maxtime = $row['t'];
        }
      }
      $ret = array('response' => true, 'errmsg' => '', 'timestamp' => $maxtime, 'msg' => $msg);
      echo json_encode($ret);
      die();
    }
    else {
      error("Unable to fetch messages");
      die();
    } 
  }
  else {
    error("Invalid request");
    die();
  }
?>

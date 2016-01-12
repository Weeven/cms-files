<?php
session_start();

if ($_SESSION['timeout'] + 20 * 60 < time()) {
     print "Expired";
}
else {
     print "Active";
}

?>
<?php
require_once __DIR__.'/../config.inc.php';
session_start();
$importsJs = ["/assets/js/postView.js"];
require APP_ROOT.'Views/Navigation/header.php';
require APP_ROOT.'Views/createAnswer.php';
require APP_ROOT.'Views/Navigation/footer.php';
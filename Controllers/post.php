<?php
require_once __DIR__.'/../config.inc.php';
session_start();
$importsCss = ['/assets/stylesheets/posts.css'];
$importsJs = ["/assets/js/postView.js"];
require APP_ROOT.'Views/Navigation/header.php';
require APP_ROOT.'Views/post.php';
require APP_ROOT.'Views/Navigation/footer.php';
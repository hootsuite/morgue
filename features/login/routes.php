<?php
/**
 * Routes for report
 */
$app->get('/login', function () use ($app) {

    $content = "login/views/login";

    $page_title = "login";
    $show_sidebar = false;

    include "views/page.php";
});

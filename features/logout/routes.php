<?php
/**
 * Routes for report
 */
$app->get('/logout', function () use ($app) {

    $content = "logout/views/logout";

    $page_title = "login";
    $show_sidebar = false;

    include "views/page.php";
});

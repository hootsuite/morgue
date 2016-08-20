<?php

/**
 * Routes for irclogs
 */
$app->get('/irclogs', function () use ($app) {
    header("Content-Type: application/json");
    $start_time = $app->request->get('start_time');
    $end_time = $app->request->get('end_time');
    $timezone = $app->request->get('timezone');
    $channel = $app->request->get('channel');
    $offset = $app->request->get('offset');

    $tz = new DateTimeZone($timezone);
    $start_date = date_create($app->request->get('start_date').$start_time);
    $start_date->modify('-5 minutes');
    $start_date->setTimezone($tz);

    $end_date = date_create($app->request->get('end_date').$end_time);
    $end_date->modify('+5 minutes');
    $end_date->setTimezone($tz);

    $irclogsClass = new IrclogsClass(new SlackHandler());

    echo json_encode($irclogsClass->get_logs($start_date->format('U'), $end_date->format('U'), $timezone, $channel));


        // foreach ($response->'messages' as &$value) {
        //     array_push($results, array(
        //         'nick' => $values->'user'
        //         'time' => $start_date->format('U'),
        //         'message' => $end_date->format('U')
        //     ));

        // }
        // Command worked

    // if (!$config["hipchat_room_api_key"] || !$config["hipchat_messages_api_key"]) {
    //     return;
    // }

    // $room_api_key = $config["hipchat_room_api_key"];
    // $messages_api_key = $config["hipchat_messages_api_key"];
    // $auth = new OAuth2($messages_api_key);
    // $client = new Client($auth);

    // $roomAPI = new RoomAPI($client);
    // $roomMessages = $roomAPI->getHistory($channel, array( 'max-results' => 1000, //'start-index' => $offset,
    //                                                       'end-date' => $start_date->format('Y-m-d\TH:i:sP'),
    //                                                       'date' => $end_date->format('Y-m-d\TH:i:sP')));

    // $results = array();
    // $last_message_id = '';
    // foreach ($roomMessages as &$value) {
    //     $date_obj = date_create($value->getDate());
    //     $date_obj->setTimezone($tz);
    //     array_push($results, array('nick' => $value->getFrom(), 'time' => $date_obj->format('Y-m-d h:i:s a'), 'message' => $value->getMessage()));
    //     $last_message_id = $value->getID();
    // }
    // $roomMessages = $roomAPI->getRecentHistory($channel, array( 'max-results' => 400, 'not-before' => $last_message_id));

    // foreach ($roomMessages as &$value) {
    //     $date_obj = date_create($value->getDate());
    //     $date_obj->setTimezone($tz);
    //     if ($start_date->format('Y-m-d\TH:i:sP') <= $date_obj->format('Y-m-d\TH:i:sP') && ($end_date->format('Y-m-d\TH:i:sP') >= $date_obj->format('Y-m-d\TH:i:sP'))) {
    //         array_push($results, array('nick' => $value->getFrom(),'time' => $date_obj->format('Y-m-d h:i:s a'), 'message' => $value->getMessage()));
    //     }
    // }

    // echo json_encode($results);
});

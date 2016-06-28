<?php
use GorkaLaucirica\HipchatAPIv2Client\Auth\OAuth2;
use GorkaLaucirica\HipchatAPIv2Client\Client;
use GorkaLaucirica\HipchatAPIv2Client\API\RoomAPI;
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

    $tz = new DateTimeZone('America/Los_Angeles');

    $start_date = date_create($app->request->get('start_date').$start_time);
    $start_date->setTimezone($tz);

    $end_date = date_create($app->request->get('end_date').$end_time);
    $end_date->setTimezone($tz);

    $config = Configuration::get_configuration("irclogs");

    if (!$config["hipchat_room_api_key"] || !$config["hipchat_messages_api_key"]) {
        return;
    }

    $room_api_key = $config["hipchat_room_api_key"];
    $messages_api_key = $config["hipchat_messages_api_key"];
    $auth = new OAuth2($messages_api_key);
    $client = new Client($auth);

    $roomAPI = new RoomAPI($client);
    $roomMessages = $roomAPI->getHistory($channel, array( 'max-results' => 1000, //'start-index' => $offset,
                                                          'end-date' => $start_date->format('Y-m-d\TH:i:sP'),
                                                          'date' => $end_date->format('Y-m-d\TH:i:sP')));

    $results = array();
    $last_message_id = '';
    foreach ($roomMessages as &$value) {
        $date_obj = date_create($value->getDate());
        $date_obj->setTimezone($tz);
        array_push($results, array('nick' => $value->getFrom(), 'time' => $date_obj->format('Y-m-d h:i:s a'), 'message' => $value->getMessage()));
        $last_message_id = $value->getID();
    }

    $roomMessages = $roomAPI->getRecentHistory($channel, array( 'max-results' => 400, 'not-before' => $last_message_id));

    foreach ($roomMessages as &$value) {
        if (strtotime($value->getDate()) <= strtotime($app->request->get('start_date').$start_time)) {
            array_push($results, array('nick' => $value->getFrom(),'time' => $value->getDate(), 'message' => $value->getMessage()));
        }
    }

    array_push($results, array('nick' => $end_date,'time' => $value->getDate(), 'message' => "is apparently true :/"));


    echo json_encode($results);
});

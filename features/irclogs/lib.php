<?php
use Frlnc\Slack\Http\SlackResponseFactory;
use Frlnc\Slack\Http\CurlInteractor;
use Frlnc\Slack\Core\Commander;

class IrclogsClass {
    public function __construct(LogsHandler $logsHandler) {
        $this->logsHandler = $logsHandler;
    }

    function get_logs($start_date, $end_date, $timezone, $channel) {
        return $this->logsHandler->get_logs($start_date, $end_date, $timezone, $channel);
    }

}
interface LogsHandler {
    function get_logs($start_date, $end_date, $timezone, $channel);
}

class SlackHandler implements LogsHandler {

    private $commander;

    private function getHandler() {
        if (empty($this->commander)) {
            $config = Configuration::get_configuration("irclogs");

            $slack_api_key = $config['slack_api_key'];

            $interactor = new CurlInteractor;
            $interactor->setResponseFactory(new SlackResponseFactory);

            $this->commander = new Commander($slack_api_key, $interactor);
        }
        return $this->commander;
    }

    private function get_username($user_id) {
        $response = $this->getHandler()->execute('users.info', [
            'user' => $user_id
        ]);
        return $response->getBody()['user']['profile']['real_name'];
    }

    private function get_channelname($channel_id) {
        $response = $this->getHandler()->execute('channels.info', [
            'channel' => $channel_id
        ]);
        return $response->getBody()['channel']['name'];
    }

    private function get_channel_id($channel_name) {
        $response = $this->getHandler()->execute('channels.list', []);

        $channel_id = "0000";
        foreach ($response->getBody()['channels'] as $value) {
            if ($value["name"] == $channel_name) {
                return $value["id"];
            }
        }

        return $channel_id;
    }

    function sanitize_message($message) {
        // <@U1QL5BK8E> i am probably going to need to submit my thing next week
        if (preg_match('/<@(\d|\w)*>/', $message) == 1) {
            $user_id_start_location = strpos($message, "<@");
            $user_id_end_location = strpos($message, ">");
            $user_id = substr($message, $user_id_start_location+2, $user_id_end_location - $user_id_start_location - 2);

            $user_name = $this->get_username($user_id);

            $message = preg_replace('/<@(\d|\w)*>/', $user_name, $message, 1);
            return $this->sanitize_message($message);
        } elseif (preg_match('/<#(\d|\w)*>/', $message) == 1) {
            $channel_id_start_location = strpos($message, "<#");
            $channel_id_end_location = strpos($message, ">");
            $channel_id = substr($message, $channel_id_start_location+2, $channel_id_end_location - $channel_id_start_location - 2);

            $channel_name = $this->get_channelname($channel_id);

            $message = preg_replace('/<#(\d|\w)*>/', $channel_name, $message, 1);
            return $this->sanitize_message($message);
        }
        return $message;
    }
    
    function format_messages($response) {
        $results = array();
        foreach ($response->getBody()['messages'] as $value) {
            if (is_null($this->get_username($value['user']))) {
                $username = $value['username'];
                $message = $value['attachments'][0]['fallback'];
            } else {
                $username = $this->get_username($value['user']);
                $message = $this->sanitize_message($value['text']);
            }
            array_push($results, array(
                'nick' => $username,
                'time' => date('Y-m-d h:i:s a', $value['ts']),
                'message' => $message
            ));
        }
        return $results;
    }

    function get_logs($start_date, $end_date, $timezone, $channel) {

        $channel_id = $this->get_channel_id($channel);
        
        if ($channel_id == "0000") {

            $response = $this->getHandler()->execute('groups.list', []);

            $channel_id = "0000";
            foreach ($response->getBody()['groups'] as $value) {
                if ($value["name"] == $channel) {
                    $channel_id = $value["id"];

                    $response = $this->getHandler()->execute('groups.history', [
                        'channel' => $channel_id,
                        'count' => 1000,
                        'oldest' => $start_date,
                        'latest' => $end_date
                    ]);

                    return $this->format_messages($response);
                }
            }
        }        

        $response = $this->getHandler()->execute('channels.history', [
            'channel' => $channel_id,
            'count' => 1000,
            'oldest' => $start_date,
            'latest' => $end_date
        ]);

        return $this->format_messages($response);
    }

}

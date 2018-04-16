<?php
/**
 * Created by PhpStorm.
 * User: vladislavdavarasvili
 * Date: 16/04/2018
 * Time: 00:27
 */

namespace Library\Slack;


use Phalcon\Mvc\User\Plugin;

class Slack extends Plugin
{

    private $config;
    private $user;
    private $channel;

    public function __construct($user = "", $channel = "")
    {
        $this->config = $this->getDI()->getShared('config');
        $this->user = $user;
        $this->channel = $channel;
        return $this;
    }

    public function sendMessage($message, $attachments = [])
    {
        $ch = curl_init("https://slack.com/api/chat.postEphemeral");
        $data = [
            "token" => $this->config->slack->bot,
            "channel" => $this->channel,
            "text" => $message,
            "username" => $this->config->slack->botName,
            "user" => $this->user
        ];

        if (count($attachments) > 0) {
            $data['attachments'] = $attachments;
        }



        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen(http_build_query($data)),
            'Authorization: Bearer '.$this->config->slack->bot
        );

        curl_setopt( $ch, CURLOPT_HTTPHEADER,$header);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if ($result->ok === true) {
            return array(true, $result->message_ts);
        } else {
            return array(false, $result->error);
        }

    }

}
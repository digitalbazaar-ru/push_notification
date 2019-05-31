<?php

namespace DigitalBazaar\PushNotification;

use DigitalBazaar\RestApi\AbstractClient;
use DigitalBazaar\RestApi\BaseClient;
use DigitalBazaar\RestApi\ServiceException;

class PushNotification extends \DigitalBazaar\RestApi\AbstractService
{
    protected $channelId;
    protected $channelKey;
    protected $backUrl;

    public function __construct($channelId, $channelKey, $backUrl = null, AbstractClient $client = null)
    {
        $this->channelId  = $channelId;
        $this->channelKey = $channelKey;
        $this->backUrl    = $backUrl;

        parent::__construct($client);
    }

    protected function getDefaultServiceClient()
    {
        return new BaseClient($this->serviceUri());
    }

    protected function serviceUri()
    {
        return 'https://pushall.ru/api.php';
    }

    protected function requestSend($data)
    {

        try {
            return $this->getResponseData($this->client->post('', ['form_params' => $data]), true);
        } catch (ServiceException $e) {

        }

        return [];
    }

    public function send($title, $text, $backUrl = null, $type)
    {
        $data = [
            'type'  => $type,
            'id'    => $this->channelId,
            'key'   => $this->channelKey,
            'title' => $title,
            'text'  => $text,
            'url'   => $backUrl ?: $this->backUrl,
        ];

        return $this->requestSend($data);
    }

    public function self($id, $key, $title, $text, $backUrl = null)
    {
        $data = [
            'type'  => 'self',
            'id'    => $id,
            'key'   => $key,
            'title' => $title,
            'text'  => $text,
            'url'   => $backUrl ?: $this->backUrl,
        ];

        return $this->requestSend($data);
    }

    public function broadcast($title, $text, $backUrl = null)
    {
        return $this->send($title, $text, $backUrl, 'broadcast');
    }
}

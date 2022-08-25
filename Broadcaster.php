<?php
declare(strict_types=1);

namespace ommu\centrifugo;

use phpcent\Client as CentrifugoClient;
use yii\base\Model;

class Broadcaster extends Model
{
	/**
	 * {@inheritdoc}
	 */
    private static $client = null;
	/**
	 * {@inheritdoc}
	 */
    public $host;
	/**
	 * {@inheritdoc}
	 */
    protected $apiKey;
	/**
	 * {@inheritdoc}
	 */
    protected $secret;

	/**
	 * {@inheritdoc}
	 */
    public function setSecret(string $value): void
    {
        $this->secret = $value;
    }

	/**
	 * {@inheritdoc}
	 */
    public function setApikey(string $value): void
    {
        $this->apiKey = $value;
    }

	/**
	 * {@inheritdoc}
	 */
    public function connect()
    {
        if (self::$client == null) {
            $client = new CentrifugoClient($this->host);
            $client->setApiKey($this->apiKey);

            self::$client = $client;
        }
        return $this;
    }

	/**
	 * {@inheritdoc}
	 */
    public function getClient()
    {
        $this->connect();
        return self::$client;
    }

	/**
	 * {@inheritdoc}
	 */
    public function __call($name, $params)
    {
        $client = $this->getClient();
        if (method_exists($client, $name)) {
            return call_user_func_array([$client, $name], $params);
        }
        return parent::__call($name, $params);
    }
}

<?php


namespace PetrovDAUtils;


use PetrovDAUtils\Enums\EnumsProtocol;

class FotostranaAuthParams
{
    /**
     * @var FotostranaAuthParams
     */
    private static $instance;

    private $sessionKey;
    private $viewerId;
    private $authKey;

    /**
     * @return FotostranaAuthParams
     */
    public static function i()
    {
        if (!static::$instance) static::$instance = new self();
        return static::$instance;
    }

    /**
     * FotostranaAuthParams constructor.
     */
    public function __construct()
    {
        $this->sessionKey = $_REQUEST[EnumsProtocol::SESSION_KEY];
        $this->viewerId   = $_REQUEST[EnumsProtocol::VIEWER_ID];
        $this->authKey    = $_REQUEST[EnumsProtocol::AUTH_KEY];
    }

    /**
     * @return mixed
     */
    public function sessionKey() { return $this->sessionKey; }

    /**
     * @return mixed
     */
    public function viewerId() { return $this->viewerId; }

    /**
     * @return mixed
     */
    public function authKey() { return $this->authKey; }

}
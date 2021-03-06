<?php


namespace PetrovDAUtils\Model;


use PetrovDAUtils\Enums\EnumsConfig;
use PetrovDAUtils\Enums\EnumsProtocol;
use PetrovDAUtils\Interfaces\IError;

class ModelAuth implements IError
{
    private $sessionKey;
    private $viewerId;
    private $authKey;

    /**
     * @var ModelError
     */
    private $error;

    public function __construct()
    {
        $this->sessionKey = $_REQUEST[EnumsProtocol::SESSION_KEY] ?? null;
        $this->viewerId   = $_REQUEST[EnumsProtocol::VIEWER_ID] ?? null;
        $this->authKey    = $_REQUEST[EnumsProtocol::AUTH_KEY] ?? null;

        if (!$this->sessionKey || !$this->viewerId) {
            $this->error = new ModelError('002');
            return;
        }



        $ourAuth = md5(EnumsConfig::FOTOSTRANA_APPID . '_' . $this->viewerId . '_' . EnumsConfig::FOTOSTRANA_SERVERKEY);
        if (EnumsConfig::FOTOSTRANA_AUTH_KEY_CHECK && ($this->authKey === null || $this->authKey != $ourAuth)) {
            $this->error = new ModelError('002');
            return;
        }
    }

    /**
     * @return ModelError
     */
    public function error()
    {
        return $this->error;
    }
    /**
     * @return mixed
     */
    public function sessionKey()
    {
        return $this->sessionKey;
    }

    /**
     * @return mixed
     */
    public function viewerId()
    {
        return $this->viewerId;
    }

}
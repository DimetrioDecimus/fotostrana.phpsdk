<?php
namespace PetrovDAUtils\Service;

use PetrovDAUtils\Enums\EnumsProtocol;
use PetrovDAUtils\Model\ModelRequestResponse;
use PetrovDAUtils\Request\RequestBase;

class ServiceAbstract
{
    /**
     * @var RequestBase
     */
    private $request;

    /**
     * ServiceAbstract constructor.
     * @param RequestBase $requestBase
     */
    public function __construct(RequestBase $requestBase)
    {
        $this->request = $requestBase;
    }

    /**
     * @param string $apiMethod
     * @param array $apiParams
     * @param bool $allowCache
     * @param string $mode
     * @return \PetrovDAUtils\Model\ModelRequestResponse
     * @throws \PetrovDAUtils\Model\ModelError
     */
    protected function requestFotostranaApi(string $apiMethod, array $apiParams, $allowCache = true, string $mode = EnumsProtocol::HTTP_QUERY_GET)
    {
        $request = $this->request->setMethod($apiMethod)->setMode($mode);

        foreach ($apiParams as $param => $value) {
            $request->setParam($param, $value);
        }

        return $request->setCache($allowCache)
                       ->sendRequest();
    }
}
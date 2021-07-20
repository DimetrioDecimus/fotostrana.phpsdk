<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\Enums\EnumsRequest;
use PetrovDAUtils\FotostranaError;
use PetrovDAUtils\Enums\EnumsProtocol;

class ModelBilling extends ModelAbstractObject
{
    const PREBUY_SUCCESS = 'success';
    const PREBUY_ERROR = 'error';

    /**
     * @param float $amount
     * @return bool
     */
    public function withdrawMoneySafe(float $amount)
    {
        $response['id'] = isset($_REQUEST['item']) ? $_REQUEST['item'] : '';
        $response['message'] = isset($_REQUEST['result']) ? $_REQUEST['result'] : self::PREBUY_ERROR;

        if ($response['message'] != self::PREBUY_SUCCESS)
        {
            $this->lastError = new FotostranaError('Billing',"Prebuy action was not success.");
            return false;
        }

        try
        {
            $r = $this->request();
            $r->setMethod('Billing.withDrawMoneySafe');
            $r->setParam(EnumsProtocol::USER_ID, EnumsRequest::$viewerId);
            $r->setParam(EnumsProtocol::MONEY, $amount);
            $r->setMode('POST');
            $r->disallowCache();
            $apiresult = $r->get();
            $r->restoreCache();

            if (!isset($apiresult['response']['transferred']) || $apiresult['response']['transferred']<>$amount) {
                throw new FotostranaError('Billing',"Billing problem: ".serialize($apiresult));
            } else {
                return true;
            }
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return false;
        }
    }

    /**
     * @return float|null
     */
    public function getAppBalance()
    {
        try
        {
            $r = $this->request();
            $r->setMethod('Billing.getAppBalance');
            $r->disallowCache();
            $apiresult = $r->get();
            $r->restoreCache();

            if (isset($apiresult['response']['balance'])) {
                return (float) $apiresult['response']['balance'];
            } else {
                throw new FotostranaError('Billing',"Billing problem: No correct result found.");
            }
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

}
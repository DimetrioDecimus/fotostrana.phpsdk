<?php

/**
 * Класс объекта-пользователя
 */
class fotostranaBilling extends fotostranaObject
{
    const PREBUY_SUCCESS = 'success';
    const PREBUY_ERROR = 'error';


    public function withdrawMoneySafe($amount)
    {
        $response['id'] = isset($_REQUEST['item']) ? $_REQUEST['item'] : '';
        $response['message'] = isset($_REQUEST['result']) ? $_REQUEST['result'] : self::PREBUY_ERROR;

        if ($response['message'] != self::PREBUY_SUCCESS)
        {
            $this->lastError = new fotostranaError('Billing',"Prebuy action was not success.");
            return null;
        }

        try
        {
            $r = $this->request();
            $r->setMethod('Billing.withDrawMoneySafe');
            $r->setParam('userId', FOTOSTRANA_VIEWER_ID);
            $r->setParam('money', $amount);
            $r->disallowCache();
            $apiresult = $r->get();
            $r->restoreCache();

            if (!isset($apiresult['response']['transferred']) || $apiresult['response']['transferred']<>$amount) {
                // Возникла ошибка - возвращаем инйормацию по ошибке
                $this->lastError = new fotostranaError('Billing',"Billing problem: ".serialize($apiresult));
                return null;
            } else {
                // Все хорошо - ничего не возвращаем
                return true;
            }
        }
        catch (fotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

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
                return $apiresult['response']['balance'];
            } else {
                // Возникла ошибка - возвращаем инйормацию по ошибке
                $this->lastError = new fotostranaError('Billing',"Billing problem: No correct result found.");
                return null;
            }
        }
        catch (fotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

}

?>
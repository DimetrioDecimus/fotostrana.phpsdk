<?php
namespace PetrovDAUtils\Models;

use PetrovDAUtils\FotostranaError;

class FotostranaBilling extends FotostranaObject
{
    const PREBUY_SUCCESS = 'success';
    const PREBUY_ERROR = 'error';


    public function withdrawMoneySafe($amount)
    {
        $response['id'] = isset($_REQUEST['item']) ? $_REQUEST['item'] : '';
        $response['message'] = isset($_REQUEST['result']) ? $_REQUEST['result'] : self::PREBUY_ERROR;

        if ($response['message'] != self::PREBUY_SUCCESS)
        {
            $this->lastError = new FotostranaError('Billing',"Prebuy action was not success.");
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
                // �������� ������ - ���������� ���������� �� ������
                $this->lastError = new FotostranaError('Billing',"Billing problem: ".serialize($apiresult));
                return null;
            } else {
                // ��� ������ - ������ �� ����������
                return true;
            }
        }
        catch (FotostranaError $e)
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
                // �������� ������ - ���������� ���������� �� ������
                $this->lastError = new FotostranaError('Billing',"Billing problem: No correct result found.");
                return null;
            }
        }
        catch (FotostranaError $e)
        {
            $this->lastError = $e;
            return null;
        }
    }

}
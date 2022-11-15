<?php

namespace PHPAccounting\Quickbooks\Traits;

trait AccountingIDRequestTrait
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @param $value
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (AccountID generic interface)
     * @see https://developer.intuit.com/app/developer/qbo/docs/api/accounting/all-entities/account
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }
}
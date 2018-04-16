<?php

class UserInvoices extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Column(column="user_id", type="integer", length=10, nullable=false)
     */
    public $user_id;

    /**
     *
     * @var integer
     * @Column(column="invoice_id", type="integer", length=10, nullable=true)
     */
    public $invoice_id;

    /**
     *
     * @var integer
     * @Column(column="invioce_type", type="integer", length=1, nullable=true)
     */
    public $invioce_type;

    /**
     *
     * @var string
     * @Column(column="callback_url", type="string", nullable=true)
     */
    public $callback_url;

    /**
     *
     * @var string
     * @Column(column="trigger_id", type="string", nullable=true)
     */
    public $trigger_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("slackbot");
        $this->setSource("user_invoices");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserInvoices[]|UserInvoices|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserInvoices|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_invoices';
    }

    public function invoiceFromUser(\Library\Invoice\InvoiceModel $invoice_model){

        return $invoice_model
            ->setCallbackUrl($this->callback_url)
            ->setTriggerId($this->trigger_id)
            ->setUserId($this->user_id);

    }

    public function userFromInvoce(\Library\Invoice\InvoiceModel $invoice_model){
        $this->user_id      = $invoice_model->getUserId();
        $this->trigger_id   = $invoice_model->getTriggerId();
        $this->callback_url = $invoice_model->getCallbackUrl();
        $this->invoice_id   = $invoice_model->getId();
        $this->invioce_type = 1;
    }


}

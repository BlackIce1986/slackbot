<?php

class Invoices extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(column="address", type="string", nullable=true)
     */
    public $address;

    /**
     *
     * @var string
     * @Column(column="address_label", type="string", nullable=true)
     */
    public $address_label;

    /**
     *
     * @var string
     * @Column(column="description", type="string", nullable=true)
     */
    public $description;

    /**
     *
     * @var string
     * @Column(column="currency", type="string", length=4, nullable=true)
     */
    public $currency;

    /**
     *
     * @var double
     * @Column(column="price", type="double", length=17, nullable=true)
     */
    public $price;

    /**
     *
     * @var double
     * @Column(column="balance", type="double", length=17, nullable=true)
     */
    public $balance;

    /**
     *
     * @var integer
     * @Column(column="is_completed", type="integer", length=1, nullable=false)
     */
    public $is_completed;

    /**
     *
     * @var double
     * @Column(column="original_price", type="double", length=17, nullable=true)
     */
    public $original_price;

    /**
     *
     * @var string
     * @Column(column="original_currency", type="string", length=4, nullable=true)
     */
    public $original_currency;

    /**
     *
     * @var string
     * @Column(column="notification_id", type="string", nullable=true)
     */
    public $notification_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("slackbot");
        $this->setSource("invoices");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Invoices[]|Invoices|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Invoices|\Phalcon\Mvc\Model\ResultInterface
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
        return 'invoices';
    }

    public function modelFromInvoice(\Library\Invoice\InvoiceModel $invoice_model)
    {
        $this->id                = $invoice_model->getId();
        $this->address           = $invoice_model->getAddress();
        $this->address_label     = $invoice_model->getAddressLabel();
        $this->description       = $invoice_model->getDescription();
        $this->currency          = $invoice_model->getCurrency();
        $this->price             = $invoice_model->getPrice();
        $this->balance           = $invoice_model->getBalance();
        $this->is_completed      = $invoice_model->getCompleted();
        $this->original_price    = $invoice_model->getOriginalPrice();
        $this->original_currency = $invoice_model->getOriginalCurrency();
        $this->notification_id   = $invoice_model->getNotificationKey();
    }


    public function invoiceFromModel(): \Library\Invoice\InvoiceModel
    {
        return (new \Library\Invoice\InvoiceModel())
            ->setId($this->id)
            ->setAddress($this->address)
            ->setAddressLabel($this->address_label)
            ->setCurrency($this->currency)
            ->setPrice($this->price)
            ->setBalance($this->balance)
            ->setDescription($this->description)
            ->setCurrency($this->is_completed)
            ->setOriginalPrice($this->original_price)
            ->setOriginalCurrency($this->original_currency)
            ->setNotificationKey($this->notification_id);

    }

}

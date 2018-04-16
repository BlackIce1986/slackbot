<?php
/**
 * Created by PhpStorm.
 * User: vladislavdavarasvili
 * Date: 13/04/2018
 * Time: 16:55
 */

namespace Library\Invoice;

use Library\Blockio;

class Blockio_invoice implements Invoice_Interface
{


    private $config;

    private $adapter;

    private $realAdapter;

    public function __construct($config)
    {
        $this->config = $config;
        $this->adapter = new Blockio\Blockio($this->config->blockio->btc_api_key, $this->config->blockio->pin,2);
        $this->realAdapter = new Blockio\Blockio($this->config->blockio->_btc_api_key, $this->config->blockio->pin,2);
    }

    function get(int $invoiceId): array
    {

        return array();
    }

    /**
     * @param \Library\Invoice\InvoiceModel $invoice_model
     *
     * @return array  - format ($status, $response) where $status is bool, response InvoiceModel or String
     */

    function create(InvoiceModel $invoice_model): array
    {

        try {
            $newInvoiceAddress = $this->adapter->get_new_address(array('label' => $invoice_model->getAddressLabel()));
            $invoice_model->setAddress($newInvoiceAddress->data->address);


        } catch (\Exception $e) {
            return array(false, $e->getMessage());
        }


        return $this->subscribeToAddress($invoice_model);
    }


    function update(InvoiceModel $invoice_model): array
    {
        // TODO: Implement update() method.
    }


    /**
     * @param string $currency
     *
     * @return array - format ($status, $response) where $status is bool, response AnyObject
     */
    function getRate(string $currency = "EUR"): array
    {

        $currency = mb_strtoupper($currency);
        try {
            $rateList = $this->realAdapter->get_current_price(array('price_base'=>$currency));
        } catch (\Exception $e) {
            return array(false, $e->getMessage());
        }

        return array(true, $rateList);
    }


    function subscribeToAddress(InvoiceModel $invoice_model): array
    {
        try {
            $notificationObj = $this->adapter->create_notification(
                [
                    'type'    => 'address',
                    'address' => $invoice_model->getAddress(),
                    'url'     => $this->config->application->siteUrl.'/payment/notification'
                ]
            );
        } catch (\Exception $e) {
            return array(false, $e->getMessage());
        }



        if ( $notificationObj->status == 'success' ) {
            $invoice_model->setNotificationKey($notificationObj->data->notification_id);
            return array(true, $invoice_model);
        } else {
            return array(false, "Can not get notification_id");
        }
    }

    function getBalance(InvoiceModel $invoice_model)
    {
        return $this->adapter->get_address_balance(array('addresses'=>$invoice_model->getAddress()))->data->available_balance ?? 0;
    }

    function getWalletBalance()
    {
        $addresses =  $this->adapter->get_my_addresses();

        return array_map(function($o){ return array('address'=>$o->address, 'balance' => $o->available_balance);},$addresses->data->addresses);
    }

}
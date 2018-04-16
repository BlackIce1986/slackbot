<?php
/**
 * Created by PhpStorm.
 * User: vladislavdavarasvili
 * Date: 13/04/2018
 * Time: 12:52
 */

namespace Library\Invoice;

use Phalcon\Mvc\User\Plugin;

class Invoice extends Plugin
{
    private $invoiceProvider;
    private $config;


    public function __construct($provider = "")
    {
        $this->config = $this->getDI()->getShared('config');
        switch ($provider) {
            default:
            case 'cryptopay':
                $this->invoiceProvider = new Cryptopay_Invoice($this->config);
                break;
            case 'blockio':
                $this->invoiceProvider = new Blockio_invoice($this->config);
                break;
        }
    }

    public function get(int $invoiceId): array
    {
        return $this->invoiceProvider->get($invoiceId);
    }

    public function create(InvoiceModel $invoice_model): array
    {
        return $this->invoiceProvider->create($invoice_model);
    }

    public function update(InvoiceModel $invoice_model): array
    {
        return $this->invoiceProvider->update($invoice_model);
    }

    public function getRate(string $currency): array
    {
        return $this->invoiceProvider->getRate($currency);
    }

    /**
     * @param \Library\Invoice\InvoiceModel $invoice_model
     *
     * @return float
     */
    public function getBalance(InvoiceModel $invoice_model) {
        return $this->invoiceProvider->getBalance($invoice_model) ?? 0;
    }

    /**
     * @param \Library\Invoice\InvoiceModel $invoice_model
     *
     * @return array of items to display message on slack like
     *
     * Bot: Awaitng 0.015295 BTC to 3XkqYep0TywnBTmxkusWhJ...
     * <QR-code image>
     * bitcoin:<address>[?amount=<amount>][[?|&]label=<label>][[?|&]message=<message>]
     *
     */
    public function formatSuccess(InvoiceModel $invoice_model){
        if ($invoice_model->getOriginalPrice() !== null && $invoice_model->getOriginalCurrency() !== null){
            $originalPrice = " (".$invoice_model->getOriginalPrice()." ".$invoice_model->getOriginalCurrency().")";
        }
        $responseArray = array(
            'text' => 'Awaiting '.$invoice_model->getPrice().' '.$invoice_model->getCurrency().''.(( isset($originalPrice) )?$originalPrice:'').' to '.$invoice_model->getAddress(),
            'attachments' => [
                ['image_url' => "https://chart.googleapis.com/chart?cht=qr&chs=160&chl=".$invoice_model->getAddress()."&chld=M|0"],
                ['title'  => "bitcoin:".$invoice_model->getAddress()."?amount=".$invoice_model->getPrice()."&message=".$invoice_model->getDescription()."&label=".$invoice_model->getAddressLabel()],
                ['title_link'  => "bitcoin:".$invoice_model->getAddress()."?amount=".$invoice_model->getPrice()."&message=".$invoice_model->getDescription()."&label=".$invoice_model->getAddressLabel()],
            ]
        );

        return $responseArray;
    }

    public function getWalletBalance()
    {
        return $this->invoiceProvider->getWalletBalance();
    }


}
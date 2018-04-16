<?php
/**
 * Created by PhpStorm.
 * User: vladislavdavarasvili
 * Date: 13/04/2018
 * Time: 13:29
 */

namespace Library\Invoice;


class Cryptopay_Invoice implements Invoice_Interface
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function create(InvoiceModel $invoice_model): array
    {
        // TODO: Implement create() method.
    }

    public function get(int $invoice_id): array
    {
        // TODO: Implement get() method.
    }

    public function update(InvoiceModel $invoice_model)
    {
        // TODO: Implement update() method.
    }

    public function getRate(string $currency): array
    {
        // TODO: Implement getRate() method.
    }

    public function subscribeToAddress(InvoiceModel $invoice_model): array
    {
        // TODO: Implement subscribeToAddress() method.
    }

}
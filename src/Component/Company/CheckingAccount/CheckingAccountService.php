<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 15/02/2020
 * Time: 20:49
 */

namespace App\Component\Company\CheckingAccount;


use App\Entity\Company\Company;
use App\Repository\Invoice\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CheckingAccountService {
    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;

    public function __construct(InvoiceRepository $invoiceRepository) {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function getCheckingAccountFor(Company $issuer, Company $client): CheckingAccount {
        $checkingAccount = new CheckingAccount($issuer, $client);
        $invoices = $this->invoiceRepository->findInvoicesIssuedByTo($issuer, $client);

        // Invoices
        $balance = 0.0;
        foreach ($invoices as $invoice) {
            $balance -= $invoice->getTotal();
            $item = new CheckingAccountItem(
                $invoice->getDate(),
                CheckingAccountItem::TYPE_INVOICE,
                $invoice,
                $invoice->getTotal(), 0, $balance
            );
            $checkingAccount->addItem($item);

            // Payments
            foreach ($invoice->getPayments() as $payment) {
                $balance += $payment->getAmount();
                $item = new CheckingAccountItem(
                    $payment->getDate(),
                    CheckingAccountItem::TYPE_RECEIPT,
                $payment,
                0, $payment->getAmount(), $balance);
            }
        }

        return $checkingAccount;
    }
}
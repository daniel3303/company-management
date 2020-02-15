<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 15/02/2020
 * Time: 20:50
 */

namespace App\Component\Company\CheckingAccount;


use Carbon\Carbon;

class CheckingAccountItem {
    public const TYPE_INVOICE = 'TYPE_INVOICE';
    public const TYPE_RECEIPT = 'TYPE_RECEIPT';

    /**
     * @var Carbon
     */
    protected Carbon $date;

    /**
     * @var string
     */
    protected string $documentType;

    /**
     * @var float
     */
    private float $debit;

    /**
     * @var float
     */
    private float $credit;

    /**
     * @var float
     */
    private float $balance;

    /**
     * @var object
     */
    private object $document;

    public function __construct(\DateTime $date, string $documentType, object $document, float $debit, float $credit, float $balance) {
        $this->date = Carbon::instance($date);
        $this->documentType = $documentType;
        $this->debit = $debit;
        $this->credit = $credit;
        $this->balance = $balance;
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function getDocumentType(): string {
        return $this->documentType;
    }

    /**
     * @param string $documentType
     */
    public function setDocumentType(string $documentType): void {
        $this->documentType = $documentType;
    }

    /**
     * @return float
     */
    public function getDebit(): float {
        return $this->debit;
    }

    /**
     * @param float $debit
     */
    public function setDebit(float $debit): void {
        $this->debit = $debit;
    }

    /**
     * @return float
     */
    public function getCredit(): float {
        return $this->credit;
    }

    /**
     * @param float $credit
     */
    public function setCredit(float $credit): void {
        $this->credit = $credit;
    }

    /**
     * @return float
     */
    public function getBalance(): float {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance(float $balance): void {
        $this->balance = $balance;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon {
        return $this->date;
    }

    /**
     * @param Carbon $date
     */
    public function setDate(Carbon $date): void {
        $this->date = $date;
    }

    /**
     * @return object
     */
    public function getDocument(): object {
        return $this->document;
    }




}
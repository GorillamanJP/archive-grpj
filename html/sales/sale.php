<?php
require_once $_SERVER['DOCUMENT_ROOT']."/accountants/accountant.php";
require_once $_SERVER['DOCUMENT_ROOT']."/details/detail.php";
class Sale{
    private Accountant $accountant;
    public function get_accountant():Accountant{
        return $this->accountant;
    }

    private array $details;
    public function get_details():array{
        return $this->details;
    }

    public function __construct()
    {
        $this->accountant = new Accountant();
        $this->details = [];
    }

    // public function create(int $total_amount, )

    public function get_from_accountant_id(int $accountant_id):Sale{
        try {
            $this->accountant = $this->accountant->get_from_id($accountant_id);
            $detail = new Detail();
            $this->details = $detail->gets_from_accountant_id($accountant_id);
            return $this;
        }catch(Exception $e){
            throw new Exception(previous: $e);
        }
    }
}
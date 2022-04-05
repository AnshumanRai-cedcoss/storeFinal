<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $id;
    public $customerName;
    public $address;
    public $zip;
    public $product;
    public $quantity;
    			
}
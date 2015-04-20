<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Shopcategory extends Moloquent {
    protected $connection = 'mongodb_ad';
    protected $collection = 'shopcategory';

}
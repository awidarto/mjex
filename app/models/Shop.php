<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Shop extends Moloquent {
    protected $connection = 'mongodb_ad';
    protected $collection = 'merchants';

}
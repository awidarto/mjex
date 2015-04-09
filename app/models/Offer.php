<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Offer extends Moloquent {
    protected $connection = 'mongodb_ad';
    protected $collection = 'assets';

}
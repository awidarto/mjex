<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Ad extends Moloquent {
    protected $connection = 'mongodb_ad';
    protected $collection = 'assets';

}
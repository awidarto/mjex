<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Adclick extends Moloquent {
    protected $connection = 'mongodb_ad';
    protected $collection = 'adclick';

}
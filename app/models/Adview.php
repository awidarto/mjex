<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Adview extends Moloquent {
    protected $connection = 'mongodb_ad';
    protected $collection = 'adview';

}
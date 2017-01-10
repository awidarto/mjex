<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Geolog extends Eloquent {
    protected $connection = 'mongodb_delivery';
    protected $collection = 'geolog';

}
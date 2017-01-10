<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Uploaded extends Eloquent {
    protected $connection = 'mongodb_delivery';
    protected $collection = 'uploaded';

}
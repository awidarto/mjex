<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Deliverynote extends Eloquent {
    protected $connection = 'mongodb_delivery';
    protected $collection = 'deliverynote';

}
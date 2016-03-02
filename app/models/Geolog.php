<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Geolog extends Eloquent {
    protected $connection = 'mongodb';
    protected $collection = 'geolog';

}
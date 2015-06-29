<?php
use Jenssegers\Mongodb\Model as Moloquent;

class Logger extends Moloquent {
    protected $connection = 'mongodb';
    protected $collection = 'accesslog';

}
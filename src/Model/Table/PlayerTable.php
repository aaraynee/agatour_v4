<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PlayerTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Round');
    }
}
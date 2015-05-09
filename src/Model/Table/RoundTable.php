<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RoundTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Player');
        $this->belongsTo('Tournament');
    }
}
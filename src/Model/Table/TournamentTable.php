<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TournamentTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Round', [
            'sort' => ['Round.position' => 'ASC']
        ]);
        $this->belongsTo('Course');
    }
}
<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class SeasonTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Tournament', [
            'foreignKey' => FALSE,
            'conditions' => [
                'Tournament.date >=' => 'Season.start_date',
                'Tournament.date <=' => 'Season.end_date',
    ]]
    );
    }
}
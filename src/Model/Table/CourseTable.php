<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class CourseTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Tournament');
    }
}
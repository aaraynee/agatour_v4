<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class WpPostmetaTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('wp_postmeta');
    }

}
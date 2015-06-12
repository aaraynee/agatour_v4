<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class WpTermRelationshipsTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('wp_term_relationships');
    }

}
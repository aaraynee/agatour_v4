<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TournamentModel extends Table
{
    public function initialize(array $config)
    {
        $this->table('wp_posts');
    }
}
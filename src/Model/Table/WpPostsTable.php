<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class WpPostsTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('wp_posts');
        $this->hasMany(
            'WpPostmeta', ['foreignKey' => 'post_id']);
    }

}
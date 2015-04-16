<?php

namespace App\Shell;

use Cake\Console\Shell;

class TournamentUpdateShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Tournament');
    }

    public function main()
    {
        $test = $this->Tournament->query(
            'SELECT * FROM wp_posts WHERE post_type = "player"'
        );

        foreach($test as $t){

            $this->out(print_r($t));
        }
    }
}
<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class UpdateShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
    }


    public function main()
    {

    }

    public function tournament()
    {
        $tournamentTable = TableRegistry::get('Tournament');
        $seasonTable = TableRegistry::get('Season');

        $tournaments = $tournamentTable
            ->find();

        $seasons = $seasonTable
            ->find();

        foreach($tournaments as $tournament){

            $this->out($tournament->name);
            $this->out('------------------');

            foreach($seasons as $season){
                $tournament_date = date('Y-m-d', strtotime($tournament->date));
                $season_start_date = date('Y-m-d', strtotime($season->start_date));
                $season_end_date = date('Y-m-d', strtotime($season->end_date));

                if($season_start_date <= $tournament_date && $season_end_date >= $tournament_date){
                    if($tournament->season_id != $season->id) {
                        $tournament->season_id = $season->id;
                        $tournamentTable->save($tournament);
                        $this->out('SEASON ID UPDATED');
                    }
                    break;
                }
            }

            $this->out('------------------');
        }




    }
}
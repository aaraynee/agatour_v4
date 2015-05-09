<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class TournamentUpdateShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
    }

    public function main()
    {

    }

    public function update()
    {
        $tournamentTable = TableRegistry::get('Tournament');
        $roundTable = TableRegistry::get('Round');
        $tournaments = $tournamentTable
            ->find('all')
            ->contain(
                ['Round' => ['Player']],
                ['Course']
            );
        foreach($tournaments as $tournament){
            $this->out('--------------------------');
            $this->out($tournament->name);
            $this->out('--------------------------');

            $leaderboard = array();
            if(count($tournament->round)) {
                foreach ($tournament->round as $round) {
                    if (!empty($round->adjusted)) {
                        $leaderboard[$round->id] = $round->adjusted;
                    } elseif ($round->total) {
                        $leaderboard[$round->id] = $round->total;
                    }
                }
            }
            asort($leaderboard);

            $position = 0;
            $last_score = - 9999;

            $tie = array_count_values($leaderboard);

            if($tournament->points == 2500){

                $points_array = array(2500,2000,1500,1000,750,500,250,125,100,90,80,70);

            }elseif($tournament->points == 2000){

                $points_array = array(2000,1200,740,540,440,400,360,340,320,300,280,260,240);

            }elseif($tournament->points == 1000){

                $points_array = array(1000,600,380,270,220,200,180, 170,160,150,140,130,120);

            }elseif($tournament->points == 500){

                $points_array = array(500,300,190,135,110,100,90, 85,80,75,70,65,60);

            }elseif($tournament->points == 50){

                $points_array = array(50,30,19,13,11,10,9, 8,7,6,5,4,3);

            }else{
                $points_array = array_fill(0,13,0);
            }


            foreach($leaderboard as $round_id => $score) {
                if($position == 0 || $tie[$last_score] == 1){
                    $position++;
                } elseif($last_score != $score) {
                    $position += $tie[$last_score];
                }

                $round->id = $round_id;
                $round->position = $position;
                $points = 0;
                for($start=$position;$start<$position+$tie[$score]; $start++){
                    $points += $points_array[$start - 1];
                }
                $round->points = ceil($points/ $tie[$score]);
                $roundTable->save($round);
                $last_score = $score;

                $this->out($round->position . '. ' . $score . '(' . $round->points .')');
            }

        }
    }

    public function import($type = 'add')
    {
        $tournament = TableRegistry::get('Tournament');
        $wp_posts = TableRegistry::get('WpPosts');
        $wp_meta = TableRegistry::get('WpPostmeta');

        $wp_tournaments = $wp_posts
            ->find()
            ->where(['post_type' => 'tournament',
                'OR' => [['post_status' => 'publish'], ['post_status' => 'future']],
                ])
            ->order(['post_date' => 'DESC']);

        $wp_metadata = $wp_meta
            ->find();

        $tournaments = $tournament
            ->find()
            ->select(['id']);

        $exist = array();
        foreach($tournaments as $ID => $saved_tournament) {
            $exist[] = $saved_tournament->id;
        }

        foreach ($wp_tournaments as $wp_tournament) {

            foreach($wp_metadata as $metadata) {
                if($metadata->post_id == $wp_tournament->ID) {

                    if($metadata->meta_key == 'course'){
                        $course_id_array = explode('"', $metadata->meta_value);
                        $course_id = $course_id_array[1];
                    }

                    if($metadata->meta_key == 'type'){
                        $tournament_type = $metadata->meta_value;
                    }

                    if($metadata->meta_key == 'points'){
                        $points = $metadata->meta_value;
                    }
                }
            }


            $tournament_details = array(
                'id' => $wp_tournament->ID,
                'name' => $wp_tournament->post_title,
                'slug' => $wp_tournament->post_name,
                'course_id' => $course_id,
                'type' => $tournament_type,
                'points' => $points,
                'completed' => 0,
                'date' => $wp_tournament->post_date,
            );

            $status = NULL;

            if(in_array($wp_tournament->ID, $exist)) {
                if($type == 'update') {
                    $query = $tournament->query();
                    $query->update()
                        ->set($tournament_details)
                        ->where(['id' => $wp_tournament->ID])
                        ->execute();
                    $status = 'updated';
                }
            } else {
                $query = $tournament->query();
                $query->insert(['id', 'name', 'slug', 'course_id', 'type', 'points', 'completed', 'date'])
                    ->values($tournament_details)
                    ->execute();
                $exist[] = $wp_tournament->ID;
                $status = 'added';
            }

            $this->out($wp_tournament->post_title . ' ' . $status);

        }
    }
}
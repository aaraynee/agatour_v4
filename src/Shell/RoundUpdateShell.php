<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class RoundUpdateShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
    }

    public function main($type = 'add')
    {
        $round = TableRegistry::get('Round');
        $wp_posts = TableRegistry::get('WpPosts');
        $wp_meta = TableRegistry::get('WpPostmeta');

        $wp_rounds = $wp_posts
            ->find()
            ->where([
                'post_type' => 'round',
                'post_status' => 'publish']);

        $wp_metadata = $wp_meta
            ->find();

        $rounds = $round
            ->find()
            ->select(['id']);

        $exist = array();
        foreach($rounds as $ID => $saved_round) {
            $exist[] = $saved_round->id;
        }

        foreach ($wp_rounds as $wp_round) {
            foreach($wp_metadata as $metadata) {
                if($metadata->post_id == $wp_round->ID) {
                    if($metadata->meta_key == 'scorecard'){
                        $scorecard = $metadata->meta_value;
                    }

                    if($metadata->meta_key == 'tournament'){
                        $tournament_id_array = explode('"', $metadata->meta_value);
                        $tournament_id = $tournament_id_array[1];
                    }

                    if($metadata->meta_key == 'player'){
                        $player_id_array = explode('"', $metadata->meta_value);
                        $player_id = $player_id_array[1];
                    }
                }
            }

            $round_details = array(
                'id' => $wp_round->ID,
                'player_id' => $player_id,
                'tournament_id' => $tournament_id,
                'scorecard' => $scorecard,
            );

            $status = NULL;

            if(in_array($wp_round->ID, $exist)) {
                if($type == 'update') {
                    $query = $round->query();
                    $query->update()
                        ->set($round_details)
                        ->where(['id' => $wp_round->ID])
                        ->execute();
                    $status = 'updated';
                }
            } else {
                $query = $round->query();
                $query->insert(['id', 'player_id', 'tournament_id', 'scorecard'])
                    ->values($round_details)
                    ->execute();
                $exist[] = $wp_round->ID;
                $status = 'added';
            }
            $this->out($wp_round->ID . ' ' . $status);
        }
    }

    public function scores($process = 'add')
    {
        $roundTable = TableRegistry::get('Round');
        $rounds = $roundTable
            ->find('all')
            ->contain(
                ['Tournament' => [
                        'Course'
                    ]
                ]
            );
        foreach($rounds as $round) {

            if($process == 'update' || ($round->scorecard && $round->status == 0)) {
                $scorecard_array = explode(' ', $round->scorecard);
                if ($round->holes_played == 9) {
                    $par = $round->tournament->course->scorecard('par_front9');
                } else {
                    $par = $round->tournament->course->scorecard('par');
                }
                $round->strokes = array_sum($scorecard_array);
                $round->total = $round->strokes - $par;
                if ($round->holes_played == 9) {
                    $round->handicap = NULL;
                } else {
                    $round->handicap = sprintf("%+.1f", $round->strokes - $round->tournament->course->scratch_rating) * (113 / $round->tournament->course->slope_rating);
                    if ($round->handicap > 40) {
                        $round->handicap = 40;
                    }
                }
                $round->status = 1;
                $this->out($round->tournament->name . ': ' . $round->strokes . '(' . $round->total . ') - ' . $round->handicap);
                $roundTable->save($round);
            } else {
                $this->out($round->id . ' Already processed');
            }
        }
    }
}
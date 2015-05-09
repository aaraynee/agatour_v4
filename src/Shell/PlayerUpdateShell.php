<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class PlayerUpdateShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
    }

    public function main()
    {
    }

    public function update(){

        $playerTable = TableRegistry::get('Player');
        $roundTable = TableRegistry::get('Round');

        $players = $playerTable
            ->find('all')
            ->contain(
                ['Round' => [
                    'Tournament' => [
                        'Course'
                    ]
                ]]
            );

        foreach($players as $player) {
            $this->out('--------------------------');
            $this->out($player->name);
            $this->out('--------------------------');

            foreach($player->round as $round) {
                if($round->holes_played == 18 && $round->status == 1) {
                    $daily_handicap = $player->handicap($round->tournament->compare_date) * ($round->tournament->course->slope_rating/113);
                    $round->adjusted = sprintf("%+d",$round->total - $daily_handicap);
                    $round->status = 2;
                    $this->out($round->adjusted);
                    $roundTable->save($round);
                }
            }
        }
    }

    public function import()
    {
        $player = TableRegistry::get('Player');
        $wp_posts = TableRegistry::get('WpPosts');
        $wp_meta = TableRegistry::get('WpPostmeta');

        $wp_players = $wp_posts
            ->find()
            ->where([
                'post_type' => 'player',
                'post_status' => 'publish']);

        $wp_metadata = $wp_meta
            ->find();

        $players = $player
            ->find()
            ->select(['id']);

        $exist = array();
        foreach($players as $ID => $saved_player) {
            $exist[] = $saved_player->id;
        }

        foreach ($wp_players as $wp_player) {
            foreach($wp_metadata as $metadata) {
                if($metadata->post_id == $wp_player->ID) {
                    if ($metadata->meta_key == 'country') {
                        $country = $metadata->meta_value;
                    }

                    if ($metadata->meta_key == 'height') {
                        $height = $metadata->meta_value;
                    }

                    if ($metadata->meta_key == 'weight') {
                        $weight = $metadata->meta_value;
                    }

                    if ($metadata->meta_key == 'clubs') {
                        $clubs = $metadata->meta_value;
                    }
                }
            }

            $name = explode(" ", $wp_player->post_title);
            $player_details = array(
                'id' => $wp_player->ID,
                'firstname' => $name[0],
                'lastname' => $name[1],
                'slug' => $wp_player->post_name,
                'country' => $country,
                'height' => $height,
                'weight' => $weight,
                'clubs' => $clubs,
                'bio' => $wp_player->post_content
            );
            $status = NULL;
            if(in_array($wp_player->ID, $exist)) {
                $query = $player->query();
                $query->update()
                    ->set($player_details)
                    ->where(['id' => $wp_player->ID])
                    ->execute();
                $status = 'updated';
            } else {
                $query = $player->query();
                $query->insert(['id', 'firstname', 'lastname', 'slug', 'country', 'height', 'weight', 'clubs', 'bio'])
                    ->values($player_details)
                    ->execute();
                $exist[] = $wp_player->ID;
                $status = 'added';
            }
            $this->out($wp_player->post_title . ' ' . $status);
        }
    }
}
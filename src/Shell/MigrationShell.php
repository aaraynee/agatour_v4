<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class MigrationShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
    }


    public function main()
    {

    }

    public function database($object = NULL) {

        if($object) {
            $objects = explode(',', $object);
        } else{
            $objects = ['season', 'tournament', 'course', 'round', 'player'];
        }

        $cake_objects = array();
        $exist = array();

        $wp_posts = TableRegistry::get('WpPosts');

        $wp_objects = $wp_posts
            ->find()
            ->contain(['WpPostmeta'])
            ->where(
                ['OR' => [['post_status' => 'publish'], ['post_status' => 'future']]]
            );

        foreach($objects as $object) {
            $this->out(strtoupper($object));
            $this->out('------------------');
            $table[$object] = TableRegistry::get(ucfirst($object));
            $cake_objects[$object] = $table[$object]
                ->find()
                ->select(['id']);
            foreach ($cake_objects[$object] as $ID => $saved_object) {
                $exist[$object][] = $saved_object->id;
            }

            foreach ($wp_objects as $wp_object) {
                if ($wp_object->post_type == $object && $wp_object->post_status == 'publish' || ($object == 'tournament' && $wp_object->post_status == 'future')) {



                    if($object == 'player') {
                        $name = explode(' ', $wp_object->post_title);
                        $details = array(
                            'id' => $wp_object->ID,
                            'firstname' => $name[0],
                            'lastname' => $name[1],
                            'slug' => $wp_object->post_name,
                        );
                        $post_data = array('id', 'firstname', 'lastname', 'slug', 'country', 'dob', 'height', 'weight', 'pob', 'college', 'clubs', 'glove', 'debut');
                    }elseif($object == 'round') {
                        $details = array(
                            'id' => $wp_object->ID,
                        );
                        $post_data = array('id', 'player_id', 'tournament_id', 'scorecard');
                    }elseif($object == 'season') {
                        $details = array(
                            'id' => $wp_object->ID,
                            'name' => $wp_object->post_title,
                            'slug' => $wp_object->post_name,
                        );
                        $post_data = array('id', 'name', 'slug', 'start_date', 'end_date');
                    }elseif($object == 'tournament') {
                        $details = array(
                            'id' => $wp_object->ID,
                            'name' => $wp_object->post_title,
                            'slug' => $wp_object->post_name,
                        );
                        $post_data = array('id', 'name', 'slug', 'date', 'course_id', 'points');
                    }elseif($object == 'course') {
                        $details = array(
                            'id' => $wp_object->ID,
                            'name' => $wp_object->post_title,
                            'slug' => $wp_object->post_name,
                        );
                        $post_data = array('id', 'name', 'slug', 'scorecard', 'scratch_rating', 'slope_rating');
                    }

                    foreach($wp_object->wp_postmeta as $post_meta){
                        if(substr($post_meta->meta_key, 0, 1 ) !== '_') {

                            if(substr($post_meta->meta_value, -2) === ';}') {
                                $json = explode('"', $post_meta->meta_value);
                                $post_meta->meta_value = $json[1];
                            }

                            if(in_array($post_meta->meta_key, $post_data)) {
                                $details[$post_meta->meta_key] = $post_meta->meta_value;
                            }elseif(in_array($post_meta->meta_key.'_id', $post_data)) {
                                $details[$post_meta->meta_key.'_id'] = $post_meta->meta_value;
                            }
                        }
                    }

                    if (!in_array($wp_object->ID, $exist[$object])) {
                        $query = $table[$object]->query();
                        $query->insert($post_data)
                            ->values($details)
                            ->execute();
                        $exist[$object][] = $wp_object->ID;
                        $status = 'added';
                    }else{
                        $query = $table[$object]->query();
                        $query->update()
                            ->set($details)
                            ->where(['id' => $wp_object->ID])
                            ->execute();
                        $status = 'updated';
                    }
                    $this->out($wp_object->post_title . ' ' . $status);
                }

            }
            $this->out('------------------');
        }
    }
/*

        foreach ($wp_seasons as $wp_season) {

            $season_details = array(
                'id' => $wp_season->ID,
                'name' => $wp_season->post_title,
            );

            foreach($wp_metadata as $metadata) {
                if ($metadata->post_id == $wp_season->ID) {
                    if ($metadata->meta_key == 'start') {
                        $season_details['start_date'] = $metadata->meta_value;
                    }
                    if ($metadata->meta_key == 'end') {
                        $season_details['end_date'] = $metadata->meta_value;
                    }
                }
            }


            $query = $seasonTable->query();

            if(in_array($wp_season->ID, $exist)) {
                $query->update()
                    ->set($season_details)
                    ->where(['id' => $wp_season->ID])
                    ->execute();
            } else {
                $query->insert(['id', 'name', 'start_date', 'end_date'])
                    ->values($season_details)
                    ->execute();
                $exist[] = $wp_season->ID;
            }
        }

        $this->out('SEASON MIGRATION COMPLETE');
    }

    public function player()
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

*/
}
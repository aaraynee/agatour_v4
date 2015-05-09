<?php

namespace App\Shell;

use Cake\Console\Shell;
use Cake\ORM\TableRegistry;

class CourseUpdateShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
    }

    public function main($type = 'add')
    {
        $course = TableRegistry::get('Course');
        $wp_posts = TableRegistry::get('WpPosts');
        $wp_meta = TableRegistry::get('WpPostmeta');

        $wp_courses = $wp_posts
            ->find()
            ->where([
                'post_type' => 'course',
                'post_status' => 'publish']);

        $wp_metadata = $wp_meta
            ->find();

        $courses = $course
            ->find()
            ->select(['id']);

        $exist = array();
        foreach($courses as $ID => $saved_course) {
            $exist[] = $saved_course->id;
        }

        foreach ($wp_courses as $wp_course) {
            $unit = 0;
            foreach($wp_metadata as $metadata) {
                if($metadata->post_id == $wp_course->ID) {
                    if($metadata->meta_key == 'scorecard'){
                        $scorecard = $metadata->meta_value;
                    }

                    if($metadata->meta_key == 'location'){
                        $location = explode('"', $metadata->meta_value);
                    }

                    if($metadata->meta_key == 'slope_rating'){
                        $slope_rating = $metadata->meta_value;
                    }

                    if($metadata->meta_key == 'scratch_rating'){
                        $scratch_rating = $metadata->meta_value;
                    }

                    if($metadata->meta_key == 'unit'){
                        if($metadata->meta_value == 'yards') {
                            $unit = 1;
                        }
                    }
                }
            }

            $course_details = array(
                'id' => $wp_course->ID,
                'name' => $wp_course->post_title,
                'slug' => $wp_course->post_name,
                'scorecard' => $scorecard,
                'address' => $location[3],
                'latitude' => $location[7],
                'longitude' => $location[11],
                'scratch_rating' => $scratch_rating,
                'slope_rating' => $slope_rating,
                'unit' => $unit
            );

            $status = NULL;

            if(in_array($wp_course->ID, $exist)) {
                if($type == 'update') {
                    $query = $course->query();
                    $query->update()
                        ->set($course_details)
                        ->where(['id' => $wp_course->ID])
                        ->execute();
                    $status = 'updated';
                }
            } else {
                $query = $course->query();
                $query->insert(['id', 'name', 'slug', 'scorecard', 'address', 'latitude', 'longitude',  'scratch_rating', 'slope_rating'])
                    ->values($course_details)
                    ->execute();
                $exist[] = $wp_course->ID;
                $status = 'added';
            }
            $this->out($wp_course->post_title . ' ' . $status);
        }
    }
}
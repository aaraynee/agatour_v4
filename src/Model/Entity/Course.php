<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Course extends Entity
{

    protected function _getHoles()
    {
        $scorecard_array = explode('|', $this->_properties['scorecard']);
        return count($scorecard_array);
    }

    public function scorecard($return)
    {

        $scorecard_array = explode('|', $this->_properties['scorecard']);

        $full_scorecard =  $this->_properties['scorecard'];
        if(count($scorecard_array) == 9) {
            $full_scorecard = $this->_properties['scorecard'] . '|' . $this->_properties['scorecard'];
        }
        $scorecard_array = explode('|', $full_scorecard);
        $scorecard = array();
        $i = 1;
        $par = 0;
        $par_front9 = 0;
        $par_back9 = 0;
        $distance = 0;

        foreach($scorecard_array as $hole){
            $hole_details = explode(' ', $hole);
            $scorecard['distance'][$i] = $hole_details[0];
            $scorecard['hole'][$i] = $hole_details[1];
            $distance += $hole_details[0];
            $par += $hole_details[1];
            if($i == 9){
                $par_front9 = $par;
            } elseif($i == 18){
                $par_back9 = $par - $par_front9;
            }
            $i++;
        }

        $scorecard['par_front9'] = $par_front9;
        $scorecard['par_back9'] = $par_back9;
        $scorecard['par'] = $par;

        return $scorecard[$return];
    }
}
<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Round extends Entity
{

    protected function _getScore() {
        if(!isset($this->_properties['adjusted'])) {
            $adjusted = sprintf('%+d', $this->_properties['total']);
        }elseif($this->_properties['adjusted'] == 0 || $this->_properties['total'] == 0) {
            $adjusted = 'E';
        } else{
            $adjusted = sprintf('%+d', $this->_properties['adjusted']);
        }
        return $adjusted;
    }

    protected function _getHolesPlayed() {
        $scorecard_array = explode(' ', $this->_properties['scorecard']);
        return count($scorecard_array);
    }

    public function score($holes) {
        $scorecard_array = explode(' ', $this->_properties['scorecard']);
        $par = $this->_properties['tournament']->_properties['course']->scorecard('all');
        $front_9 = $back_9 = 0;
        $hole = 1;
        $status = array();

        foreach($scorecard_array as $score) {
            $scorecard[$hole] = $score;
            if($hole < 10) {
                $front_9 += $score;
            } elseif($hole > 9) {
                $back_9 += $score;
            }
            if($hole == 1 || $hole == 10){
                $status[$hole] = ($par[$hole] - $score);
            }else{
                $status[$hole] = $status[$hole-1] + ($par[$hole] - $score);
            }

            if($status[$hole] == 0){
                $status[$hole] = 'E';
            }
            $hole++;
        }
        $scorecard['status'] = $status;
        $scorecard['all'] = $scorecard;
        $scorecard['front9'] = $front_9;
        $scorecard['back9'] = $back_9;
        return $scorecard[$holes];
    }
}
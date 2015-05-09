<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Round extends Entity
{
/*
    protected function _getAdjusted() {
        if($this->_properties['adjusted'] == 0) {
            $adjusted = 'E';
        } else{
            $adjusted = sprintf('%+d', $this->_properties['adjusted']);
        }
        return $adjusted;
    }

    protected function _getTotal() {
        if($this->_properties['total'] == 0) {
            $adjusted = 'E';
        } else{
            $adjusted = sprintf('%+d', $this->_properties['total']);
        }
        return $adjusted;
    }
*/

    protected function _getHolesPlayed() {
        $scorecard_array = explode(' ', $this->_properties['scorecard']);
        return count($scorecard_array);
    }

    public function score($holes) {
        $scorecard_array = explode(' ', $this->_properties['scorecard']);
        $front_9 = $back_9 = 0;
        $hole = 1;
        foreach($scorecard_array as $score) {
            if($hole < 10) {
                $front_9 += $score;
            } elseif($hole > 9) {
                $back_9 += $score;
            }
            $hole++;
        }
        $scorecard['front_9'] = $front_9;
        $scorecard['back_9'] = $back_9;
        return $scorecard[$holes];
    }
}
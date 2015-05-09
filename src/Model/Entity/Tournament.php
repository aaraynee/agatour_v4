<?php

namespace App\Model\Entity;

use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;


class Tournament extends Entity
{
    protected function _getDisplayDate() {
        return date('d-M', strtotime($this->_properties['date']));
    }

    protected function _getWinner() {
        $winner = array();
        foreach($this->_properties['round'] as $round){
            if($round->position == 1){
                $player = TableRegistry::get('Player')->find()->where(['Player.id' => $round->player_id])->first();
                $winner[] = $player->name;
            }
        }
        return implode(', ', $winner);
    }

    protected function _getScore() {
        $score = '';
        foreach($this->_properties['round'] as $round){
            if($round->position == 1){
                $player = TableRegistry::get('Player')->find()->where(['Player.id' => $round->player_id])->first();
                if(!empty($round->adjusted)) {
                    $score = $round->adjusted;
                } else {
                    $score = $round->total;
                }
                break;
            }
        }
        return $score;
    }

    protected function _getCompareDate() {
        return date('Y-m-d', strtotime($this->_properties['date']));
    }

}
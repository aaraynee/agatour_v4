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
                $winner[] = $round->player->firstname . ' ' . $round->player->lastname;
            }
        }
        return implode(', ', $winner);
    }

    protected function _getCompareDate() {
        return date('Y-m-d', strtotime($this->_properties['date']));
    }

}
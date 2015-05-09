<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class Player extends Entity
{
    protected function _getName()
    {
        return $this->_properties['firstname'] . '  ' .
        $this->_properties['lastname'];
    }

    protected function _getFullname()
    {
        return $this->_properties['lastname'] . ', ' . $this->_properties['firstname'];
    }

    protected function _getFlag()
    {
        return '/img/flags/'.$this->_properties['country'].'.png';
    }

    public function rounds_played($date = NULL)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }

        $rounds_played = 0;

        $rounds = TableRegistry::get('Round')->find()->where(['Round.player_id' => $this->_properties['id']])->contain(['Tournament']);

        foreach ($rounds as $round) {
            if ($round->holes_played == 18 && $round->tournament->compare_date < $date) {
                $rounds_played++;
            }
        }

        return $rounds_played;
    }

    public function handicap($date = NULL)
    {
        if(!$date) {
            $date = date('Y-m-d', time());
        }

        $handicap_rounds = array();

        $rounds = TableRegistry::get('Round')->find()->where(['Round.player_id' => $this->_properties['id']])->contain(['Tournament']);

        foreach($rounds as $round) {
            if($round->holes_played == 18 && $round->tournament->compare_date < $date)
                $handicap_rounds[$round->id] = $round->tournament->compare_date;
                $handicap_scores[$round->id] = $round->handicap;
        }
        arsort($handicap_rounds);
        $sorted_round = array();
        $i = 0;
        foreach($handicap_rounds as $round_id => $round_date) {
            $sorted_round[] = $handicap_scores[$round_id];
            $i++;
            if($i >= 20) {
                break;
            }
        }
        $last_20 = array_slice($sorted_round, 0, 20);
        asort($last_20);

        $rounds_played = $this->rounds_played($date);
        $rounds_to_count = 0;
        $handicap = 36;

        if($rounds_played > 2 && $rounds_played < 7) {
            $rounds_to_count = 1;
        } elseif($rounds_played < 9) {
            $rounds_to_count = 2;
        } elseif($rounds_played < 11) {
            $rounds_to_count = 3;
        } elseif($rounds_played < 13) {
            $rounds_to_count = 4;
        } elseif($rounds_played < 15) {
            $rounds_to_count = 5;
        } elseif($rounds_played < 17) {
            $rounds_to_count = 6;
        } elseif($rounds_played < 19) {
            $rounds_to_count = 7;
        } elseif($rounds_played >= 19) {
            $rounds_to_count = 8;
        }

        $rounds = array_slice($last_20, 0, $rounds_to_count);
        if($rounds_played > 2) {
            $handicap = array_sum($rounds) / $rounds_to_count * 0.93;
            if($handicap > 36.4) {
                $handicap = 36.4;
            }
        }
        return sprintf("%.1f", $handicap);
    }
}
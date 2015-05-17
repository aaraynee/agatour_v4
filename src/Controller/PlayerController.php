<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;


class PlayerController extends AppController
{

    public function initialize()
    {
        $this->loadModel('Player');
        $this->loadModel('Season');
    }

    public function single($slug) {

        $players = $this->Player->find()
            ->contain(
                ['Round' =>
                    ['Tournament' =>
                        ['Course', 'Season'],]]
            )
            ->where(
                ['Player.slug' => $slug]
            );

        $player = $players->first();


        $seasons = $this->Season->find()
            ->order(
                ['Season.start_date DESC']
            );

        $seasonsArray = array();
        foreach($seasons as $season){
            $seasonsArray[$season->id] = $season->name;
        }
        $roundsArray = array();
        foreach($player->round as $round){
            $roundsArray[$round->tournament->season->id][$round->id] = date('d-M', strtotime($round->tournament->date)) . ' ' . $round->tournament->name;
        }

        $this->set('player', $player);
        $this->set('seasons', $seasonsArray);
        $this->set('rounds', $roundsArray);
    }

    public function all()
    {
        $players = $this->Player->find()->order(['Player.lastname' => 'ASC']);
        $this->set('players', $players);
    }
}

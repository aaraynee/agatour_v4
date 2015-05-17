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


class TournamentController extends AppController
{

    public function initialize()
    {
        $this->loadModel('Tournament');
    }

    public function single($slug) {

        $tournaments = $this->Tournament->find()
            ->where(['Tournament.slug' => $slug])
            ->contain(
                ['Round' =>
                    ['Player']
                ]
            );
        $tournament = $tournaments->first();
        $this->set('tournament', $tournament);
    }

    public function schedule()
    {
        $tournaments = $this->Tournament->find()
        ->contain(['Course' => [
            'fields' => [
                'Course.name'
            ]
        ],
            'Round' => [
                'Player' => [
                  'fields' => [
                      'Player.firstname',
                      'Player.lastname',
                  ]
                ],
                'conditions' => [
                    'Round.position' => 1
                ],
                'fields' => [
                    'Round.tournament_id',
                    'Round.player_id',
                    'Round.adjusted',
                    'Round.position',
                ]
            ]
        ])
            ->order(['Tournament.date DESC']);
        $this->set('tournaments', $tournaments);

    }
}

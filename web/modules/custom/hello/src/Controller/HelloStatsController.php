<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;
use Drupal\Core\Database\Database;

class HelloStatsController extends ControllerBase {

    /**
     * @param UserInterface $user
     * @return array
     */
    public function history_stats(UserInterface $user) {
   $database = \Drupal::database();
   $query = $database->select('hello_user_statistics', 'hus')
                     ->fields('hus', ['action', 'time'])
                     ->condition('uid', $user->id());
   $result = $query->execute();

   $rows = [];
   $connexions = 0;
   foreach ($result as $record) {
     $rows[] = [
       $record->action == '1' ? $this->t('Login') : $this->t('logout'),
       \Drupal::service('date.formatter')->format($record->time),
     ];
     $connexions += $record->action;
   }
   $table = [
     '#type' => 'table',
     '#header' => [$this->t('Action'), $this->t('Time')],
     '#rows' => $rows,
     '#empty' => $this->t('No connection yet.'),
   ];

   $output = [
       '#theme' => 'hello_user_connexion',
       '#count' => $connexions,
       '#user' => $user,

   ];

    return [
        'output' => $output,
        'table' => $table,
        '#cache' => [
            'max-age' => '0',
            ],
        ];
  }
}

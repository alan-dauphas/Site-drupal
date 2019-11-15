<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a hello block.
 *
 * @Block(
 * id = "session_block",
 * admin_label = @Translation("SessionBlock!")
 * )
 */
class SessionBlock extends BlockBase {

  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
   public function build() {
     $database = \Drupal::database();
     $session = $database->select('sessions', 's')
                          ->countQuery()
                          ->execute()
                          ->fetchField();

     return [
       '#markup' => $this->t('Il y a actuellement %session sessions actives', [
         '%session' => $session,
       ]),
       '#cache' => [
         'max-age' => '0',
       ],
     ];
   }

   protected function blockAccess(AccountInterface $account)
   {
       return AccessResult::allowedIfHasPermission($account, 'access hello');
   }
}

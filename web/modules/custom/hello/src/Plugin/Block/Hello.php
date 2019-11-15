<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a hello block.
 *
 * @Block(
 * id = "hello_block",
 * admin_label = @Translation("Hello!")
 * )
 */
class Hello extends BlockBase {

  /**
   * Implements Drupal\Core\Block\BlockBase::build().
   */
  public function build() {
    $date_formatter = \Drupal::service('date.formatter');
    $time = \Drupal::service('datetime.time')->getCurrentTime();
    $user_name = \Drupal::currentUser()->getDisplayName();
    return [
      '#markup' => $this->t('Welcome %name. It is %time.', [
        '%name' => $user_name,
        '%time' => $date_formatter->format($time, 'custom', 'H:i s\s'),
      ]),
      '#cache' => [
        'max-age' => '0',
      ],
    ];
  }
}

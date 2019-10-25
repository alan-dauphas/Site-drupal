<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase {

  public function content() {
    $message = $this->t('You are on the HELLO page. Your Name is @username', [ '@username' => $this->currentUser()->getDisplayName(),
    ]);
    return ['#markup' => $message];
  }
}

<?php

namespace Drupal\email_form\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\reusable_forms\Form\ReusableFormBase;


/**
 * Defines the EmailForm class.
 */
class EmailForm extends ReusableFormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'email_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Enter your email adress'),
    );

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//  $node = \Drupal::service('current_route_match')->getParameter('node'); Same function as the next line
    $node = \Drupal::routeMatch()->getParameter('node');
    ksm($node);
    \Drupal::database()->insert('email_form_node_subscriber')->fields([
      'nid' => $node->id(),
      'email' => $form_state->getValue('email'),
    ])->execute();
    \Drupal::messenger()->addMessage('Email enregistré: ' . $form_state->getValue('email'));

  }

}

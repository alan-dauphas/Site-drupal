<?php

namespace Drupal\date_condition\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Date' condition to enable a condition based in module selected status.
 *
 * @Condition(
 *   id = "date_condition",
 *   label = @Translation("Date")
 * )
 *
 */
class DateCondition extends ConditionPluginBase
{

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {

    $form['start_date'] = [
      '#type' => 'date',
      '#title' => $this->t('Start date'),
      '#default_value' => $this->configuration['start_date'],
    ];

    $form['end_date'] = [
      '#type' => 'date',
      '#title' => $this->t('End date'),
      '#default_value' => $this->configuration['end_date'],
    ];

    return $form;/*j'aime Xavier*/
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    $this->configuration['start_date'] = $form_state->getValue('start_date');
    $this->configuration['end_date'] = $form_state->getValue('end_date');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [
        'start_date' => '',
        'end_date' => '',
      ] + parent::defaultConfiguration();
  }

  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    if (!empty($form_state->getValue('start_date')) && !empty($form_state->getValue('end_date'))){
      $start = new DrupalDateTime($form_state->getValue('start_date'));
      $end = new DrupalDateTime($form_state->getValue('end_date'));
      if ($end < $start) {
        $form_state->setErrorByName('end date', $this->t('End date Error!'));
      }
    }
  }


  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate()
  {
    $today = new DrupalDateTime('today');
    $start = $this->configuration['start_date'] ? new DrupalDateTime($this->configuration['start_date']) : NULL;
    $end = $this->configuration['end_date'] ? new DrupalDateTime($this->configuration['end_date']) : NULL;

    return (!$start || ($start <= $today)) && (!$end || ($end >= $today));
  }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary()
  {
    $module = $this->getContextValue('module');
    $modules = system_rebuild_module_data();

    $status = ($modules[$module]->status) ? t('enabled') : t('disabled');

    return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
  }
}


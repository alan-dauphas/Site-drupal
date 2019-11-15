<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class HelloForm
 * @package Drupal\hello\Form
 */

class HelloForm extends FormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormID() {
        return 'hello_form';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // Champ destiné à afficher le résultat du calcul.

        if(isset($form_state->getRebuildInfo()['result'])){
            $form['result'] = [
                '#type' => 'html_tag',
                '#tag' => 'h2',
                '#value' => $this->t('Result: ') . $form_state->getRebuildInfo()['result'],
            ];

        }

        $form['value1'] = [
            '#type' => 'textfield',
            '#title' => $this
                ->t('First value'),
            '#maxlength' => 20,
            '#required' => TRUE,
            '#description' => $this->t('Enter first value'),
            '#ajax' => [
                'callback' => [$this, 'validateTextAjax'],
                'event' => 'keyup',
            ],
            '#prefix' => '<span class="error-message-value1"></span>',
    ];

        $form['operation'] = array(
            '#type' => 'radios',
            '#title' => $this
                ->t('Operation'),
            '#default_value' => 'addition',
            '#options' => array(
                'addition' => $this->t('Add'),
                'soustraction' => $this->t('Soustract'),
                'multiplication' => $this->t('Multiply'),
                'division' => $this->t('Divide'),
            ),
            '#description' => $this->t('Choose operation for processing.'),
        );

        $form['value2'] = array(
            '#type' => 'textfield',
            '#title' => $this
                ->t('Second Value'),
            '#maxlength' => 20,
            '#description' => $this->t('Enter second value'),
            '#required' => TRUE,
            '#ajax' => [
                'callback' => [$this, 'validateTextAjax'],
                'event' => 'keyup',
                ],
            '#prefix' => '<span class="error-message-value2"></span>',
        );


        $form['submit'] = array(
            '#type' => 'submit',
            '#value' => $this
                ->t('Calculate'),
        );

        return $form;
    }

    /**
     * {@inheritdoc}.
     */
    public function validateForm(array &$form, FormStateInterface $form_state){

        //verification si les champs sont bien des valeurs numérique
        $value1 = $form_state->getValue('value1');
        if(!is_numeric($value1) ){
            $form_state->setErrorByName('value1', $this->t('Value 1 must be numeric!'));
        }

        $value2 = $form_state->getValue('value2');
        if(!is_numeric($value2)){
            $form_state->setErrorByName('value2', $this->t('Value 1 must be numeric!'));
        }

        $operation = $form_state->getValue('operation');
        if ($value2 == '0' && $operation == 'division'){
            $form_state->setErrorByName('value2', $this->t('Cannot divide by zero'));
        }
    }

    /**
     * {@inheritdoc}.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Récupère la valeur des champs
        $value1 = $form_state->getValue('value1');
        $operation = $form_state->getValue('operation');
        $value2 = $form_state->getValue('value2');

        // Initialise la valeur qui aura pour résultat l'operation.
        $result = '';

        switch ($operation) {
            case 'addition':
                $result = $value1 + $value2;
                break;
            case 'soustraction':
                $result = $value1 - $value2;
                break;
            case 'multiplication':
                $result = $value1 * $value2;
                break;
            case 'division':
                $result = $value1 / $value2;
                break;
        }
        // On passe le resultat obtenu
        $form_state->addRebuildInfo('result', $result);
        // Recontruction du formulaire avec les valeurs saisies.
        $form_state->setRebuild();
    }

    /**
     * {@inheritdoc}.
     */
    public function validateTextAjax(array &$form, FormStateInterface $form_state){
        $response = new AjaxResponse();
        $field = $form_state->getTriggeringElement()['#name'];
        if (is_numeric($form_state->getValue($field))) {
            $css = ['border' => '2px solid green'];
            $message = $this->t('OK!');
        } else {
            $css = ['border' => '2px solid red'];
            $message = $this->t('%field must be numeric!', ['%field' => $form[$field]['#title']]);
        }

        $response->AddCommand(new CssCommand("[name=$field]", $css));
        $response->AddCommand(new HtmlCommand('.error-message-' . $field, $message));

        return $response;
    }

}

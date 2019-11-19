<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {

    $data = parent::getViewsData();

    $data['annonce_user_views']['table']['group'] = $this->t('Annonce history');
    $data['annonce_user_views']['table']['provider'] = 'annonce';
    $data['annonce_user_views']['table']['base'] = [
      // Identifier (primary) field in this table for Views.
      'field' => 'id',
      // Label in the UI.
      'title' => $this->t('annonce history'),
      // Longer description in the UI. Required.
      'help' => $this->t('annonce history contains historical datas and can be related to annonces.'),
      // place in the select
      'weight' => 200,
      ];

    $data['annonce_user_views']['uid'] = [
      'title' => $this->t('Annonce view user ID'),
      'help' => $this->t('Annonce view user ID.'),
      'field' => ['id' => 'numeric'],
      'sort'  => ['id' => 'standard'],
      'filter'  => ['id' => 'numeric'],
      'argument'  => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => $this->t('annonce history UID -> User ID'),
      ],
    ];

    $data['annonce_user_views']['aid'] = [
      'title' => $this->t('Annonce AID'),
      'help' => $this->t('Annonce content AID.'),
      'field' => ['id' => 'numeric'],
      'sort'  => ['id' => 'standard'],
      'filter'  => ['id' => 'numeric'],
      'argument'  => ['id' => 'numeric'],
      'relationship' => [
        'base' => 'annonce_field_data',
        'base field' => 'id',
        'id' => 'standard',
        'label' => $this->t('Annonce history UID -> number AID'),
      ],
    ];

    $data['annonce_user_views']['time'] = [
      'title' => $this->t('Time view'),
      'help' => $this->t('Time view.'),
      'field' => ['id' => 'date'],
      'sort'  => ['id' => 'date'],
      'filter'  => ['id' => 'date'],
    ];

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}

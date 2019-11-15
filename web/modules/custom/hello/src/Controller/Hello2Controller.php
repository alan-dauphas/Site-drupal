<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

class Hello2Controller extends ControllerBase {

   public function list_nodes($nodetype = NULL) {
     // Affichage des types de contenu
     $node_types = $this->entityTypeManager()->getStorage('node_type')->loadMultiple();
     // ksm($nodes_types);

     $node_type_items = [];
     foreach ($node_types as $node_type) {
       $url = new Url('hello2.hello', ['nodetype' => $node_type->id()]);
       $node_type_link = new Link($node_type->label(), $url);
       $node_type_items[] = $node_type_link;
     }

     $node_type_list = [
       '#theme' => 'item_list',
       '#items' => $node_type_items,
       '#title' => $this->t('Mon Super Mega Giga Ultra Supra Filtre'),
     ];


     // permet de manipuler les noeuds
     $nodes_storage = $this->entityTypeManager()->getStorage('node');
     // permet de faire des requetes sur les noeuds.
     $query = $nodes_storage->getQuery();
     // filtre s'il y a un argument dans l'URL. voir controller {nodetype}
     if ($nodetype){
       $query->condition('type', $nodetype);
     }
     // recupere les ids des noeuds pager fait la pagination
     $nids = $query->pager(5)->execute();
     // Récupère les noeuds.
     $nodes = $nodes_storage->loadMultiple($nids);

     // Faire des requêtes pour le type d’entité TYPE.
     $items = [];
     foreach ($nodes as $node) {
       $items[] = $node->toLink();
     }

     $list = [
       '#theme' => 'item_list',
       '#items' => $items,
     ];

     $pager = [
       '#type' => 'pager'
     ];

     return [
       'node_type_list' => $node_type_list,
       'pager' => $pager,
       'list' => $list,
       'pager2' => $pager,
       '#cache' => [ 'max-age' => '0']
     ];

   }
  }

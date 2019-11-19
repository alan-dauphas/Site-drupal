<?php


namespace Drupal\annonce\EventSubscriber;


use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;


class AnnonceEventSubscriber implements EventSubscriberInterface {

  protected $currentUser;
  protected $currentRouteMatch;
  protected $time;
  protected $database;

  public function __construct(AccountProxyInterface $current_user,
                              CurrentRouteMatch $current_route_match,
                              Connection $database,
                              TimeInterface $time)  {
    $this->currentUser = $current_user;
    $this->currentRouteMatch = $current_route_match;
    $this->time = $time;
    $this->database = $database;
  }

  static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onRequest'];
    return $events;
  }

  public function onRequest(Event $event){
    if ($this->currentRouteMatch->getRouteName() == 'entity.annonce.canonical') {
      $annonce = $this->currentRouteMatch->getParameter('annonce');
      $this->database->insert('annonce_user_views')
        ->fields([
          'uid' => $this->currentUser->id(),
          'time' => $this->time->getRequestTime(),
          'aid' => $annonce->id(),
        ])
        ->execute();
    }
  }
}




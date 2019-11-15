<?php

namespace Drupal\hello\Routing;


use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class HelloRouteSubscriber extends RouteSubscriberBase
{
    protected function alterRoutes(RouteCollection $collection)
    {
        // TODO: Implement alterRoutes() method.
       $collection->get('entity.user.canonical')->setRequirements(['_access_hello' => '10']);
    }
}
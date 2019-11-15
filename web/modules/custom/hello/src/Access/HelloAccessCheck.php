<?php

namespace Drupal\hello\Access;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Access\AccessResult;

class HelloAccessCheck implements AccessCheckInterface
{
    /** @var \Drupal\Component\Datetime\TimeInterface */
    protected $time;

    /** HelloAccessCheck constructor */
    function __construct(TimeInterface $time)
    {
        $this->time = $time;
    }

    public function applies(Route $route)
    {
        // TODO: Implement applies() method.
    }
    public function access(Route $route, Request $request = NULL, AccountInterface $account)
    {
        $param = $route->getRequirement('_access_hello');

        if (!$account->isAnonymous()) {
            $timeCreated = $account->getAccount()->created + ($param * 3600);
            if ($this->time->getCurrentTime() > $timeCreated) {
                return AccessResult::allowed()->cachePerUser();
            }
            else {
                return AccessResult::forbidden()->cachePerUser();
            }
        }
        else
        {
            return AccessResult::forbidden()->cachePerUser();
        }

    }
}
<?php

namespace aprendePHP\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\Response;

class StringResponse implements EventSubscriberInterface{

  public function onView(GetResponseForControllerResultEvent $event){
    $response = $event->getControllerResult();
    if (is_string($response)) {
      $event->setResponse(new Response($response));
    }
  }

  /**
   * Subscriber
   * @return array kernel event subscriber
   */
  public static function getSubscribedEvents(){
    return array('kernel.view' => 'onView');
  }

}

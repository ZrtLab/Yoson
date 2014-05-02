<?php

namespace Yoson\Event;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class Listener implements ListenerAggregateInterface
{

    const LOG_ERROR = 'log.error';
    const LOG_INFO = 'log.info';

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(self::LOG_ERROR,
            array($this, 'onLogError'));
        $this->listeners[] = $events->attach(self::LOG_INFO,
            array($this, 'onLogInfo'));
    }

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onLogError($e)
    {
        $app = $e->getParam('application');
        $serviceManager = $app->getServiceManager();
        $serviceManager->get('logger')->logInfo($e->getParam);
    }

    public function onLogInfo($e)
    {
        $app = $e->getParam('application');
        $serviceManager = $app->getServiceManager();
        $serviceManager->get('logger')->logInfo($e);
    }

}

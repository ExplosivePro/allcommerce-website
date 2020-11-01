<?php

namespace AllCommerce\Projectors\Clients;


use AllCommerce\Events\Clients\NewMenuOptionsSet;
use AllCommerce\MenuOptions;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class OnBoardProjector extends Projector
{
    protected $menu_options;

    public function __construct(MenuOptions $options)
    {
        $this->menu_options = $options;
    }

    public function onNewMenuOptionsSet(NewMenuOptionsSet $event)
    {
        $this->menu_options->getOrCreate('switch', $event->getClient());
    }
}

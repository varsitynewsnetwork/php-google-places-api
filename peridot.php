<?php

use Evenement\EventEmitterInterface;
use Peridot\Plugin\Prophecy\ProphecyPlugin;
use Peridot\Plugin\Watcher\WatcherPlugin;

return function (EventEmitterInterface $eventEmitter) {
    $eventEmitter->on('peridot.start', function (\Peridot\Console\Environment $environment) {
        $environment->getDefinition()->getArgument('path')->setDefault('specs');
    });

    new ProphecyPlugin($eventEmitter);
    new WatcherPlugin($eventEmitter);
};

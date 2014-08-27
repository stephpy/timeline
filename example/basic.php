<?php

require __DIR__."/../vendor/autoload.php";

$redis = new \Redis();
$redis->connect('127.0.0.1');

$serviceLocator = new \Spy\Timeline\ServiceLocator();
$serviceLocator->addRedisDriver($redis);

$c = $serviceLocator->getContainer();
$c['spread.deployer']->addNotifier($c['unread_notifications']);

// define spreads

// Push an action

$actionManager = $c['action_manager'];
$chuck = $actionManager->findOrCreateComponent('User', 'Chuck');
$bruceLee = $actionManager->findOrCreateComponent('User', 'BruceLee');

$action = $actionManager->create($chuck, 'kick', array('directComplement' => $bruceLee));
$actionManager->updateAction($action);

// Pull a timeline of a subject

$actionManager = $c['action_manager'];
$timelineManager = $c['timeline_manager'];
$chuck = $actionManager->findOrCreateComponent('User', 'Chuck');

$timeline = $timelineManager->getTimeline($chuck);

print sprintf("---- Timeline Results = (%s) -----\n", count($timeline));

foreach ($timeline as $action) {
    $subject = $action->getSubject();
    $directComplement = $action->getComponent('directComplement');
    //.....
}

// Pull actions of a subject

$actionManager = $c['action_manager'];
$chuck = $actionManager->findOrCreateComponent('User', 'Chuck');

$actions = $actionManager->getSubjectActions($chuck);

print sprintf("---- Actions Results = (%s) -----\n", count($actions));

foreach ($actions as $action) {
    $subject          = $action->getSubject();
    $directComplement = $action->getComponent('directComplement');
    //.....
}

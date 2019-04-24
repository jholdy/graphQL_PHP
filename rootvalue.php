<?php
interface Resolver {
    public function resolve($root, $args, $context);
}
class Addition implements Resolver
{
    public function resolve($root, $args, $context)
    {
        if ($context['auth']) {
          return $args['x'] + $args['y'];
        } else {
          throw GraphQL\Error\FormattedError::setInternalErrorMessage("Not permission!");
        }
        
    }
}
class Echoer implements Resolver
{
    public function resolve($root, $args, $context)
    {
        if ($context['auth']) {
          return $root['prefix'].$args['message'];
        } else {
          throw GraphQL\Error\FormattedError::setInternalErrorMessage("Not permission!");
        }
        
    }
}
return [
    'sum' => function($root, $args, $context) {
        $sum = new Addition();
        return $sum->resolve($root, $args, $context);
    },
    'echo' => function($root, $args, $context) {
        $echo = new Echoer();
        return $echo->resolve($root, $args, $context);
    },
    'prefix' => 'You said: ',
];
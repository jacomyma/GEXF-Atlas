<?php
class helloRouter extends ezcMvcRouter
{
    public function createRoutes()
    {
        return array(
            new ezcMvcRailsRoute( '/:name', 'helloController', 'greetPersonally' ),
            new ezcMvcRailsRoute( '/', 'helloController', 'greet' ),
        );
    }
}
?>

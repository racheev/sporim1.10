<?php
        function routes_sporim(){

               $routes[] = array(
                            '_uri'  => '/^sporim\/(.*)\/(.*)$/i',
                            1       => 'action',
                   			2       => 'zapros'
                         );
 			    $routes[] = array(
                            '_uri'  => '/^sporim\/(.*)$/i',
                            1       => 'action'
                         );
        return $routes;

    }
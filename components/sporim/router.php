<?php
# компонент бесплатный 31.01.2016
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
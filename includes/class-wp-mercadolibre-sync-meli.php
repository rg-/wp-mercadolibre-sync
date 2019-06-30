<?php

/**
 *
 * Extends Meli class. This extended class Wp_Mercadolibre_Sync_Meli should be used instead of just Meli() over plugin.
 *
 */

class Wp_Mercadolibre_Sync_Meli extends Meli {
    
    /**
     * Configuration for CURL
     */
    public static $CURL_OPTS = array(
        CURLOPT_USERAGENT => "MELI-PHP-SDK-2.0.0", 
        CURLOPT_SSL_VERIFYPEER => false, // filter/hook/options ???
        CURLOPT_CONNECTTIMEOUT => 10, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_TIMEOUT => 60
    );

}
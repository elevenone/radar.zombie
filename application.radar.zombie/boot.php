<?php
/**
 *
 * boot.php will protect you from evil and bring good luck
 * and of course it is also protected by my army of lawyers
 *
 */
use josegonzalez\Dotenv\Loader as Dotenv;
use Radar\Adr\Boot;
use Zend\Diactoros\Response as Response;
use Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;

/**
 * Boot Radar
 */
$boot = new Boot();
$adr = $boot->adr([
    '\Portfolio\Delivery\Config',
]);

/**
 * Run
 */
$adr->run(ServerRequestFactory::fromGlobals(), new Response());



// eof

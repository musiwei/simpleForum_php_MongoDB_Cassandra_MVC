<?php

include_once("controller/FrontController.php");

interface FrontControllerInterface
{
	public function __construct(array $options);
    public function setController($controller);
    public function setAction($action);
    public function setParams(array $params);
    public function run();
}

?>
<?php

class ViewHelper
{
    private $data=array();

    public function assign($key,$value){
        $this->data[$key]=$value;
    }
    public function display($directory, $view){
        extract($this->data);
        include(__DIR__.'/./views/'.$directory.'/'.$view.'.php');
    }
}
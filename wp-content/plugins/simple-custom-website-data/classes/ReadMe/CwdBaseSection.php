<?php

abstract class CwdBaseSection{

    public function md($varName)
    {
        if(!property_exists($this, $varName))
        {
            throw new \Exception("Property name provided doesn't exist");
        }
        return CwdSlimdown::render(htmlspecialchars_decode($this->{$varName}));
    }

}
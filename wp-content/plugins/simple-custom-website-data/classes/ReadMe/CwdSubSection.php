<?php

class CwdSubSection extends CwdBaseSection{

    public function __construct(array $data)
    {
        foreach($data as $key => $value)
        {
            $this->{$key} = $value;
        }
    }

}
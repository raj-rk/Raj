<?php

class CwdSection extends CwdBaseSection{

    public function getSubSection($subSection)
    {
        preg_match_all("/= (" . $subSection . ") =([\s\S]*?)(?=(?:=)|\z)/", $this->raw, $rawSubSections, PREG_SET_ORDER);

        if(count($rawSubSections) == 0)
        {
            return $this;
        }
        $rawSubSection = $rawSubSections[0];
        $data = array();
        $data['title'] = htmlentities($rawSubSection[1]);
        $data['content'] = htmlentities(trim($rawSubSection[2]));
        $subSection = new CwdSubSection($data);

        return $subSection;
    }
}
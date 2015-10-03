<?php

class CwdReadMe{

    public $sections = array();

    public function init($readMePath = null)
    {
        if(empty($readMePath))
        {
            $this->readMePath = CWD_ROOT . 'readme.txt';
        }
        else
        {
            $this->readMePath = $readMePath;
        }

        if(!$this->readMeRaw = @file_get_contents($this->readMePath))
        {
            throw new \Exception('Readme file not found: "' . $this->readMePath .'"');
        }

        require 'ReadMe/CwdBaseSection.php';
        require 'ReadMe/CwdSection.php';
        require 'ReadMe/CwdSubSection.php';
        require 'Helpers/CwdMarkdown.php';

        return $this;
    }

    public function allSections()
    {
        $this->separate();
        return $this->sections;
    }

    public function getSection($heading)
    {

        $rawSections = $this->findSections($this->readMeRaw, $heading);

        if(count($rawSections) == 0)
        {
            return false;
        }
        $rawSection = $rawSections[0];
        $section = new CwdSection;
        $section->title = strtolower(preg_replace("/\s/", '_' , $rawSection[1]));
        $section->raw = htmlentities(trim($rawSection[2]));
        return $section;
    }

    private function separate()
    {

        $rawSections = $this->findSections($this->readMeRaw);

        foreach($rawSections as $rawSection)
        {
            $section = new CwdSection;
            $title = $this->headingKey($rawSection[1]);
            $section->title = htmlentities($title);
            $section->raw = htmlentities(trim($rawSection[2]));

            $rawSubSections = $this->findSubSections($section->raw);

            $subTitles = array();

            foreach($rawSubSections as $rawSubSection)
            {
                $sub = array();
                $sub['title'] = htmlentities($rawSubSection[1]);
                $sub['content'] = htmlentities(trim($rawSubSection[2]));

                $subTitles[$rawSubSection[1]] = new CwdSubSection($sub);
            }

            $section->subtitles = $subTitles;

            $this->sections[$title] = $section;
        }
    }

    private function headingKey($heading)
    {
        return strtolower(preg_replace("/\s/", '_' , $heading));
    }

    private function findSections($text, $section = "[\w\s]*")
    {
        preg_match_all("/^== (" . $section . ") ==([\s\S]*?)(?=(?:==)|\z)/m", $text, $rawSections, PREG_SET_ORDER);
        return $rawSections;
    }

    private function findSubSections($text, $subSection = "[\d\.]*")
    {
        preg_match_all("/= (" . $subSection . ") =([\s\S]*?)(?=(?:=)|\z)/", $text, $rawSubSections, PREG_SET_ORDER);
        return $rawSubSections;
    }
}
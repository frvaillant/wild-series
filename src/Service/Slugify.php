<?php


namespace App\Service;


class Slugify
{
    private $string;

    public function generate(string $string): string
    {
        $this->string = $string;
        $this->trimer()->removeAccents()->removeSpaces()->removeDouble()->toLower();
        return $this->string;
    }

    public function removeSpaces()
    {
        $this->string = str_replace(' ', '-', $this->string);
        return $this;
    }

    public function trimer()
    {
        $this->string = trim($this->string);
        return $this;
    }

    public function removeAccents()
    {
        $this->string = DataMaker::removeSpecialCharacters($this->string);
        return $this;
    }

    public function removeDouble()
    {
        $this->string = str_replace('--', '-', $this->string);
        return $this;
    }

    public function toLower()
    {
        $this->string = strtolower($this->string);
        return $this;
    }

}

<?php

namespace Tinder\Helper;

use Twig_Extension;

class TwigFormatter extends Twig_Extension
{

    public function getName()
    {
        return 'tinder';
    }

    public function getFilters()
    {
        return array(
          'gender' => new \Twig_Filter_Method($this, 'gender'),
          'age' => new \Twig_Filter_Method($this, 'age'),
        );
    }

    public function gender($input)
    {
        switch ($input) {
            case -1:
                return 'Both';
            case 0:
                return 'Male';
            case 1:
                return 'Female';
        }
    }

    public function age($input)
    {
        $year = date('Y');
        $birthYear = date('Y', strtotime($input));

        return $year - $birthYear;
    }
}
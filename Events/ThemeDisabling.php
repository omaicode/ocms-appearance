<?php

namespace Modules\Appearance\Events;

class ThemeDisabling
{
    /**
     * @var array|string
     */
    public $theme;

    /**
     * @param $theme
     */
    public function __construct($theme)
    {
        $this->theme = $theme;
    }
}

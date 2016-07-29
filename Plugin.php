<?php

namespace Kanboard\Plugin\Sprints;

use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {
        $this->route->addRoute('/plugin/sprints/', 'sprints', 'show', 'sprints');

        $this->template->hook->attach('template:header:dropdown', 'sprints:sidebar');
    }

    public function getPluginName()
    {
        return 'Scrum Sprints';
    }

    public function getPluginAuthor()
    {
        return 'esclkm <esclkm@gmail.com>';
    }

    public function getPluginVersion()
    {
        return '0.0.1';
    }

    public function getPluginDescription()
    {
        return 'Show shows week sprints for all projects';
    }

    public function getPluginHomepage()
    {
        return 'http://littledev.ru';
    }
}

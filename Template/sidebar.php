<?php
    $routerController = $this->app->getRouterController();
    $routerPlugin = $this->app->getPluginName();

    $active = $routerController == 'Sprints' && $routerPlugin == 'Sprints';
?>
<li class="<?= $active ? 'active' : '' ?>">
    <i class="fa fa-rotate-left fa-fw"></i>
    <?= $this->url->link(
        'Scrum Sprints',
        'sprints',
        'show',
        ['plugin' => 'sprints']
    ) ?>
</li>


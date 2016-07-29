<!--?php print_r($task) ?>-->
<div class="task-board <?php if (! empty($task['date_completed'])): ?>color-dark_grey<?php elseif ($task['column_name'] == 'Done' || $task['column_name'] == 'Greg tested'): ?>color-light_green <?php elseif($task['date_due'] < time() && $task['date_due'] > 0): ?> color-red<?php endif ?>" onclick="return false;">
    <span class="task-table  color-<?= $task['color_id'] ?>"> <?= $this->render('task/dropdown', array('task' => $task)) ?></span>


    <?php if ($task['reference']): ?>
    <span class="task-board-reference" title="<?= t('Reference') ?>">
        (<?= $task['reference'] ?>)
    </span>
    <?php endif ?>
    <div class="task-board-avatars">
        <?= $this->url->link($this->text->e($task['project_name']), 'BoardViewController', 'show', array('project_id' => $task['project_id'])) ?> 
        / <?= $this->text->e($task['swimlane_name']) ?>
        / <?= $this->text->e($task['column_name']) ?>
    </div>

    <?= $this->hook->render('template:board:public:task:before-title', array('task' => $task)) ?>
    <div class="task-board-title">
        <?= $this->url->link($this->text->e($task['title']), 'TaskViewController', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>
    </div>
    <?= $this->hook->render('template:board:public:task:after-title', array('task' => $task)) ?>
    <?= $this->render('sprints:task_footer', array(
        'task' => $task,
        'not_editable' => $not_editable,
        'project' => $project,
    )) ?>
</div>

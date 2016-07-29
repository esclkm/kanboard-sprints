<section id="main">
       
    <?php if (!count($users)): ?>
        <p class="alert"><?= t('No user') ?></p>
    <?php else: ?>
        <table class="table-stripped">
            <tr>
                <th class="column-8"><?= t('Username') ?>

                </th>
                <?php foreach ($dateIntervals as $interval): ?>
                    <th class="column-12" <?php if($interval['current']): ?>style="color:red;"<?php endif ?> >
                      <?= Date('d.m.y', $interval['start']) ?> - <?= Date('d.m.y', $interval['end']) ?>  
                    </th>
                <?php endforeach ?>
            </tr>
            <?php foreach ($users as $user): ?>
                <?php if(isset($tasks[$user['name']])): ?> 
                <tr>
                    <td>
                    <div><strong><?= $this->text->e($user['name']) ?></strong></div>
                        <?= $this->url->link($this->text->e($user['username']), 'UserViewController', 'show', array('user_id' => $user['id'])) ?><br/>
                        <?= $this->user->getRoleName($user['role']) ?>  

                        <?php if(isset($tasks[$user['name']]['nodate'])): ?> 
                            <hr/>
                            <?php foreach ($tasks[$user['name']]['nodate'] as $task): ?>
                                <?= $this->render('sprints:task_public', array(
                                                        'project' => 1,
                                                        'task' => $task,
                                                        'board_highlight_period' => null,
                                                        'not_editable' => false,
                                                    )) ?>
                                <!--<span class="task-board" style="display: inline-block;" alt="?= $this->text->e($task['title']) ?>" title="?= $this->text->e($task['title']) ?>">
                                    ?= $this->url->link('#'.$task['id'], 'TaskViewController', 'readonly', array('task_id' => $task['id'], 'token' => null)) ?>
                                </span>-->
                            <?php endforeach ?>
                        <?php endif ?>


                    </td>
                    <?php foreach ($dateIntervals as $key => $interval): ?>
                        <td> 
                            <?php if(isset($tasks[$user['name']][$key])): ?> 
                            <?php foreach ($tasks[$user['name']][$key] as $task): ?>
                                                    <?= $this->render('sprints:task_public', array(
                                                        'project' => 1,
                                                        'task' => $task,
                                                        'board_highlight_period' => null,
                                                        'not_editable' => false,
                                                    )) ?>
                                <!--<div class="task-board task-board-status-open color-teal"> 

                                    task
                                </div>-->
                            <?php endforeach ?>
                            <?php endif ?>
                        </td>
                    <?php endforeach ?>
                </tr>
                <?php endif ?>
            <?php endforeach ?>
        </table>
    <?php endif ?>
</section>
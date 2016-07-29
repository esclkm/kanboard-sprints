<?php

namespace Kanboard\Plugin\Sprints\Controller;

use Kanboard\Controller\BaseController;

class Sprints extends BaseController
{
    public $currentMonday;
    public $dateIntervals = [];
    public $rangeStart = -2;
    public $rangeEnd = 1;
    public $dateStart = 0;
    public $dateEnd = 0;

    public function show()
    {

        $users = $this->userModel->getQuery()->eq('is_active', true)->eq('is_ldap_user', 0)->asc('name')->findAll();
        $userTasks = [];

       // $products = $query->getResult();
/* tasklistcontroller
$this->taskLexer
                ->build($search)
                ->withFilter(new TaskProjectFilter($project['id']))
                ->getQuery()
                */
        $this->currentMonday = $this->getCurrentMonday();
        foreach (range($this->rangeStart, $this->rangeEnd) as $number) {
            $interval = $this->getWeekBefore($number);
            $this->dateIntervals[$number] = $interval;
            if($interval['start'] < $this->dateStart || $this->dateStart==0)
            {
                $this->dateStart  = $interval['start'];
            }
            if($interval['end'] > $this->dateEnd || $this->dateEnd==0)
            {
                $this->dateEnd  = $interval['end'];
            }           
        }
        foreach ($this->getTasksInSprints() as $key => $val) {
            $intervalN = $this->getInterval($val);
            if($intervalN !== false) {
                $userTasks[$val['assignee_name']][$intervalN][] = $val;
            }
        }
       // print_r($this->getTasksInSprints());
        //print_r(array(date('d.m.y', $this->dateStart), date('d.m.y', $this->dateEnd)));
        $this->response->html(
            $this->helper->layout->app(
                'sprints:show',
                [
                    'title' => 'Scrum Sprints',
                    'users' => $users,
                    'weekStarts' => $this->currentMonday,
                    'weekEnds' => $this->getEndOfWeek($this->currentMonday),
                    'dateIntervals' => $this->dateIntervals,
                    'tasks' => $userTasks
                ]
            )
        );

    }
    public function getInterval($task=[])
    {
        if (in_array($task['column_name'], ['Done', 'Greg tested', 'Tested', 'Checked', 'Done and Checked', 'Tested Bespoke', 'Rejected']))
        {
            $task['date_completed'] = $task['date_modification'];
        }
        if($task['date_creation'] > $this->dateStart && $task['date_due'] <= 0 && $task['date_completed'] <= 0)
        {
            $task['date_due'] = $this->dateEnd;
        }

        $due = $task['date_due'];
        $completed = $task['date_completed'];

        $date = 0;
        if($due > 0 ) {
            $date = $due;
        }
        if($completed > 0) {
            $date = $completed;
        }
        foreach ($this->dateIntervals as $key => $value) {
            if($date >= $value['start'] && $date <= $value['end']) {
                return $key;
            }
        }
        if($date > 0) {
            return 'lessdate';
        }
        return 'nodate';
    }

    public function getCurrentMonday()
    {
        $SecondsToMonday = (Date('N')-1)*24*3600;
        $TimeToZerro = Date('H')*3600 + Date('i')*60+Date('s');
        $currentWeekStarts = time() - $SecondsToMonday -  $TimeToZerro;
        return $currentWeekStarts;
    }
    public function getWeekBefore($weekInterval=0)
    {
        $weekStarts = $this->currentMonday + $weekInterval*7*24*3600;
        $weekEnds = $this->getEndOfWeek($weekStarts);
        return ['start'=> $weekStarts, 'end' => $weekEnds, 'current' => $weekInterval==0 ? true : false ];
    }
    public function getEndOfWeek($weekStarts)
    {
        return $weekStarts+7*24*3600-1;
    }

    public function getTasksInSprints()
    {
        $start = $this->dateStart;
        $end = $this->dateEnd;
       // $tasks = $this->taskFinderModel->getExtendedQuery();
        $int1 = $this->taskFinderModel->getExtendedQuery()->gte('tasks.date_due', $start)->lte('tasks.date_due', $end)->isNull('tasks.date_completed')->findAll();
        $int2 = $this->taskFinderModel->getExtendedQuery()->lte('tasks.date_due', 0)->isNull('tasks.date_completed')->findAll();
        $int3 = $this->taskFinderModel->getExtendedQuery()->gte('tasks.date_completed', $start)->lte('tasks.date_completed', $end)->findAll();
        $check_clones = [];
        //print_r(array(count($int1), count($int2), count($int3)));
        foreach ($int1 as $key => $value) {
            array_push($check_clones, $value['id']);
        }
        foreach ($int2 as $key => $value) {
            if(!in_array($value['id'], $check_clones))
            {
                array_push($check_clones, $value['id']);
                array_push($int1, $value);
            }
        }
        foreach ($int3 as $key => $value) {
            if(!in_array($value['id'], $check_clones))
            {
                array_push($check_clones, $value['id']);
                array_push($int1, $value);
            }
        }
        //print_r(array(count($int1), count($int2), count($int3), count($check_clones)));
        return $int1;

    }
}

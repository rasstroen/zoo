<?php

new CronRunner();

class CronRunner {

    function __construct() {
        $tasks_to_run = $this->getTasks();
        foreach ($tasks_to_run as $task) {
            $task->run();
        }
    }

    function getTasks() {
        // забираем из базы все таски, которые должны запуститься сейчас
        App::i()->db()->sql2array();
        // проверяем, что они ещё не запущены
        // добавляем таск в очередь на запуск
    }

}
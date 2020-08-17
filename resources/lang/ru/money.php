<?php

return [
    'ruble' => 'рубль|рубля|рублей',

    'list' => [
        'success' => [
            'title' => 'Твой список трат за :date:' . PHP_EOL . PHP_EOL,
            'title_nothing' => 'За указанную дату (:date) ничего не найдено.' . PHP_EOL,
            'record' => '_:id. :date,_ *:spend_sum :spend_plural* на *«:description»*.' . PHP_EOL,
            'summary' => 'Итого за :date: *:spend_sum :spend_plural*' . PHP_EOL,
            'all_time' => 'все время',
            'current_day' => 'сегодняшний день (:date)',
            'yesterday' => 'вчерашний день (:date)',
        ]
    ],

    'spend' => [
        'errors' => [
            'money' => 'Сумма затрат должна быть положительным числом.' . PHP_EOL . PHP_EOL . ':help',
            'help' => '/spend <сумма> <описание>' . PHP_EOL,
        ],
        'success' => 'Запись совершена.' . PHP_EOL . PHP_EOL .
            'Номер записи — :record_id' . PHP_EOL . PHP_EOL .
            ':spend_sum :spend_plural потрачено на «:description».' . PHP_EOL,
    ],

    'delete_spend' => [
        'errors' => [
            'id'=> 'Id записи должен быть положительным числом' . PHP_EOL . PHP_EOL . ':help',
            'record' => 'Запись с id :id не найдена' . PHP_EOL . PHP_EOL . ':help',
            'help' => '/deleteSpends <id записи>',
        ],
        'success' => 'Записи с id :id успешно удалена!' . PHP_EOL . PHP_EOL .
            'Информация из записи: :spend_sum :spend_plural на «:description».' . PHP_EOL,
    ],

    'errors' => [
        'global' => 'Что-то пошло не так.' . PHP_EOL,
        'arguments_min' => 'Недостаточное количество аргументов.' . PHP_EOL . PHP_EOL . ':help',
        'arguments_max' => 'Превышено количество аргументов.' . PHP_EOL . PHP_EOL . ':help',
    ],
];

<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

class BaseListCommand extends Command
{
    public function getRecordsList($spends)
    {
        $text = '';

        foreach ($spends as $record) {
            $text .= \Lang::get(
                'money.list.success.record',
                [
                    'date' => date('d.m H:i', strtotime($record->created_at)),
                    'id' => $record->id,
                    'spend_sum' => number_format($record->money, 0, '', ' '),
                    'spend_plural' => \Lang::choice('money.ruble', $record->money),
                    'description' => $record->description,
                ]
            );
        }

        return ($text);
    }

    public function handle()
    {
    }
}

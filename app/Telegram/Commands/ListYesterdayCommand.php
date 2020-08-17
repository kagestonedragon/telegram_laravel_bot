<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

class ListYesterdayCommand extends BaseListCommand
{
    protected $name = 'listYesterday';

    protected $aliases = ['listyesterday'];

    protected $description = 'Команда получения списка трат за все время.';

    public function handle()
    {
        $response = $this->getUpdate();
        $message = $response->getMessage();
        $spends = \App\Models\Money::whereDate(
            'created_at',
            date('Y-m-d', time() - 86400)
        )
            ->where('user_id', $message['from']['id'])
            ->get();

        if ($spends->isNotEmpty()) {
            $text  = $this->getTitle();
            $text .= $this->getRecordsList($spends);
            $text .= $this->getSummary($spends);
        } else {
            $text = $this->getTitleNothing();
        }

        $parse_mode = 'markdown';
        $this->replyWithMessage(compact('text', 'parse_mode'));
    }

    private function getTitle()
    {
        $text = \Lang::get(
            'money.list.success.title',
            [
                'date' => \Lang::get(
                    'money.list.success.yesterday',
                    [
                        'date' => date('d.m'),
                    ]
                ),
            ]
        );

        return ($text);
    }

    private function getSummary($spends)
    {
        $summary = $spends->sum('money');

        $text = PHP_EOL . \Lang::get(
                'money.list.success.summary',
                [
                    'date' => \Lang::get(
                        'money.list.success.yesterday',
                        [
                            'date' => date('d.m'),
                        ]
                    ),
                    'spend_sum' => number_format($summary, 0, '', ' '),
                    'spend_plural' => \Lang::choice('money.ruble', $summary)
                ]
            );

        return ($text);
    }

    private function getTitleNothing()
    {
        $text = \Lang::get(
            'money.list.success.title_nothing',
            [
                'date' => date('Y-m-d'),
            ]
        );

        return ($text);
    }
}

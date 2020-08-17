<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

class SpendCommand extends Command
{
    protected $name = 'spend';

    protected $aliases = [];

    protected $description = 'Команда записи траты.';

    public function handle()
    {
        $response = $this->getUpdate();
        $message = $this->parseMessage($response->getMessage());

        if ($message['error'] === true) {
             $text = $message['error_message'];
        } else {
            $record = $this->saveRecord(
                $message['message']['user_id'],
                $message['message']['username'],
                $message['message']['money'],
                $message['message']['description']
            );

            $text = \Lang::get(
                'money.spend.success',
                [
                    'record_id' => $record->id,
                    'spend_sum' => number_format($record->money, 0, '', ' '),
                    'spend_plural' => \Lang::choice('money.ruble', $record->money),
                    'description' => $record->description,
                ]
            );
        }

        $this->replyWithMessage(compact('text'));
    }

    private function saveRecord(int $user_id, string $username, int $money, string $description)
    {
        $record = new \App\Models\Money();

        $record->user_id = $user_id;
        $record->username = $username;
        $record->money = $money;
        $record->description = $description;

        $record->save();

        return ($record);
    }

    private function parseMessage($tgMessage)
    {
        $result = [
            'error' => false,
            'command' => '',
            'message' => [
                'user_id' => 0,
                'username' => '',
                'money' => '',
                'description' => '',
            ],
        ];

        $tmp = explode(' ', $tgMessage['text']);
        $result['command'] = array_shift($tmp);

        if (count($tmp) < 2) {
            $result['error'] = true;
            $result['error_message'] = \Lang::get(
                'money.errors.arguments_min',
                [
                    'help' => \Lang::get('money.spend.errors.help'),
                ]
            );
        } else {
            $money = array_shift($tmp);

            if (!is_numeric($money) || (int)$money < 0) {
                $result['error'] = true;
                $result['error_message'] = \Lang::get(
                    'money.spend.errors.money',
                    [
                        'help' => \Lang::get('money.spend.errors.help'),
                    ]
                );
            } else {
                $result['message']['user_id'] = (int)$tgMessage['from']['id'];
                $result['message']['username'] = (string)$tgMessage['from']['username'];
                $result['message']['money'] = (int)$money;
                $result['message']['description'] = (string)implode(' ', $tmp);
            }
        }

        return ($result);
    }
}

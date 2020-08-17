<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

class DeleteSpendCommand extends Command
{
    protected $name = 'deleteSpend';

    protected $aliases = ['deletespend'];

    protected $description = 'Команда удаления записи траты.';

    public function handle()
    {
        $response = $this->getUpdate();
        $message = $this->parseMessage($response->getMessage());

        if ($message['error'] === true) {
            $text = $message['error_message'];
        } else {
            $record = $message['message']['record'];

            $text = \Lang::get(
                'money.delete_spend.success',
                [
                    'id' => $message['message']['id'],
                    'spend_sum' => number_format($record->money, 0, '', ' '),
                    'spend_plural' => \Lang::choice('money.ruble', $record->money),
                    'description' => $record->description,
                ]
            );

            $record->delete();
        }

        $this->replyWithMessage(compact('text'));
    }

    private function parseMessage($tgMessage)
    {
        $result = [
            'error' => false,
            'command' => '',
            'message' => [
                'id' => '',
                'record' => '',
            ],
        ];

        $tmp = explode(' ', $tgMessage['text']);
        $result['command'] = array_shift($tmp);

        if (count($tmp) < 1) {
            $result['error'] = true;
            $result['error_message'] = \Lang::get(
                'money.errors.arguments_min',
                [
                    'help' => \Lang::get('money.delete_spend.errors.help'),
                ]
            );
        } else if (count($tmp) > 1) {
            $result['error'] = true;
            $result['error_message'] = \Lang::get(
                'money.errors.arguments_max',
                [
                    'help' => \Lang::get('money.delete_spend.errors.help'),
                ]
            );
        } else {
            $id = array_shift($tmp);

            if (!is_numeric($id) || (int)$id < 0) {
                $result['error'] = true;
                $result['error_message'] = \Lang::get(
                    'money.delete_spend.errors.id',
                    [
                        'help' => \Lang::get('money.delete_spend.errors.help'),
                    ]
                );
            } else {
                $records = \App\Models\Money::where('id', (int)$id)->get();

                if ($records->isEmpty()) {
                    $result['error'] = true;
                    $result['error_message'] = \Lang::get(
                        'money.delete_spend.errors.record',
                        [
                            'id' => $id,
                            'help' => \Lang::get('money.delete_spend.errors.help'),
                        ]
                    );
                } else {
                    $result['message']['id'] = (int)$id;
                    $result['message']['record'] = $records->first();
                }
            }
        }

        return ($result);
    }
}

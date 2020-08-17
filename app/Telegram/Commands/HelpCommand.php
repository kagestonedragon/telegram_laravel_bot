<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;

/**
 * Class HelpCommand.
 */
class HelpCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'help';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['listcommands'];

    /**
     * @var string Command Description
     */
    protected $description = 'Help command, Get a list of all commands';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $telegram = $this->getTelegram();
        $client = $telegram->getMe();
        $this->parseCommandArguments();

        $response = $this->getUpdate();
        $message = $response->getMessage();
        $parsedText = $this->parseText($message['text']);

        $text = 'Hey stranger, thanks for visiting me.'.chr(10).chr(10);
        $text .= 'I am a bot and working for'.chr(10);
        $text .= env('APP_URL').chr(10).chr(10);
        $text .= 'Please come and visit me there.'.chr(10);
        $text .= 'You - ' . $response->getMessage()['text'] . chr(10);
        $text .= 'You - ' . $client->getId() . chr(10);
        $text .= 'Argumenets - ' . json_encode($this->getArguments()) . chr(10);
        $record = new \App\Money();

        $record->user_id = (int)$message['from']['id'];
        $record->money = (int)$parsedText[1];
        $record->save();

        $this->replyWithMessage(compact('text'));

    }

    private function parseText(string $text)
    {
        return (explode(' ', $text));
    }
}

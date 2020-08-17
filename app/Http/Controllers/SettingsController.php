<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class SettingsController
 * @package App\Http\Controllers
 * Контроллер для управления настройками
 */
class SettingsController extends Controller
{
    public function index()
    {
        return (config('telegram.bots.moneyControllerBot.token'));
    }

    public function setWebHook(Request $request)
    {
        $result = $this->sendTelegramData(
            config('telegram.webhook.route.set'),
            [
                'query' => [
                    'url' => config('app.url') . config('telegram.bots.money.webhook_url'),
                ],
            ]
        );

        return ($result);
    }

    public function getWebHookInfo(Request $request)
    {
        $result = $this->sendTelegramData(
            config('telegram.webhook.route.getinfo')
        );

        return ($result);
    }

    public function deleteWebhook(Request $request)
    {
        $result = $this->sendTelegramData(
            config('telegram.webhook.route.delete')
        );

        return ($result);
    }

    public function sendTelegramData($route = '', $params = [], $method = 'POST')
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => config('telegram.bots.money.base_url'),
        ]);

        $result = $client->request($method, $route, $params);

        return (
            (string)$result->getBody()
        );
    }

    public function update(Request $request)
    {
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/test_log.log', print_r($_POST, true));
        \Telegram::commandsHandler(true);
    }
}

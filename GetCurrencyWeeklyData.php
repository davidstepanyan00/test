<?php

namespace App\Containers\AppSection\Test\UI\CLI\Commands;

use App\Ship\Parents\Commands\ConsoleCommand as ParentConsoleCommand;
use Carbon\Carbon;

class GetCurrencyWeeklyData extends ParentConsoleCommand
{
    protected $signature = 'get:currency:weekly:data';
    protected $description = 'Get currency weekly data';

    public const API_URL = 'https://www.cbr.ru/scripts/XML_daily.asp?';

    public const CURRENCIES = ['USD', 'EUR', 'KGS'];

    public function handle(): void
    {

        $filterDate = Carbon::now()->startOfWeek()->format('d/m/Y');
        $response['date'] = $filterDate;

        $xmlString = file_get_contents(self::API_URL . http_build_query(['data_req' => $filterDate]));
        $xmlObject = simplexml_load_string($xmlString);

        $json = json_encode($xmlObject);
        $data = json_decode($json, true);

        foreach ($data["Valute"] as $item) {
            if (in_array($item["CharCode"], self::CURRENCIES)) {
               $response["currencies"][$item["CharCode"]] = $item["Value"];
            }
        }

        echo json_encode($response) . "\n";

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stock;
use App\Models\Income;
use App\Models\Sale;
use App\Models\Order;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ApiDataController extends Controller
{
    private $baseUrl = 'http://89.108.115.241:6969/api';
    private $apiKey = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie';
    
    private function checkApiKey(Request $request)
    {
        $apiKey = $request->query('key');
        if ($apiKey !== $this->apiKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return true;
    }
    
    private function fetchFromApi(string $endpoint, array $params)
    {
        $response = Http::get("{$this->baseUrl}/{$endpoint}", $params);

        if ($response->failed()) {
            Log::error("API request to {$endpoint} failed", ['response' => $response->body()]);
            return null;
        }
        return $response->json();
    }

  
    private function fetchAndStoreData(Request $request, string $endpoint, array $params, $model, array $callbackData)
    {
        // Проверяем API ключ
        $authorizationCheck = $this->checkApiKey($request);
        if ($authorizationCheck !== true) {
            return $authorizationCheck;
        }

        // Устанавливаем параметры для запроса
        $params['page'] = 1;
        $params['limit'] = $params['limit'] ?? 500;  // Лимит по умолчанию

        // Запрос с пагинацией
        do {
            $data = $this->fetchFromApi($endpoint, $params);
            if (!$data || empty($data['data'])) {
                break;
            }

            // Обработка данных
            foreach ($data['data'] as $item) {
                $model::updateOrCreate(
                    $callbackData['uniqueField']($item),
                    $callbackData['dataFields']($item)
                );
            }

            $params['page']++;
        } while (count($data['data']) === $params['limit']);
    }
    public function fetchStocks(Request $request)
    {
        $params = [
            'dateFrom' => now()->toDateString(),
            'limit' => 500,
        ];

        $this->fetchAndStoreData($request, 'stocks', $params, Stock::class, [
            'uniqueField' => fn($item) => ['nm_id' => $item['nm_id']],  
            'dataFields' => fn($item) => [
                'name' => $item['supplier_article'] ?? 'Unknown',
                'quantity' => $item['quantity'],
                'supplier_article' => $item['supplier_article'] ?: 'Unknown', 
                'tech_size' => $item['tech_size'],
                'barcode' => $item['barcode'],
                'price' => $item['price'],
                'warehouse_name' => $item['warehouse_name'],
                'in_way_to_client' => $item['in_way_to_client'],
                'in_way_from_client' => $item['in_way_from_client'],
                'nm_id' => $item['nm_id'],
                'subject' => $item['subject'],
                'category' => $item['category'],
                'brand' => $item['brand'],
                'sc_code' => $item['sc_code'],
                'discount' => $item['discount']
            ]
        ]);

        return response()->json(['message' => 'Stocks data saved successfully']);
    }

    public function fetchIncomes(Request $request)
    {
        $params = [
            'dateFrom' => '2024-01-01',
            'dateTo' => '2024-12-10',
            'limit' => 100,
        ];

        $this->fetchAndStoreData($request, 'incomes', $params, Income::class, [
            'uniqueField' => fn($item) => ['income_id' => $item['income_id']],
            'dataFields' => fn($item) => [
                'number' => $item['number'],
                'date' => $item['date'],
                'last_change_date' => $item['last_change_date'],
                'supplier_article' => $item['supplier_article'],
                'tech_size' => $item['tech_size'],
                'barcode' => $item['barcode'],
                'quantity' => $item['quantity'],
                'total_price' => $item['total_price'],
                'date_close' => $item['date_close'],
                'warehouse_name' => $item['warehouse_name'],
                'nm_id' => $item['nm_id'],
            ]
        ]);

        return response()->json(['message' => 'Income data stored successfully']);
    }

    public function fetchSales(Request $request)
    {
        $params = [
            'dateFrom' => '2024-01-01',
            'dateTo' => '2024-12-10',
            'limit' => 100,
        ];

        $this->fetchAndStoreData($request, 'sales', $params, Sale::class, [
            'uniqueField' => fn($item) => ['sale_id' => $item['sale_id']],
            'dataFields' => fn($item) => [
                'g_number' => $item['g_number'],
                'date' => $item['date'],
                'last_change_date' => $item['last_change_date'],
                'supplier_article' => $item['supplier_article'],
                'tech_size' => $item['tech_size'],
                'barcode' => $item['barcode'],
                'total_price' => $item['total_price'],
                'discount_percent' => $item['discount_percent'],
                'is_supply' => $item['is_supply'],
                'is_realization' => $item['is_realization'],
                'promo_code_discount' => $item['promo_code_discount'],
                'warehouse_name' => $item['warehouse_name'],
                'country_name' => $item['country_name'],
                'oblast_okrug_name' => $item['oblast_okrug_name'],
                'region_name' => $item['region_name'],
                'income_id' => $item['income_id'],
                'odid' => $item['odid'],
                'spp' => $item['spp'],
                'for_pay' => $item['for_pay'],
                'finished_price' => $item['finished_price'],
                'price_with_disc' => $item['price_with_disc'],
                'nm_id' => $item['nm_id'],
                'subject' => $item['subject'],
                'category' => $item['category'],
                'brand' => $item['brand'],
                'is_storno' => $item['is_storno'],
            ]
        ]);

        return response()->json(['message' => 'Sales data saved successfully']);
    }

    public function fetchOrders(Request $request)
    {
        $params = [
            'dateFrom' => '2024-01-01',
            'dateTo' => '2024-12-10',
            'limit' => 100,
        ];

        $this->fetchAndStoreData($request, 'orders', $params, Order::class, [
            'uniqueField' => fn($item) => ['g_number' => $item['g_number']],
            'dataFields' => fn($item) => [
                'g_number' => $item['g_number'],
                'date' => $item['date'],
                'last_change_date' => $item['last_change_date'],
                'supplier_article' => $item['supplier_article'],
                'tech_size' => $item['tech_size'],
                'barcode' => $item['barcode'],
                'total_price' => $item['total_price'],
                'discount_percent' => $item['discount_percent'],
                'warehouse_name' => $item['warehouse_name'],
                'oblast' => $item['oblast'],
                'income_id' => $item['income_id'],
                'odid' => $item['odid'],
                'nm_id' => $item['nm_id'],
                'subject' => $item['subject'],
                'category' => $item['category'],
                'brand' => $item['brand'],
                'is_cancel' => $item['is_cancel'] ?? false,
                'cancel_dt' => $item['cancel_dt'] ?? null,
            ]
        ]);

        return response()->json(['message' => 'Orders data saved successfully']);
    }
}


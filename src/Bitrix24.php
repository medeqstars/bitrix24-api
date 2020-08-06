<?php

namespace Medeq\Bitrix24;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Arr;

class Bitrix24
{
    const MAX_BATCH_REQUEST_NUM = 50;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Bitrix24 constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $path
     * @param array $params
     * @return array|null
     */
    public function call($path, $params = []) : ? array
    {
        $url = $this->getFullUrlByPath($path);

        $params = $this->array_change_key_case_recursive($params, CASE_UPPER);

        $response = $this->client->post($url, [
            'form_params' => $params
        ]);

        $response = json_decode($response->getBody(), true);
        $response = $this->array_change_key_case_recursive($response, CASE_LOWER);

        return $response;
    }

    public function batch(string $path, array $params)
    {
        $response = $this->call($path, $params);

        if(! isset($response['result'])) {
            return null;
        }

        $result = $response['result'];

        $limit = Arr::pull($params, 'limit');

        if ($limit && count($result) >= $limit) {
            return array_slice($result, 0, $limit);
        }

        if (! isset($response['next'])) {
            return $result;
        }

        $requests = $this->generateRequests($path, $response['next'], $response['total'], $limit, $params);

        $requestGroups = array_chunk($requests, static::MAX_BATCH_REQUEST_NUM);

        foreach ($requestGroups as $batch)
        {
            $batch_response = $this->call('batch', [
                'cmd' => $batch,
                'halt' => 0,
            ]);

            if (! isset($batch_response['result'])) {
                continue;
            }

            foreach ($batch_response['result']['result'] as $batch_result) {
                $result = array_merge($result, $batch_result);
            }
        }

        if ($limit && $limit < count($result)) {
            $result = array_slice($result, 0, $limit);
        }

        return $result;
    }

    protected function array_change_key_case_recursive(array $data, $case = CASE_UPPER) : array
    {
        $attributes = [];

        foreach ($data as $key => $value) {

            $key = $case == CASE_UPPER ? mb_strtoupper($key) : mb_strtolower($key);

            if (is_array($value)) {
                $attributes[$key] = $this->array_change_key_case_recursive($value, $case);
            }
            else {
                $attributes[$key] = $value;
            }

        }

        return $attributes;
    }

    protected function getFullUrlByPath($path) : string
    {
        return config('bitrix24.domain') . "/rest/" . config('bitrix24.user_id') ."/" . config('bitrix24.token') ."/{$path}";
    }

    protected function generateRequests(string $path, $next, $total, $limit = null, array $params = [])
    {
        $requests = [];

        for ($i = $next; $i < $total; $i += $next) {
            $params['start'] = $i;
            $requests[] = "$path?" . http_build_query($params);
            if ($limit && $i + $next >= $limit) break;
        }

        return $requests;
    }
}

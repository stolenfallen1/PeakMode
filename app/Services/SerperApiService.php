<?php 

namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class SerperApiService
{
    protected $client;
    protected $headers;
    protected $apiKey;

    public function __construct() 
    {
        $this->apiKey = 'f90fb76a9193dd3e905dac0548d8f318144f9a9b';
        $this->client = new Client();
    }

    public function searchNearbyGyms($location) 
    {
        try {
            $headers = [
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json'
            ];

            $body = json_encode([
                'q' => 'gym',
                'location' => $location . ', Philippines', 
                'gl' => 'ph'
            ]);

            $request = new Request('POST', 'https://google.serper.dev/places', $headers, $body);
            $response = $this->client->sendAsync($request)->wait();
            return json_decode($response->getBody(), true);            

        } catch (\Exception $e) {
            \Log::error('Error searching nearby gyms: ' . $e->getMessage());
            return [];
        }
    }

}
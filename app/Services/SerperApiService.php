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
        $location = trim($location);
        if (empty($location)) {
            return [
                'error' => 'Location is required',
                'places' => []
            ];
        }

        if (!preg_match('/^[a-zA-Z0-9\s,.-]+$/', $location)) {
            return [
                'error' => 'Location contains invalid characters',
                'places' => []
            ];
        }

        try {
            $headers = [
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json'
            ];

            $body = json_encode([
                'q' => "gym near {$location}",
                'location' => $location, 
                'gl' => 'ph'
            ]);

            $request = new Request('POST', 'https://google.serper.dev/places', $headers, $body);
            $response = $this->client->sendAsync($request)->wait();
            $result = json_decode($response->getBody(), true);         
            
            if (empty($result['places'])) {
                return [
                    'error' => 'No gyms found near ' . $location,
                    'places' => []
                ];
            }

            return $result;

        } catch (\Exception $e) {
            \Log::error('Error searching nearby gyms: ' . $e->getMessage());
            return [];
        }
    }

}
<?php 

namespace App\Services;
use GuzzleHttp\Client;

class ExerciseApiService
{
    protected $client;
    protected $baseUrl = 'https://api.api-ninjas.com/v1/exercises';
    protected $headers;

    public function __construct() 
    {
        $this->headers = [
            'X-Api-Key' => '2/yj9SckomVhDhddK92dpQ==u9TwhMj0yDk5aOIW'
        ];
        $this->client = new Client(['base_uri' => $this->baseUrl]);
    }

    public function getDataFromExercisesAPI($filters = []) 
    {
        try {
            $response = $this->client->request('GET', '', [
                'query' => $filters,
                'headers' => $this->headers,
            ]);

            $data = json_decode($response->getBody(), true);
            return $data;

        } catch(\Exception $e) {
            \Log::error('Error fetching exercises: ' . $e->getMessage());
            return [];
        }
    }

    public function getExercisesByMuscleAndType($muscle, $type) 
    {
        $filters = [];
        if ($muscle) {
            $filters['muscle'] = $muscle;
        }
        if ($type) {
            $filters['type'] = $type;
        }

        return $this->getDataFromExercisesAPI($filters);
    }

}
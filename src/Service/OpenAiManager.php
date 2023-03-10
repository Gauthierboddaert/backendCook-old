<?php

namespace App\Service;

class OpenAiManager
{
    private string $apiKey;
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getDataFromOpenAiApi(string $question) : array
    {
        $model = "text-davinci-002";
        $prompt = $question;
        $max_tokens = 100;
        $temperature = 0.5;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.openai.com/v1/engines/".$model."/completions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{
              \"prompt\": \"".$prompt."\",
              \"max_tokens\": ".$max_tokens.",
              \"temperature\": ".$temperature.",
              \"n\":1,
              \"stop\":\"\",
              \"stream\":false
          }",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer ".$this->apiKey
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data;
    }
}
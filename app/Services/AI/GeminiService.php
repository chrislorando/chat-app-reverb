<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class GeminiService implements AIServiceInterface
{
    protected string $apiKey;
    protected string $endpoint;
    protected string $model;
    protected string $contentApi;

    public function __construct()
    {
        $this->apiKey   = config('ai.gemini.api_key');
        $this->endpoint = config('ai.gemini.endpoint');
        $this->model    = config('ai.gemini.model');
        $this->contentApi    = config('ai.gemini.content_api');
    }

    public function generateText(string $prompt): array
    {
        $url = "{$this->endpoint}/{$this->model}:{$this->contentApi}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $this->apiKey,
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => 0.9,
                'maxOutputTokens' => 150,
                'topP' => 1
            ],
            'safetySettings' => [
                [
                    'category' => 'HARM_CATEGORY_HARASSMENT',
                    'threshold' => 'BLOCK_ONLY_HIGH',
                ],
                [
                    'category' => 'HARM_CATEGORY_HATE_SPEECH',
                    'threshold' => 'BLOCK_ONLY_HIGH',
                ],
                [
                    'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                    'threshold' => 'BLOCK_ONLY_HIGH',
                ],
                [
                    'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                    'threshold' => 'BLOCK_ONLY_HIGH',
                ],
            ],
        ]);

        if ($response->failed()) {
            // throw new \Exception("Gemini API Error: " . $response->body());
             return [
                'error' => true,
                'body'  => json_decode($response->body())
            ];
        }

        $data = $response->json();

        $text = null;
        if (isset($data['candidates'][0]['content']['parts'])) {
            foreach ($data['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['text'])) {
                    $text = $part['text'];
                }
            }
        }

        return [
            'error' => false,
            'text' => $text,
            'raw'  => $data,
        ];
    }

    public function generateImage(string $prompt): array
    {
        $url = "{$this->endpoint}/{$this->model}:{$this->contentApi}";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $this->apiKey,
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'responseModalities' => ['TEXT', 'IMAGE'],
            ],
        ]);

        if ($response->failed()) {
            throw new \Exception("Gemini API Error: " . $response->body());
        }

        $data = $response->json();

        // Ambil base64 image (inlineData)
        $imageData = null;
        if (isset($data['candidates'][0]['content']['parts'])) {
            foreach ($data['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['inlineData']['data'])) {
                    $imageData = $part['inlineData']['data'];
                }
            }
        }

        return [
            'base64' => $imageData,
            'raw'    => $data,
        ];
    }
}

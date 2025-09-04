<?php

namespace App\Services\AI;

interface AIServiceInterface
{
    public function generateText(string $prompt): array;
    public function generateImage(string $prompt): array;
}

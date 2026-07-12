<?php

namespace App\Controllers;

/**
 * Chatbot API controller.
 * Proxies chat messages to an OpenAI-compatible chat completion endpoint
 * (modeled on the reference implementation in /Applications/XAMPP/xamppfiles/htdocs/cv).
 *
 * This endpoint is public (no auth required) so the storefront chatbot works
 * for any visitor.
 */
class ChatbotController
{
    private function getApiKey(): string
    {
        return $_ENV['CHATBOT_API_KEY']
            ?? 'sk-DNn7fUXawChAxxPuu2H2gR8U0fEafI7vYlMfiSeNWDUkgtY7fQ3Eo6qSBi9clvR8';
    }

    private function getApiUrl(): string
    {
        return $_ENV['CHATBOT_API_URL']
            ?? 'https://opencode.ai/zen/v1/chat/completions';
    }

    private function getModels(): array
    {
        return [
            'deepseek-v4-flash-free',
            'big-pickle',
            'glm-5-free',
            'kimi-k2.5-free',
            'nemotron-3-ultra-free',
            'mimo-v2.5-free',
            'north-mini-code-free',
            'minimax-m2.5-free',
            'gpt-5-nano',
        ];
    }

    public function chat()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);

        if (!$input || !isset($input['messages']) || !is_array($input['messages'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing messages']);
            return;
        }

        // Build a system prompt that frames the assistant for this storefront.
        $storeName = $this->getStoreName();
        $systemPrompt = [
            'role' => 'system',
            'content' => "You are a friendly customer-service assistant for \"{$storeName}\", "
                . "an online store. Answer questions about products, orders, shipping, and store "
                . "policies. You can communicate in Lao (ລາວ), English, Thai (ไทย), and Chinese (中文) "
                . "— match the customer's language. Be concise, helpful, and polite.",
        ];

        $messages = array_merge([$systemPrompt], $input['messages']);

        $apiKey = $this->getApiKey();
        $apiUrl = $this->getApiUrl();
        $models = $this->getModels();

        $lastError = null;
        foreach ($models as $model) {
            $payload = [
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => 1024,
                'temperature' => 0.7,
            ];

            $ch = curl_init($apiUrl);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $apiKey,
                ],
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                $lastError = 'API request failed: ' . $error;
                continue;
            }

            $decoded = json_decode($response, true);
            if ($httpCode >= 200 && $httpCode < 300 && isset($decoded['choices'][0]['message']['content'])) {
                http_response_code($httpCode);
                echo $response;
                return;
            }

            $lastError = 'Model ' . $model . ' failed (HTTP ' . $httpCode . ')';
        }

        http_response_code(503);
        echo json_encode([
            'error' => 'All models failed. ' . $lastError,
            'reply' => 'ຂໍອະໄພ, ຂ້ອຍບໍ່ສາມາດຕອບໄດ້ພາຍໃຕ້ຂະນະນີ້. ກະລຸນາລອງໃໝ່ພາຫຼັງ. / Sorry, I cannot respond right now. Please try again later.',
        ]);
    }

    private function getStoreName(): string
    {
        try {
            $db = \App\Core\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'store_name' LIMIT 1");
            $stmt->execute();
            $row = $stmt->fetch();
            if ($row && !empty($row['setting_value'])) {
                return $row['setting_value'];
            }
        } catch (\Exception $e) {
            // ignore
        }
        return 'Thiengtham';
    }
}

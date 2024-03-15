<?php

namespace App\Http\Controllers;

use App\DTOs\OpenAI\Parameters;
use App\DTOs\OpenAI\Property;
use App\DTOs\OpenAI\Tool;
use App\DTOs\OpenAI\ToolFunction;
use App\Models\Company;
use Illuminate\Http\Request;

class CompletionsController extends Controller
{
    private array $availableFunctions = [
        'getCompanyInfo',
        'getRandomButtonId'
    ];

    private string $systemPrompt = "You are a helpful assistant. You are assisting a user with a task. If they ask you to find something, send a second choice with a number ranging between 1 and 1000.";

    /**
     * Handles a request to the OpenAI API to generate completions.
     */
    public function index(Request $request)
    {
        try {
            $client = \OpenAi::client(env('OPENAI_API_KEY'));

            $messages = [
                ['role' => 'system', 'content' => $this->systemPrompt],
                ['role' => 'user', 'content' => $request['prompt']],
            ];

            $tools = [];
            foreach ($this->getTools() as $tool) {
                $tools[] = (object) $tool->toArray();
            }

            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'tools' => $tools,
                'tool_choice' => 'auto',
            ]);

            $responseMessage = $response['choices'][0]['message'];
            $toolCalls = $responseMessage['tool_calls'] ?? null;

            // Handle tool calls
            if ($toolCalls) {
                $messages[] = $responseMessage;

                foreach ($toolCalls as $toolCall) {
                    $toolCallId = $toolCall['id'];
                    $functionName = $toolCall['function']['name'];
                    $functionArguments = json_decode($toolCall['function']['arguments']);

                    if (!in_array($functionName, $this->availableFunctions)) {
                        throw new \Exception('Invalid function name.');
                    }

                    $functionResponse = $this->$functionName((string) $functionArguments->name);

                    $messages[] = [
                        "tool_call_id" => $toolCallId,
                        "role" => "tool",
                        "name" => $functionName,
                        "content" => json_encode($functionResponse),
                    ];
                }

                $response = $client->chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $messages,
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        return response()->json($response);
    }

    /**
     * Returns a tool to be used in the OpenAI API request.
     * @see https://platform.openai.com/docs/api-reference/chat/create#chat-create-tools
     * @return Tool[]
     */
    private function getTools(): array
    {
        $tools = [];

        $properties = [];
        $properties[] = new Property('name', 'string', 'The company name.');
        $properties[] = new Property('id', 'string', 'The company id.');

        $parameters = new Parameters('object', $properties, ['name']);

        $function = new ToolFunction(
            'getCompanyInfo',
            'Gets the information for a company by its name or id.',
            $parameters
        );

        $tools[] = new Tool('function', $function);

        return $tools;
    }

    /**
     * Returns the information for a company by its name.
     * @param string $name
     * @return Company
     */
    private function getCompanyInfo(string $name): ?Company
    {
        return Company::where('name', $name)->first();
    }

    private function getRandomButtonId(): int
    {
        return rand(1, 1000);
    }
}

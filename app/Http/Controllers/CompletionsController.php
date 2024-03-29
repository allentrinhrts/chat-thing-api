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
        'getHtmlIds',
    ];

    private string $systemPrompt = <<<EOT
        You are a helpful assistant. You are assisting a user with a task. You are able to locate HTML elements on a
        page. If the user asks you to find something, return an HTML id that most closely resembles what the user is
        looking for. If you find a match, end your response with "let me take you there". If you don't find a match,
        end your response with "I couldn't find that element".
    EOT;

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

            error_log('toolCalls: ' . json_encode($toolCalls) . PHP_EOL);

            // Handle tool calls
            if ($toolCalls) {
                $messages[] = $responseMessage;

                foreach ($toolCalls as $toolCall) {
                    $toolCallId = $toolCall['id'];
                    $functionName = $toolCall['function']['name'];
                    $functionArguments = json_decode($toolCall['function']['arguments']);

                    error_log('In loop' . PHP_EOL);

                    if (!in_array($functionName, $this->availableFunctions)) {
                        throw new \Exception('Invalid function name.');
                    }

                    error_log('after catch' . PHP_EOL);
                    error_log('functionName: ' . $functionName . PHP_EOL);
                    error_log('functionArguments: ' . json_encode($functionArguments) . PHP_EOL);

                    $functionResponse = $this->$functionName($functionArguments);

                    error_log('functionResponse: ' . json_encode($functionResponse) . PHP_EOL);

                    $messages[] = [
                        "tool_call_id" => $toolCallId,
                        "role" => "tool",
                        "name" => $functionName,
                        "content" => json_encode($functionResponse),
                    ];

                    error_log('messages: ' . json_encode($messages) . PHP_EOL);
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

        $getCompanyInfoFn = new ToolFunction(
            'getCompanyInfo',
            'Gets the information for a company by its name or id.',
            new Parameters(
                'object',
                [
                    new Property('name', 'string', 'The company name.'),
                    new Property('id', 'string', 'The company id.'),
                ],
                ['name'],
            ),
        );

        $getHtmlIdsFn = new ToolFunction(
            'getHtmlIds',
            'Gets a list of the available HTML ids.',
            new Parameters(
                'object',
                [
                    new Property('target', 'string', 'The target HTML element')
                ],
                ['target']
            ),
        );

        $tools[] = new Tool('function', $getCompanyInfoFn);
        $tools[] = new Tool('function', $getHtmlIdsFn);

        return $tools;
    }

    /**
     * Returns the information for a company by its name.
     * @param object $args
     * @return Company
     */
    private function getCompanyInfo(string $args): ?Company
    {
        return Company::where('name', $args->name)->first();
    }

    /**
     * Gets a list of the available HTML ids.
     * @return string[]
     */
    private function getHtmlIds(): array
    {
        return [
            'root',
            'site-header',
            'site-content',
            'url-input',
            'site-footer',
        ];
    }
}

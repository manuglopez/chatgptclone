<?php

use App\Http\Livewire\Chat;
use Livewire\Livewire;
use OpenAI\Laravel\Facades\OpenAI;

it('can see the home page', function () {
    Livewire::test(Chat::class)
        ->assertSee('GPT Clone');
});

it('can post a message to openai api', function () {
    $this->message = 'Hello World';
    $this->messages = session()->get('messages', [
        ['role' => 'system', 'content' => 'You are LaravelGPT - A ChatGPT clone. Answer as concisely as possible.'],
    ]);
    $this->messages[] = ['role' => 'user', 'content' => $this->message];

    $response = Mockery::mock(OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => $this->messages,
        'temperature' => 0.7,
        'max_tokens' => 2097,
        'top_p' => 0.3,
        'frequency_penalty' => 0.5,
        'presence_penalty' => 0.0,
    ]));

    expect($response->toArray()['choices'][0]['message']['content'])->toBe('Hi there! How can I assist you today?');

    $this->messages[] = [
        'role' => 'assistant',
        'content' => $response->toArray()['choices'][0]['message']['content'],
    ];
    expect($this->messages)->toBe([
        ['role' => 'system', 'content' => 'You are LaravelGPT - A ChatGPT clone. Answer as concisely as possible.'],
        ['role' => 'user', 'content' => 'Hello World'],
        ['role' => 'assistant', 'content' => 'Hi there! How can I assist you today?'],
    ]);
});

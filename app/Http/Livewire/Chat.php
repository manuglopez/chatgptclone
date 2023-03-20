<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class Chat extends Component
{
    public $messages ;
    public $message;
    public function render():View
    {

        $this->messages = collect(session('messages', []))->reject(fn ($message) => $message['role'] === 'system');

        return view('livewire.chat', [
            'messages' => $this->messages,
        ]);

    }
    public function mount(): void
    {
        $this->messages = collect(session('messages', []))->reject(fn ($message) => $message['role'] === 'system');

    }

    /**
     * Sends a message to GPT API.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws \JsonException
     */
    public function store(Request $request)
    {

        $this->messages = $request->session()->get('messages', [
            ['role' => 'system', 'content' => 'You are LaravelGPT - A ChatGPT clone. Answer as concisely as possible.']
        ]);
        $this->messages[] = ['role' => 'user', 'content' => $this->message];

        while (strlen(json_encode($this->messages, JSON_THROW_ON_ERROR))>4000){
            array_shift($this->messages);
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $this->messages,
            'temperature'=>0.7,
            'max_tokens'=>2097,
            'top_p'=>0.3,
            'frequency_penalty'=>0.5,
            'presence_penalty'=>0.0
        ]);
        $this->messages[] = ['role' => 'assistant', 'content' => $response->choices[0]->message->content];
        session()->put('messages', $this->messages);
        $this->message = '';
        $this->render();

    }

    /**
     * Reset the session and deletes the messages.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function destroy(Request $request)
    {
        $request->session()->forget('messages');

        $this->render();
    }

}

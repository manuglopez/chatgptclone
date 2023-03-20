<div>
    <div class="flex flex-col space-y-4 p-4">
        <h1 class="text-3xl font-bold">GPT Clone</h1>
        @foreach($messages as $message)
            <div class="flex rounded-lg p-4 @if ($message['role'] === 'assistant') bg-green-200 flex-reverse @else bg-blue-200 @endif ">
                <div class="ml-4">
                    <div class="text-lg">
                        @if ($message['role'] === 'assistant')
                            <a href="#" class="font-medium text-gray-900">LaravelGPT</a>
                        @else
                            <a href="#" class="font-medium text-gray-900">You</a>
                        @endif
                    </div>
                    <div class="mt-1">
                        <p class="text-gray-600">
                            {!! \Illuminate\Mail\Markdown::parse($message['content']) !!}
                        </p>
                    </div>
                </div>
            </div>

            <script>
                scrollToBottom();
            </script>
        @endforeach
        <div class="text-center bg-red-900 text-gray-50" wire:loading>
            Procesando petición...
            <script>
                scrollToBottom();
            </script>
        </div>
    </div>

    <form id="bottom" class="p-4 flex space-x-4 justify-center items-center" wire:submit.prevent="store">
        <label for="message">Question:</label>
        <input id="message" wire:model.debounce.1000ms="message" type="text" name="message" autocomplete="off" class="border rounded-md  p-2 flex-1" />
        <a wire:click.prevent="destroy" class="bg-gray-800 text-white p-2 rounded-md" href="#">Reset Conversation</a>

    </form>

    @push('scripts')
        <script>
          function scrollToBottom() {
              document.getElementById('bottom').scrollIntoView({behavior: 'smooth'});
          }
        </script>

    @endpush
</div>

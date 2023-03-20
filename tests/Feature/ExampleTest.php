<?php
it('can see the welcome page', function () {
    $this->get('/')
        ->assertSeeLivewire('chat');
});

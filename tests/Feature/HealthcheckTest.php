<?php

test('app boots and welcome loads', function () {
    $response = $this->get('/');
    $response->assertOk();
});

<?php

use Dentro\Paranoia\Middlewares\FormViaHeaderMiddleware;
use Illuminate\Http\Request;

test('POST form should correctly mapped in Request', function (): void {
    $formUnderTest = [
        'username' => 'johndoe',
        'password' => 'myp4ss',
    ];

    /** @var string $json */
    $json = json_encode($formUnderTest);
    $encodedForm = base64_encode($json);

    $request = Request::create(
        uri: '/',
        method: 'POST',
        server: ['HTTP_X-Form' => $encodedForm],
    );

    (new FormViaHeaderMiddleware)->handle($request, function ($request) use ($formUnderTest): void {
        expect($request->input('username'))
            ->toBe($formUnderTest['username'])
            ->and($request->input('password'))
            ->toBe($formUnderTest['password']);
    });
});

test('GET request should correctly mapped in Request', function (): void {
    $formUnderTest = [
        'username' => 'johndoe',
        'password' => 'myp4ss',
    ];

    /** @var string $json */
    $json = json_encode($formUnderTest);
    $encodedForm = base64_encode($json);

    $request = Request::create(
        uri: '/',
        method: 'GET',
        server: ['HTTP_X-Form' => $encodedForm],
    );

    (new FormViaHeaderMiddleware)->handle($request, function ($request) use ($formUnderTest): void {
        expect($request->get('username'))
            ->toBe($formUnderTest['username'])
            ->and($request->get('password'))
            ->toBe($formUnderTest['password']);
    });
});

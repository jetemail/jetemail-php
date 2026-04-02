<?php

declare(strict_types=1);

use JetEmail\Resource;

it('creates a resource from an array', function () {
    $resource = Resource::from([
        'id' => 'msg_123',
        'status' => 'sent',
    ]);

    expect($resource)->toBeInstanceOf(Resource::class)
        ->and($resource->id)->toBe('msg_123')
        ->and($resource->status)->toBe('sent');
});

it('returns null for missing attributes', function () {
    $resource = Resource::from(['id' => 'msg_123']);

    expect($resource->nonexistent)->toBeNull();
});

it('supports array access', function () {
    $resource = Resource::from(['id' => 'msg_123', 'name' => 'test']);

    expect($resource['id'])->toBe('msg_123')
        ->and($resource['name'])->toBe('test')
        ->and(isset($resource['id']))->toBeTrue()
        ->and(isset($resource['missing']))->toBeFalse();
});

it('is immutable via array access', function () {
    $resource = Resource::from(['id' => 'msg_123']);

    $resource['id'] = 'changed';
    unset($resource['id']);

    expect($resource['id'])->toBe('msg_123');
});

it('serializes to json', function () {
    $resource = Resource::from(['id' => 'msg_123', 'status' => 'sent']);

    $json = json_encode($resource);

    expect($json)->toBe('{"id":"msg_123","status":"sent"}');
});

it('converts to array', function () {
    $attributes = ['id' => 'msg_123', 'from' => 'test@example.com'];
    $resource = Resource::from($attributes);

    expect($resource->toArray())->toBe($attributes);
});

it('supports isset on properties', function () {
    $resource = Resource::from(['id' => 'msg_123']);

    expect(isset($resource->id))->toBeTrue()
        ->and(isset($resource->missing))->toBeFalse();
});

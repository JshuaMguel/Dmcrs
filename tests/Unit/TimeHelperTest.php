<?php

use App\Helpers\TimeHelper;

it('formats 24h time to 12h', function () {
    expect(TimeHelper::formatTime('13:05'))->toBe('1:05 PM');
    expect(TimeHelper::formatTime('00:00'))->toBe('12:00 AM');
});

it('returns original when invalid', function () {
    expect(TimeHelper::formatTime('not-a-time'))->toBe('not-a-time');
    expect(TimeHelper::formatTime(null))->toBe('');
});

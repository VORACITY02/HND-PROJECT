<?php

use App\Support\Money;

it('formats XOF with 0 decimals', function () {
    expect(Money::format(1234, 'XOF'))->toBe('1,234');
});

it('formats USD with 2 decimals', function () {
    expect(Money::format(1234, 'USD'))->toBe('12.34');
});

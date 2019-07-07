<?php

namespace Tests\Unit;

use App\Country;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountrySeederTest extends TestCase
{

    use RefreshDatabase;

    public function testCountrySeederSeedsAllCountries()
    {
        $expectedNumberOfCountries = 206;
        $actualNumberOfCountries = sizeOf(Country::all());

        $this->assertEquals($expectedNumberOfCountries, $actualNumberOfCountries);
    }
}

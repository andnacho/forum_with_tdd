<?php

namespace Tests\Unit;

use Tests\TestCase;

class SpamTest extends TestCase
{
    /** @test */
    public function it_checks_for_invalides_keywords()
    {
        $spam = new \App\Inspection\Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }

    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        $spam = new \App\Inspection\Spam();

        $this->expectException('Exception');


        $spam->detect('Hello world aaaaaaaaa');
    }
}

<?php

namespace Tests\app\Infrastructure\Controller;

use Tests\TestCase;

class GetStatusControllerTest extends TestCase
{
    /**
     * @test
     */
    public function systemIsUpAndRunning()
    {
        $response = $this->get('/api/status');

        $response->assertExactJson(['status' => 'Ok', 'message' => 'Systems are up and running']);
    }
}

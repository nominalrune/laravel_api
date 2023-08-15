<?php
declare(strict_types=1);
namespace Tests\Feature\Auth;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\ApiTestCase;

class ApiTokenTest extends ApiTestCase
{
    protected \Laravel\Sanctum\NewAccessToken $token01;
    protected \Laravel\Sanctum\NewAccessToken $token02;
    protected \Laravel\Sanctum\NewAccessToken $token03;
    protected function setUp() : void
    {
        parent::setUp();
        $this->token01 = $this->user01->createToken('test-token01');
        $this->token02 = $this->user02->createToken('test-token02');
        $this->token03 = $this->user02->createToken('test-token03');
    }
    public function test_can_not_access_tokens_without_login(): void
    {
        $this->getJson('/users'.'/'.$this->user01->id.'/tokens')->assertStatus(401);
    }
    public function test_users_can_get_own_tokens()
    {
        $this->login()
            ->getJson('/users'.'/'.$this->user01->id.'/tokens')
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([$this->token01->plainTextToken]);
    }
}

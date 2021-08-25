<?php

namespace Tests\Feature;

use App\Trade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class StockTradesApiMediumTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_create_a_new_trade_of_buy_type()
    {
        $user23_buy_ABX = [
            'type' => 'buy',
            'user_id' => 23,
            'symbol' => 'ABX',
            'shares' => 30,
            'price' => 134,
            "timestamp" => 1531522701000
        ];

        $response = $this->postJson('/trades', $user23_buy_ABX);

        $response
            ->assertStatus(201)
            ->assertExactJson(Arr::add($user23_buy_ABX, 'id', 1));
    }

    public function test_should_create_a_new_trade_of_sell_type()
    {
        $user23_sell_AAC = [
            'type' => 'sell',
            'user_id' => 23,
            'symbol' => 'AAC',
            'shares' => 12,
            'price' => 133,
            "timestamp" => 1521522701000,
        ];

        $response = $this->postJson('/trades', $user23_sell_AAC);

        $response
            ->assertStatus(201)
            ->assertExactJson(Arr::add($user23_sell_AAC, 'id', 1));
    }

    public function test_should_fetch_all_the_trades()
    {
        $trades = factory(Trade::class, 5)->create();

        $response = $this->getJson('/trades');

        $response
            ->assertStatus(200)
            ->assertExactJson($trades->toArray());
    }

    public function test_should_fetch_no_trades_if_the_type_filter_value_does_not_exist()
    {
        factory(Trade::class, 5)->create();

        $response = $this->getJson('/trades?type=test');

        $response
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    public function test_should_fetch_all_trades_for_a_user()
    {
        $trade1 = factory(Trade::class)->create([
            'user_id' => 23,
        ]);

        $trade2 = factory(Trade::class)->create([
            'user_id' => 23,
        ]);

        factory(Trade::class)->create([
            'user_id' => 24
        ]);

        factory(Trade::class)->create([
            'user_id' => 25
        ]);

        $response = $this->getJson('/trades?user_id=23');

        $response
            ->assertStatus(200)
            ->assertExactJson(collect([])->add($trade1)->add($trade2)->toArray());
    }

    public function test_should_fetch_no_trades_if_user_filter_value_does_not_exist()
    {
        factory(Trade::class, 5)->create();

        $response = $this->getJson('/trades?user_id=3233');

        $response
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    public function test_should_fetch_all_buy_trades_for_a_user()
    {
        $trade1 = factory(Trade::class)->create([
            'user_id' => 23,
            'type' => 'buy',
        ]);

        factory(Trade::class, 2)->create([
            'user_id' => 24,
            'type' => 'sell',
        ]);

        factory(Trade::class, 2)->create([
            'user_id' => 25,
            'type' => 'sell',
        ]);

        $response = $this->getJson('/trades?user_id=23&type=buy');

        $response
            ->assertStatus(200)
            ->assertExactJson([$trade1->toArray()]);
    }

    public function test_should_fetch_all_sell_trades_for_a_user()
    {
        $trade = factory(Trade::class)->create([
            'user_id' => 23,
            'type' => 'sell',
        ]);

        factory(Trade::class, 2)->create([
            'user_id' => 24,
            'type' => 'buy',
        ]);

        factory(Trade::class, 2)->create([
            'user_id' => 25,
            'type' => 'buy',
        ]);

        $response = $this->getJson('/trades?user_id=23&type=sell');

        $response
            ->assertStatus(200)
            ->assertExactJson([$trade->toArray()]);
    }

    public function test_should_fetch_a_single_trade()
    {
        $trade = factory(Trade::class)->create();

        $response = $this->getJson("/trades/{$trade->id}");

        $response
            ->assertStatus(200)
            ->assertExactJson($trade->toArray());
    }

    public function test_should_get_404_if_the_trade_ID_does_not_exist()
    {
        $response = $this->getJson('/trades/32323');

        $response
            ->assertStatus(404)
            ->assertSeeText('ID not found');
    }

    public function test_should_get_405_for_a_put_request_to_trades_id()
    {
        $trade = factory(Trade::class)->create();

        $response = $this->putJson("/trades/{$trade->id}", [
            'type' => 'buy',
            'user_id' => 23,
            'symbol' => 'ABX',
            'shares' => 30,
            'price' => 134,
            "timestamp" => 1531522701000
        ]);

        $response->assertStatus(405);
    }

    public function test_should_get_405_for_a_patch_request_to_trades_id()
    {
        $trade = factory(Trade::class)->create();

        $response = $this->patchJson("/trades/{$trade->id}", [
            'type' => 'buy',
            'user_id' => 23,
            'symbol' => 'ABX',
            'shares' => 30,
            'price' => 134,
            "timestamp" => 1531522701000
        ]);

        $response->assertStatus(405);
    }

    public function test_should_get_405_for_a_delete_request_to_trades_id()
    {
        $trade = factory(Trade::class)->create();

        $response = $this->delete("/trades/{$trade->id}");

        $response->assertStatus(405);
    }
}

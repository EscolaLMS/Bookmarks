<?php

namespace EscolaLms\Bookmarks\Tests\Api;

use EscolaLms\Bookmarks\Database\Seeders\BookmarkPermissionSeeder;
use EscolaLms\Bookmarks\Models\Bookmark;
use EscolaLms\Bookmarks\Tests\BookmarkTesting;
use EscolaLms\Bookmarks\Tests\TestCase;
use EscolaLms\Core\Tests\CreatesUsers;
use Illuminate\Support\Arr;

class BookmarkIndexApiTest extends TestCase
{
    use BookmarkTesting, CreatesUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BookmarkPermissionSeeder::class);
    }

    /**
     * @dataProvider filterDataProvider
     */
    public function testIndexBookmarkFilters(array $filters, callable $generator, int $filterCount): void
    {
        $user = $this->makeStudent();
        $generator($user->getKey())->each(fn($factory) => $factory->create());

        $this->actingAs($user, 'api')
            ->getJson($this->prepareUri('api/bookmarks', $filters))
            ->assertOk()
            ->assertJsonCount($filterCount, 'data')
            ->assertJsonStructure(['data' => [[
                'id',
                'value',
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                ],
                'bookmarkable_id',
                'bookmarkable_type',
            ]]]);
    }

    public function testIndexBookmarkPagination(): void
    {
        $user = $this->makeStudent();
        Bookmark::factory()->count(10)->create();
        Bookmark::factory()->count(25)->create(['user_id' => $user->getKey()]);

        $this->actingAs($user, 'api')
            ->getJson('api/bookmarks?per_page=10')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 25
                ]
            ]);

        $this->actingAs($user, 'api')
            ->getJson('api/bookmarks?per_page=10&page=3')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 25
                ]
            ]);
    }

    /**
     * @dataProvider orderDataProvider
     */
    public function testIndexBookmarkOrder(array $order, callable $generator, callable $assertion): void
    {
        $user = $this->makeStudent();
        $generator($user->getKey())->each(fn($factory) => $factory->create());

        $response = $this->actingAs($user, 'api')
            ->getJson($this->prepareUri('api/bookmarks', $order))
            ->assertOk();

        $assertion($response);
    }

    public function filterDataProvider(): array
    {
        return [
            [
                'filter' => [
                ],
                'data' => (function (int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory());
                    $items->push(Bookmark::factory()->state(['user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId]));

                    return $items;
                }),
                'filterCount' => 2
            ],
            [
                'filter' => [
                    'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic',
                ],
                'data' => (function (int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory());
                    $items->push(Bookmark::factory()->state(['user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Course']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Course']));

                    return $items;
                }),
                'filterCount' => 3
            ],
            [
                'filter' => [
                    'bookmarkable_id' => 123,
                    'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic',
                ],
                'data' => (function (int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory());
                    $items->push(Bookmark::factory()->state(['user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic', 'bookmarkable_id' => 123]));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Topic']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'bookmarkable_type' => 'EscolaLms\\Courses\\Models\\Course']));

                    return $items;
                }),
                'filterCount' => 1
            ],
        ];
    }

    public function orderDataProvider(): array
    {
        return [
            [
                'order' => [
                    'order_by' => 'id',
                    'order' => 'asc',
                ],
                'data' => (function(int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory()->state(['id' => 1, 'user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['id' => 2, 'user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['id' => 3, 'user_id' => $userId]));

                    return $items;
                }),
                'assert' => (function($data) {
                    $this->assertEquals(1, Arr::first($data->getData()->data)->id);
                    $this->assertEquals(3, Arr::last($data->getData()->data)->id);
                })
            ],
            [
                'order' => [
                    'order_by' => 'id',
                    'order' => 'desc',
                ],
                'data' => (function(int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory()->state(['id' => 1, 'user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['id' => 2, 'user_id' => $userId]));
                    $items->push(Bookmark::factory()->state(['id' => 3, 'user_id' => $userId]));

                    return $items;
                }),
                'assert' => (function($data) {
                    $this->assertEquals(1, Arr::last($data->getData()->data)->id);
                    $this->assertEquals(3, Arr::first($data->getData()->data)->id);
                })
            ],
            [
                'order' => [
                    'order_by' => 'value',
                    'order' => 'asc',
                ],
                'data' => (function(int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'value' => 'aaa']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'value' => 'bbb']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'value' => 'ccc']));

                    return $items;
                }),
                'assert' => (function($data) {
                    $this->assertEquals('aaa', Arr::first($data->getData()->data)->value);
                    $this->assertEquals('ccc', Arr::last($data->getData()->data)->value);
                })
            ],
            [
                'order' => [
                    'order_by' => 'value',
                    'order' => 'desc',
                ],
                'data' => (function(int $userId) {
                    $items = collect();
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'value' => 'aaa']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'value' => 'bbb']));
                    $items->push(Bookmark::factory()->state(['user_id' => $userId, 'value' => 'ccc']));

                    return $items;
                }),
                'assert' => (function($data) {
                    $this->assertEquals('aaa', Arr::last($data->getData()->data)->value);
                    $this->assertEquals('ccc', Arr::first($data->getData()->data)->value);
                })
            ],
        ];
    }
}

<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Console\CliDumper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * DB のテーブルに入っているデータを出力します
     */
    protected function dumpdb(): void
    {
        if (class_exists(CliDumper::class)) {
            CliDumper::resolveDumpSourceUsing(fn () => null); // ファイル名や行数の出力を消す
        }

        // Laravel Ver.11 未満は、Schema::getAllTables() として下さい
        foreach (Schema::getTables() as $table) {
            if (isset($table->name)) {
                $name = $table->name;
            } else {
                $table = (array) $table;
                $name = reset($table);
            }

            if (in_array($name, ['migrations'], true)) {
                continue;
            }

            $collection = \DB::table($name)->get();

            if ($collection->isEmpty()) {
                continue;
            }

            $data = $collection->map(function ($item) {
                unset($item->created_at, $item->updated_at);

                return $item;
            })->toArray();

            dump(sprintf('■■■■■■■■■■■■■■■■■■■ %s %s件 ■■■■■■■■■■■■■■■■■■■', $name, $collection->count()));
            dump($data);
        }

        $this->assertTrue(true);
    }

    /**
     * Dump the database query.
     */
    protected function dumpQuery(): void
    {
        \DB::enableQueryLog();

        $this->beforeApplicationDestroyed(function () {
            dump(\DB::getQueryLog());
        });
    }

    /**
     * 共通のログイン処理
     * @param User|null $user
     * @return User
     */
    protected function login(User $user = null): User
    {
        $user ??= User::factory()->create();
        $this->actingAs($user);
        // $this->actingAs($user, 'admin');  マルチ認証の場合
        return $user;
    }
}

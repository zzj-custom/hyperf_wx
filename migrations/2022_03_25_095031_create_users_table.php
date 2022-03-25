<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('beautiful_word', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('hitokoto')->default('')->comment('文本类容');
            $table->string('type', 10)->default('')->comment(
                '文本类型，a-动画, b-漫画, c-游戏, d-小说, e-原创, f-来自网络, g-其他'
            );
            $table->string('from', 64)->nullable()->comment('来源方式');
            $table->string('from_who', 64)->nullable()->comment('来源谁');
            $table->string('commit_from', 64)->nullable()->comment('来源何处');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');


            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8_unicode_ci';

            //设置表的comment
            $table->comment('每日一词');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beautiful_word');
    }
}

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

class CreateTrain extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('train', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('md5_txt', 32)->unique()->comment('对联md5序列');
            $table->string('in_train')->nullable()->comment('上联');
            $table->string('out_train')->nullable()->comment('下联');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');

            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8mb4';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8mb4_unicode_ci';

            //设置表的comment
            $table->comment('对联');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('train');
    }
}

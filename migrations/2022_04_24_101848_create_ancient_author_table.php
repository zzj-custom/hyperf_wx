<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

class CreateAncientAuthorTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ancient_author', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 64)->unique()->comment('作者名称');
            $table->text('desc')->comment('作者简介');
            $table->string('dynasty')->default('')->comment('朝代');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');

            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8mb4';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8mb4_unicode_ci';

            //设置表的comment
            $table->comment('古文作者');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ancient_author');
    }
}

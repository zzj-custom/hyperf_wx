<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;
use Hyperf\DbConnection\Db;

class CreateYellowWord extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yellow_word', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('md5_txt', 33)->unique()->nullable(false)->comment('文本MD5字符串');
            $table->text('text')->nullable(false)->comment('污段子');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');


            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8_unicode_ci';

            //设置表的comment
            $table->comment('黄段子');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yellow_word');
    }
}

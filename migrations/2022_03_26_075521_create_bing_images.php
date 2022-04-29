<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

class CreateBingImages extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bing_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->nullable(false)->comment('图片名称');
            $table->string('images_url')->nullable(false)->comment('图片地址');
            $table->date('date')->nullable(false)->comment('图片时间');
            $table->integer('click_count')->default(0)->comment('点击次数');
            $table->integer('download_count')->default(0)->comment('下载次数');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');

            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8mb4';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8mb4_unicode_ci';

            //设置表的comment
            $table->comment('必应图片');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bing_images');
    }
}

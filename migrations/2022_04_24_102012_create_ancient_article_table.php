<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

class CreateAncientArticleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ancient_article', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('ancient_id', 32)->comment('古典文学id');
            $table->char('ancient_type_id', 32)->nullable()->comment('古典文学分类id');
            $table->string('name')->nullable()->default('')->comment('文章名称');
            $table->longText('content')->nullable()->comment('文章内容');
            $table->string('category_name')->nullable()->default('')->comment('分类名称');
            $table->text('category_desc')->nullable()->comment('分类描述');
            $table->string('video_url')->default('')->nullable()->comment('朗读地址');
            $table->string('author_id')->default('')->nullable()->comment('作者id');
            $table->integer('collection')->nullable()->default(0)->comment('收藏次数');
            $table->integer('view')->nullable()->default(0)->comment('查看次数');
            $table->integer('comment_num')->nullable()->default(0)->comment('评论数');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');

            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8mb4';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8mb4_unicode_ci';

            //设置表的comment
            $table->comment('古文文章');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ancient_article');
    }
}

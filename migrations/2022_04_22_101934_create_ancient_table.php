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
use App\Infrastructure\Service\Baidu\BaiduTranslateClient;
use Contract\Exceptions\RemoteException;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Arr;

class CreateAncientTable extends Migration
{
    protected array $ancient = [
        [
            'ancient_id'   => 'history_department',
            'ancient_name' => '史部',
            'children'     => [
                [
                    'ancient_type_name' => '传记类',
                ],
                [
                    'ancient_type_name' => '別史类',
                ],
                [
                    'ancient_type_name' => '地理类',
                ],
                [
                    'ancient_type_name' => '杂史类',
                ],
                [
                    'ancient_type_name' => '正史类',
                ],
                [
                    'ancient_type_name' => '纪事本末类',
                ],
                [
                    'ancient_type_name' => '编年',
                ],
                [
                    'ancient_type_name' => '载纪类',
                ],
            ],
        ],
        [
            'ancient_id'   => 'sub_division',
            'ancient_name' => '子部',
            'children'     => [
                [
                    'ancient_type_name' => '儒家类',
                ],
                [
                    'ancient_type_name' => '兵家类',
                ],
                [
                    'ancient_type_name' => '农家类',
                ],
                [
                    'ancient_type_name' => '医家类',
                ],
                [
                    'ancient_type_name' => '小说家类',
                ],
                [
                    'ancient_type_name' => '术数类',
                ],
                [
                    'ancient_type_name' => '杂家类',
                ],
                [
                    'ancient_type_name' => '法学类',
                ],
                [
                    'ancient_type_name' => '法家类',
                ],
                [
                    'ancient_type_name' => '类书类',
                ],
                [
                    'ancient_type_name' => '艺术类',
                ],
                [
                    'ancient_type_name' => '谱录类',
                ],
                [
                    'ancient_type_name' => '道家类',
                ],
                [
                    'ancient_type_name' => '释家类',
                ],
            ],
        ],
        [
            'ancient_id'   => 'warp_department',
            'ancient_name' => '经部',
            'children'     => [
                [
                    'ancient_type_name' => '书类',
                ],
                [
                    'ancient_type_name' => '四书类',
                ],
                [
                    'ancient_type_name' => '孝经类',
                ],
                [
                    'ancient_type_name' => '小学类',
                ],
                [
                    'ancient_type_name' => '易类',
                ],
                [
                    'ancient_type_name' => '春秋类',
                ],
                [
                    'ancient_type_name' => '礼类',
                ], [
                    'ancient_type_name' => '诗类',
                ],
            ],
        ],
        [
            'ancient_id'   => 'shu_bu',
            'ancient_name' => '集部',
            'children'     => [
                [
                    'ancient_type_name' => '别集类',
                ],
                [
                    'ancient_type_name' => '词曲类',
                ],
                [
                    'ancient_type_name' => '诗文评类',
                ],
            ],
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ancient', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('ancient_id', 32)->comment('古典文学id');
            $table->string('ancient_name')->nullable()->comment('古典文学名称');
            $table->char('ancient_type_id', 32)->unique()->nullable()->comment('古典文学分类id');
            $table->string('ancient_type_name')->nullable()->comment('古典文学分类名称');
            $table->timestamp('created_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP'))->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->default(Db::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->comment('更新时间');

            // 指定表存储引擎
            $table->engine = 'InnoDB';

            // 指定数据表的默认字符集
            $table->charset = 'utf8mb4';

            // 指定数据表默认的排序规则
            $table->collation = 'utf8mb4_unicode_ci';

            //设置表的comment
            $table->comment('古典文学');
        });

        Db::table('ancient')->insert($this->handleInsertData());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ancient');
    }

    /**
     * @throws RemoteException
     * @return array
     */
    private function handleInsertData(): array
    {
        $insertData = [];

        foreach ($this->ancient as $value) {
            foreach ($value['children'] as $items) {
                $insertData = Arr::prepend($insertData, [
                    'ancient_id'        => md5($value['ancient_id']),
                    'ancient_name'      => $value['ancient_name'],
                    'ancient_type_id'   => md5($this->handleTranslateAncient($items['ancient_type_name'])),
                    'ancient_type_name' => $items['ancient_type_name'],
                ]);
                //降低请求频率
                sleep(1);
            }
        }
        return $insertData;
    }

    /**
     * @throws RemoteException
     * @return array|string|string[]
     */
    private function handleTranslateAncient(string $ancientTypeName): string
    {
        $translateResult = ApplicationContext::getContainer()->get(BaiduTranslateClient::class)->request($ancientTypeName);
        $translateResult = strtolower($translateResult);
        return str_replace([' ', '-'], '_', trim($translateResult));
    }
}

<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'link_name' => '百度',
                'link_title' => '众里寻他千百度',
                'link_url' => 'http://www.baidu.com',
                'link_order' => 1,
            ],
            [
                'link_name' => 'Mrsong',
                'link_title' => '梦在全栈',
                'link_url' => 'http://www.mrsong.me',
                'link_order' => 2,
            ],
        ];
        DB::table('links')->insert($data);
    }
}

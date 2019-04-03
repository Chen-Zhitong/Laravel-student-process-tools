<?php

namespace App\Console\Commands;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Console\Command;

class PixianStuCardImportInfoByExcel extends Command
{
    private $excel_src = '/home/sikoay/Desktop/学生证信息导入处理/郫县/郫县考生导入模板中级.xlsx';
    private $moban_src = '/home/sikoay/Desktop/学生证信息导入处理/郫县/moban.jpg';
    private $save_dir = '/home/sikoay/Desktop/学生证信息导入处理/郫县处理完成/';
    private $ttf = '/home/sikoay/font/simsun.ttc';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PixianStuCardImportInfoByExcel:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '郫县Excel信息处理';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->excel_src);
        $worksheet = $spreadsheet->getActiveSheet();
        $datas = $worksheet->toArray();


        $datas = array_filter ( $datas ,function($arr){
                return !is_null($arr[0]);
            });
        $datas = array_map(function($data){
            return array_slice($data,0,7);
        },$datas);

        $first = true;
        foreach($datas as $data){
            if($first){
                $first = false;
                continue;
            }
            $name = $data[0];
            $sex = $data[1];
            $birthday = $data[2];
            $profession = $data[3];
            $class = $data[4];
            $entrance_date = $data[5];
            $learn_years = $data[6];

            $b = Image::make($this->moban_src);
            // 姓名
            $b->text($name,485,765,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            // 性别
            $b->text($sex,740,765,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            // 出生日期
            $b->text($birthday,565,885,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            // 专业
            $b->text($profession,510,1005,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            // 班级
            $b->text($class,705,1005,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            // 入学时间
            $b->text($entrance_date,500,1130,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            // 学制
            $b->text($learn_years,710,1125,function($font) {
                $font->file($this->ttf);
                $font->size(40);
            });

            $b->save($this->save_dir.$name.'.jpg');
            $this->line('(-/'.count($datas).')'.$name.' 处理完成');
            return;
        }
    }
}

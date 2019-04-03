<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class LeshanLvyouImageProcess extends Command
{
    private $b_file = '/home/sikoay/Desktop/乐山旅游学校/moban.jpg';
    private $p_dir = '/home/sikoay/Desktop/乐山旅游学校/平行班育婴师/';
    private $s_dir = '/home/sikoay/Desktop/乐山旅游学校/处理完成/';
    private $ttf = '/home/sikoay/font/Deng.ttf';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LeshanLvyouImageProcess:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理乐山照片,一次性操作';

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

        // configure with favored image driver (gd by default)
        Image::configure(array('driver' => 'imagick'));

        // dd($b);
        // 获取文件名
        $dir = opendir($this->p_dir);
        $files = [];
        while(false !== ($file = readdir($dir))){
            if(filetype($this->p_dir . $file) == 'file'){
                $files[]= $file;
            }
        }
        closedir($dir);
        // dd($files);
        $i = -1;
        $complete = 0;
        // 照片处理
        foreach($files as $file_name){
            $i++;
            if($i<$complete){
                continue;
            }
            $this->line('---('.$i.'/'.count($files).')'.$file_name.'开始处理');

            $b = Image::make($this->b_file);
            $photo = Image::make($this->photoPath($file_name));
            $photo -> resize(240,352);
            // dd($photo->filename);
            $b->insert($photo,'top-left',1182,857);
            $b->text($photo->filename,100,100,function($font) {
                $font->file($this->ttf);
                $font->size(30);
            });
            $b->save($this->photoSavePath($file_name));
            $this->line('('.$i.'/'.count($files).')'.$file_name.'处理完成');
        }
    }

    private function photoPath($file_name){
        return $this->p_dir.$file_name;
    }
    private function photoSavePath($file_name){
        return $this->s_dir.$file_name;
    }

}

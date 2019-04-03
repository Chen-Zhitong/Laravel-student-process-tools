<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class MianyangImageProcess extends Command
{
    private $b_file = '/home/sikoay/Desktop/绵阳幼专高级育婴师照片/moban.jpg';
    private $p_dir = '/home/sikoay/Desktop/绵阳幼专高级育婴师照片/育婴师电子照片/';
    private $ttf = '/home/sikoay/font/Deng.ttf';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MianyangImageProcess:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理绵阳照片,一次性操作';

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
            if($file != '.' && $file != '..' && $file!='处理完成'){
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
            $photo -> resize(280,390);
            // dd($photo->filename);
            $b->insert($photo,'top-left',1700,1280);
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
        return $this->p_dir.'处理完成/'.$file_name;
    }

}

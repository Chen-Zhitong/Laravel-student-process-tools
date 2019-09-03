<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;

class CompressPicturesTo19kb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompressPictures:19kb {in_dir} {out_dir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '压缩图片到19Kb';

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
        $in_dir = $this->argument('in_dir');
        $out_dir = $this->argument('out_dir');

        if(!is_dir($in_dir) || !is_dir($out_dir)){
            $this->error('请输入正确的 输入目录 和 输出目录');
        }
        // 获取文件名
        $dir = opendir($in_dir);
        $files = [];
        while(false !== ($file = readdir($dir))){
            if(filetype($in_dir . $file) == 'file'){
                $files[]= $file;
            }
        }
        closedir($dir);

        $i = -1;
        foreach($files as $file_name){
            $i++;
            $photo = Image::make($in_dir.$file_name);
            $jpg=$photo -> encode('jpg',100);
            $jpg -> resize(200,290);
            // $photo->save($out_dir.$file_name);
            $jpg->save($out_dir.$jpg->filename.'.jpg');

            $this->line('('.$i.'/'.count($files).')'.$file_name.'处理完成');
        }

    }
}

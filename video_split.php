<?php
$dir_input = '/usr/www/zip2'; // Input dir with files video mp4
$dir_output = '/usr/www/zip'; // Output dir with files video mp4
$clip_duration = 120; // New clip duration - 
$clip_timer_shift = 80; // Timer shift - $clip_timer_shift = $clip_duration

// We read a directory into an array only one level
// $is_add_dir - добавлять в название полный путь к файлу или каталогу
// $is_file - добавлять только файлы, иначе добавлять только каталоги
function dir_to_array_nr($dir,$is_add_dir=false,$is_file=true) {
  $r=array();
  if (!is_dir($dir)) return false;
  $cdir = scandir($dir);
  foreach ($cdir as $key => $value)
  {
    if (!in_array($value,array('.','..')))
    {
      if ($is_file)
      {
        if (is_file($dir . '/' . $value))
        {
          if ($is_add_dir) $r[] = $dir . '/' . $value;
          else $r[] = $value;
        }
      }
      else
      {
        if (is_dir($dir . '/' . $value))
        {
          if ($is_add_dir) $r[] = $dir . '/' . $value;
          else $r[] = $value;
        }
      }
    }
  }
  return $r;
}

// Добавление нулей в начале
function add_zero($num,$kolvo) {
  $num=abs($num);
  $len=strlen($num);
  if ($len<$kolvo) return trim(str_repeat('0',$kolvo-$len).$num);
  else return trim($num);
}

$ar = dir_to_array_nr($dir_input,true);
if ($ar == false) {
    exit("Empty dir $dir");
}
foreach($ar as $fname) {
    echo "File: $fname" . PHP_EOL;
    $str_exec = 'ffprobe -i "' . $fname . '" -show_entries format=duration -v quiet -of csv="p=0"';
    $out = array();
    echo $str_exec . PHP_EOL;
    exec($str_exec,$out);
    print_r($out);
    if (!isset($out[0])) {
        echo 'Error duration' . PHP_EOL;
        continue;
    }
    $duration = intval($out[0]);
    $cnt_file = 1;
    $timer = 0; 
    $new_video_duration = $clip_duration; // Длина ролика
    $timer_sdvig = $clip_timer_shift; // Сдвиг таймера
    $file_ext = strrpos(basename($fname),'.');
    while($timer<$duration) {
        $fname_new = $dir_output.'/'.substr(basename($fname),0,$file_ext) . '_'.add_zero($cnt_file,3). substr(basename($fname),$file_ext);
        if ($duration - $timer < $new_video_duration) {
            $new_video_duration = $duration - $timer;
        }
        $str_exec = 'ffmpeg -i "' . $fname . '" -ss '.$timer.'.0 -t '.$new_video_duration.'.0 -c copy "'.$fname_new.'"';
        echo $str_exec . PHP_EOL;
        $timer += $timer_sdvig;
        $cnt_file ++;
        system($str_exec);
    }
    
    
    
}
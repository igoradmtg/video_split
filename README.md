# video_split

Split video by time

# Install

install ffmpeg

install php

download file split_video.php or split_video_by_time.php 

edit file split_video.php or split_video_by_time.php 

```PHP

$dir_input = '/usr/www/zip2'; // Input dir with files video mp4
$dir_output = '/usr/www/zip'; // Output dir with files video mp4
$clip_duration = 120; // New clip duration - 
$clip_timer_shift = 80; // Timer shift - $clip_timer_shift = $clip_duration

```
# Execute

php -f split_video.php

or

php -f split_video_by_time.php 

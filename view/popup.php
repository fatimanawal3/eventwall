<?php
session_start();
require_once 'config/config.php';
require_once BASE_PATH . '/includes/auth_validate.php';

$eve = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$pagelimit = 200;
$page = filter_input(INPUT_GET, 'page');
if (!$page) {
    $page = 1;
}

$db = getDbInstance();

$db->pageLimit = $pagelimit;

$total_pages = $db->totalPages;
?>

<?php $files = glob('/var/www/html/prime_ui2/x1/videos/{,.}*', GLOB_BRACE); 
      foreach($files as $file){ 
        if(is_file($file))
          unlink($file); }?>

<?php exec("/usr/bin/python3 image2.py '".$eve."'", $output); 
      set_time_limit(90);?>

<?php do {
          if (file_exists("/var/www/html/prime_ui2/x1/videos/video.mp4")) 
            {?> 
              <video width="100%" height="100%" controls="controls" autoplay="autoplay">
                <source src="videos/video.mp4" type="video/mp4">
                  Sorry, your browser doesn't support the video element.
              </video>
          <?php break;}
          } while(true);?>



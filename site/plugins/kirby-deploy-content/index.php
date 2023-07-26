<?php


Kirby::plugin('wagnerpaul/deploy-content', [
    'siteMethods' => [
        'flush_dir' => function ($dir) {
          if (!is_dir($dir))  return ;

          $dir = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
          $dir = new RecursiveIteratorIterator($dir,RecursiveIteratorIterator::CHILD_FIRST);
          
          foreach ( $dir as $file ) { 
              $file->isDir() ?  rmdir($file) : unlink($file);
          }
        },
        'push_dir' => function ($src,$dst) {    
            if (!is_dir($src))  return ; 
            $dir = opendir($src);
            @mkdir($dst);
            while(( $file = readdir($dir)) ) {
              if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                  $this->push_dir($src .'/'. $file, $dst .'/'. $file);
                }
                else {
                  copy($src .'/'. $file,$dst .'/'. $file);
                }
              }
            }
            closedir($dir);
        }
    ],
    'routes' => function ($kirby) {
        return [

            [
                'pattern' => env('DEPLOY_CONTENT_HOOK').'/start',
                'method' => 'POST',
                'action'  => function () {
                  $key = kirby()->request()->get('key');
                  if ($key === env('DEPLOY_CONTENT_KEY')) {
                    $response = Response::json(['status' => 'start'], 200);
                  } else {
                    $response = Response::json(['status' => 'error'], 400);
                  }
                  return $response;
                }
            ],

        ];
      },
      'hooks' => [
          //after our progress has started do the directory copying
          'route:after' => function ($route, $path, $method) {
            $endpointAndHook = env('WEBHOOKS_ENDPOINT').'/'.env('DEPLOY_CONTENT_HOOK');
            if ($path === $endpointAndHook.'/progress' && $method === 'POST') {
              $kirby = kirby();
              $site = $kirby->site();
              $site->flush_dir('/app/content');
              $site->push_dir('/app/content-stg','/app/content');
              $site->flush_dir('/app/storage/cache');


                $url = 'https://stg.'.env('SLD').'.'.env('TLD').'/'.$endpointAndHook.'/success';
                $data = ['status' => 'success'];
                $options = [
                    'method'  => 'POST',
                    'data'    => json_encode($data)
                 ];
                $response = Remote::request($url, $options);  
            }
          },


      ]
  
]);

<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;

class Shared implements ControllerInterface
{
  protected $plates;

  public function __construct(Engine $plates)
  {
    $this->plates = $plates;
  }

  public function execute(ServerRequestInterface $request)
  {
    if (!isset($_GET['url'])) {
      echo "<div>
              You shouldn't be there. Well - you'll be soon redirected to login.
              </div>";
      header('HTTP/1.1 401 Unauthorized');
      header('Refresh:3; url=start.php');
      die();
    }

    if (!file_exists('sharefile/' . $_GET['url'])) {
      header("Location: 404.php", 404);
      die();
    }

    echo $this->templates->render('_sharedgallery', []);
  }
}

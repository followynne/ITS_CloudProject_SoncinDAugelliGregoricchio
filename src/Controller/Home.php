<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;

class Home implements ControllerInterface
{
    protected $plates;

    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    public function execute(ServerRequestInterface $request)
    {
        if (isset($_SESSION['mail'])) {
            $mail = $_SESSION['mail'];
            echo $this->plates->render('_homepage', ['mail' => $mail]);
        } else {
            echo '<script type="text/javascript">
            alert("Credentials wrong");
            </script>';
            header('Location: login');
        }
        echo $this->plates->render('home');
    }
}

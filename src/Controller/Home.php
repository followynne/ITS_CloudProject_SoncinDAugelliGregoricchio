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
            // add method to get first 5 photos in DB
            echo $this->plates->render('_homepage', ['mail' => $mail]);
            exit;
        } else {
            echo '<script type="text/javascript">alert("Credentials wrong");</script>';
            echo $this->plates->render('_login', []);
        }
    }
}

<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\AzureStorageSASOperations;
use SimpleMVC\Model\DAOInteraction;

class Register implements ControllerInterface
{
    protected $plates;
    protected $dao;
    protected $azure;

    public function __construct(Engine $plates, DAOInteraction $dao, AzureStorageSASOperations $azure)
    {
        $this->plates = $plates;
        $this->azure = $azure;
        $this->dao = $dao;
    }

    public function execute(ServerRequestInterface $request)
    {
        $post = $request->getParsedBody();
        if (isset($_SESSION['mail'])) {
            header('Location: /');
            return;
        }
        if ($request->getMethod() != 'POST') {
            echo $this->plates->render('_register', ['msg' => 'Hello, welcome to the registration.']);
            exit;
        } else if (filter_var($post['mail'], FILTER_VALIDATE_EMAIL) && strlen($post['pwd']) > 6) {
            $dati['email'] = $post["mail"];
            $dati['user'] = $post["user"];
            $pwd = password_hash($post['pwd'], PASSWORD_DEFAULT);
            $dati['pwd'] = $pwd;
            
            if (!empty($this->dao->checkUserExistence($dati['email']))) {
                echo $this->plates->render('_register', ['msg' => "Check your mail please."]);
                die;
            }
            $containername = '';
            do {
                $containername = $this->generateRandomString(10);
            } while (!$this->azure->createContainer($containername));
            $dati['containername'] = $containername;
            $res = $this->dao->registerUser($dati);
            if ($res == 'correct') {
                echo $this->plates->render('_login', ['msg' => "Account creato. :)."]);
                die();
            } else {
                echo $res;
                http_response_code(401);
                echo $this->plates->render('_register', ['msg' => "Errore nel processamento dei tuoi dati."]);
                die();
            }
        } else {
            echo $this->plates->render('_register', ['msg' => "Errore, dati di registrazione non validi.Riprova."]);
            exit;
        }
    }

    private function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

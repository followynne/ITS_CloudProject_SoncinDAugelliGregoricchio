<?php

declare(strict_types=1);

namespace SimpleMVC\Controller;

use League\Plates\Engine;
use Psr\Http\Message\ServerRequestInterface;
use SimpleMVC\Model\AzureInteractionContainer;

class Home implements ControllerInterface
{
    protected $plates;
    protected $blob;

    public function __construct(Engine $plates, AzureInteractionContainer $blob)
    {
        $this->plates = $plates;
        $this->blob = $blob;
    }

    public function execute(ServerRequestInterface $request)
    {
        if (isset($_SESSION['mail'])) {
            $user = $_SESSION['user'];
            $this->blob->setContainer($_SESSION['container']);
            $photos = $this->blob->getLastBlobs();
            if (empty($photos)){
                $photos = [
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRDotp6lpWuQVLM3PHPk0sko5Xv3yvalBYbmEzcGOcnFsVi5N76&usqp=CAU',
                    'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__340.jpg',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRS6OtIG3BUtuqDzMvbr7GdEO_4BrcL-Jrz1jVKt2l2V9rCjY1n&usqp=CAU',
                    'https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQhbe5-kI2PTHa6-4MAFPJFk2DmDPTuj_Wikb_JRRyZvF43hlxb&usqp=CAU',
                    'https://files.salsacdn.com/service/1251-STABG/image/Dollarphotoclub-30128064-X3_z_0_27_514.20190207122938.jpg'
                ];
            }
            echo $this->plates->render('_homepage', ['user' => $user, 'data' => $photos]);
            exit;
        } else {
            echo $this->plates->render('_login', ['msg' => '']);
            exit;
        }
    }
}

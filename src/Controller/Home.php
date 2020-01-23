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
                    'https://www.zingarate.com/pictures/2018/05/28/aurora-boreale_1.jpeg',
                    'https://cdn-01.independent.ie/irish-news/article37870710.ece/5f8ab/AUTOCROP/w620/2019-03-02_iri_48402126_I1.JPG',
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

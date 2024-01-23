<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class conn extends AbstractController
{
    public function index()
    {
        $user = "root";
        $pass = "";
        try {
            $dbh = new \PDO('mysql:host=localhost;dbname=sophiane', $user, $pass);
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $stmt = $dbh->query('SELECT * FROM marker');
            $markerData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $markerData;
            } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }
}
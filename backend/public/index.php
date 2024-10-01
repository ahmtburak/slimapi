<?php
header('Access-Control-Allow-Origin: http://localhost:5173');

use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
  $database = new Database;
  $postsUrl = 'https://jsonplaceholder.typicode.com/posts';
  $postsResponse = file_get_contents($postsUrl);
  $postsData = json_decode($postsResponse, true);

  //Veritabanına bağlantı kontrolü yapıyoruz. Hata varsa yazdırıyoruz
  try {
    $pdo = $database->getConnection();
  } catch (PDOException $e) {
    die("Veritabanına bağlanamadı: " . $e->getMessage());
  }

  //Eklenen postlar için ve veritabanında bulunanlar için count tutuyorum.
  $addedPostsCount = 0;
  $existingPostsCount = 0;

  //Foreach ile apiden gelen veriyi kontrol ederek ekleme
  try {
    foreach ($postsData as $key => $value) {
      // Veritabanında aynı id'de veri eklenmiş mi kontrol etme 
      $checkSql = "SELECT COUNT(*) FROM posts WHERE id = ?";
      $checkStmt = $pdo->prepare($checkSql);
      $checkStmt->execute([$value['id']]);
      $exists = $checkStmt->fetchColumn();

      // Aynı veri bulunmazsa veriyi ekle
      if ($exists == 0) {
        $sql = "INSERT INTO posts (userId, id, body, title) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$value['userId'], $value['id'], $value['body'], $value['title']]);
        $addedPostsCount++;
      } else {
        $existingPostsCount++;
      }
    }
    $response->getBody()->write("Posts tablosuna $addedPostsCount veri başarıyla eklendi. $existingPostsCount veri zaten mevcut.");
  } catch (PDOException $e) {
    $response->getBody()->write("API'den veritabanına yazılırken bir hata oluştu: " . $e->getMessage());
  }

  $usersUrl = 'https://jsonplaceholder.typicode.com/users';
  $usersResponse = file_get_contents($usersUrl);
  $usersData = json_decode($usersResponse);

  //Eklenen postlar için ve veritabanında bulunanlar için count tutuyorum.
  $addedUsersCount = 0;
  $existingUsersCount = 0;

  //Foreach ile apiden gelen veriyi kontrol ederek ekleme
  try {
    foreach ($usersData as $key => $value) {
      // Veritabanında aynı id'de veri eklenmiş mi kontrol etme 
      $checkSql = "SELECT COUNT(*) FROM users WHERE id = ?";
      $checkStmt = $pdo->prepare($checkSql);
      $checkStmt->execute([$value->id]);
      $exists = $checkStmt->fetchColumn();

      // Aynı veri bulunmazsa veriyi ekle
      if ($exists == 0) {
        $sql = "INSERT INTO users (id, name, username, email, address, phone,website,company) VALUES (?, ?, ?, ?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        //JSON olarak eklemek için
        $value->address = json_encode($value->address);
        $value->company = json_encode($value->company);
        $stmt->execute([$value->id, $value->name, $value->username, $value->email, $value->address, $value->phone, $value->website, $value->company]);
        $addedUsersCount++;
      } else {
        $existingUsersCount++;
      }
    }
    $response->getBody()->write("Users tablosuna $addedUsersCount veri başarıyla eklendi. $existingUsersCount veri zaten mevcut.");
  } catch (PDOException $e) {
    $response->getBody()->write("API'den veritabanına yazılırken bir hata oluştu: " . $e->getMessage());
  }

  return $response;
});

$app->get('/getData', function (Request $request, Response $response, $args) {
  $database = new Database;

  //Veritabanına bağlantı kontrolü yapıyoruz. Hata varsa yazdırıyoruz
  try {
    $pdo = $database->getConnection();
  } catch (PDOException $e) {
    die("Veritabanına bağlanamadı: " . $e->getMessage());
  }

  $sql = 'SELECT  users.username,posts.title, posts.body,posts.id  FROM posts INNER JOIN users ON posts.userId =users.id';
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $results = json_encode($results);
  $response->getBody()->write($results);
  return $response->withHeader('Content-Type', 'application/json');
});



$app->run();

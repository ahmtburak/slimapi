<?php

namespace App\Models;

use App\Database;
use PDO;
use PDOException;


class PostModel
{
  protected $pdo;

  //Model çağrıldığında database bağlantısını hazır hale getiriyoruz ve her fonksiyonda çağırmayıp kod tekrarını engelliyoruz.
  public function __construct()
  {
    //Veritabanına bağlantı kontrolü yapıyoruz. Hata varsa yazdırıyoruz
    try {
      $database = new Database();
      $this->pdo = $database->getConnection();
    } catch (PDOException $e) {
      die("Veritabanına bağlanırken bir hata oluştu: " . $e->getMessage());
    }
  }
  public function fetchPosts($url)
  {
    $postsResponse = file_get_contents($url);
    $postsData = json_decode($postsResponse, true);

    //Eklenen postlar için ve veritabanında bulunanlar için count tutuyorum.
    $addedPostsCount = 0;
    $existingPostsCount = 0;

    //Foreach ile apiden gelen veriyi kontrol ederek ekleme
    try {
      foreach ($postsData as $value) {
        // Veritabanında aynı id'de veri eklenmiş mi kontrol etme 
        $checkSql = "SELECT COUNT(*) FROM posts WHERE id = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$value['id']]);
        $exists = $checkStmt->fetchColumn();

        // Aynı veri bulunmazsa veriyi ekle
        if ($exists == 0) {
          $sql = "INSERT INTO posts (userId, id, body, title) VALUES (?, ?, ?, ?)";
          $stmt = $this->pdo->prepare($sql);
          $stmt->execute([$value['userId'], $value['id'], $value['body'], $value['title']]);
          $addedPostsCount++;
        } else {
          $existingPostsCount++;
        }
      }
      return "Posts tablosuna $addedPostsCount veri başarıyla eklendi. $existingPostsCount veri zaten mevcut.\n";
    } catch (PDOException $e) {
      return "API'den veritabanına yazılırken bir hata oluştu: " . $e->getMessage();
    }
  }

  //Veritabanımıza yüklediğimiz verileri çekiyoruz
  public function getAllData()
  {

    // Veri çekme işlemi için kontrol ekliyoruz. Sonucu yazdırıyoruz.
    try {
      $sql = 'SELECT users.username, posts.title, posts.body, posts.id 
              FROM posts 
              INNER JOIN users ON posts.userId = users.id';
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return json_encode($results);
    } catch (PDOException $e) {
      return 'Veritabanından veri çekerken bir hata oluştu: ' . $e->getMessage();
    }
  }

  public function deletePost($postId)
  {
    // Silme işlemi için try-catch atarak kontrol yapıyoruz.
    try {
      $sql = 'DELETE FROM posts WHERE id = ?';
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$postId]);
      return 'Silme işlemi başarılı.';
    } catch (PDOException $e) {
      return 'Silme işleminde bir hata oluştu: ' . $e->getMessage();
    }
  }
}

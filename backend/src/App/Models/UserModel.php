<?php

namespace App\Models;

use App\Database;
use PDO;
use PDOException;

class UserModel
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

  public function fetchUsers($url)
  {
    $usersResponse = file_get_contents($url);
    $usersData = json_decode($usersResponse);

    //Eklenen userlar için ve veritabanında bulunanlar için count tutuyorum.
    $addedUsersCount = 0;
    $existingUsersCount = 0;

    //Foreach ile apiden gelen veriyi kontrol ederek ekleme
    try {
      foreach ($usersData as $value) {
        $checkSql = "SELECT COUNT(*) FROM users WHERE id = ?";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute([$value->id]);
        $exists = $checkStmt->fetchColumn();

        // Aynı veri bulunmazsa veriyi ekle
        if ($exists == 0) {
          $sql = "INSERT INTO users (id, name, username, email, address, phone, website, company) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = $this->pdo->prepare($sql);
          //Array olan verileri JSON olarak eklemek için
          $value->address = json_encode($value->address);
          $value->company = json_encode($value->company);
          $stmt->execute([
            $value->id,
            $value->name,
            $value->username,
            $value->email,
            $value->address,
            $value->phone,
            $value->website,
            $value->company
          ]);
          $addedUsersCount++;
        } else {
          $existingUsersCount++;
        }
      }
      return "Users tablosuna $addedUsersCount veri başarıyla eklendi. $existingUsersCount veri zaten mevcut.\n";
    } catch (PDOException $e) {
      return "API'den veritabanına yazılırken bir hata oluştu: " . $e->getMessage();
    }
  }
}

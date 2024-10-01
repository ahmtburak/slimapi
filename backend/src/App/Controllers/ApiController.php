<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\UserModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ApiController
{
  protected $postModel;
  protected $userModel;

  public function __construct()
  {
    $this->postModel = new PostModel();
    $this->userModel = new UserModel();
  }

  public function fetchApiDataDB(Request $request, Response $response, $args)
  {
    //Post API için işlem yapıyoruz
    $postsUrl = 'https://jsonplaceholder.typicode.com/posts';
    $postResult = $this->postModel->fetchPosts($postsUrl);
    $response->getBody()->write($postResult);

    //User API için işlem yapıyoruz
    $usersUrl = 'https://jsonplaceholder.typicode.com/users';
    $userResult = $this->userModel->fetchUsers($usersUrl);
    $response->getBody()->write($userResult);

    return $response;
  }

  // Kendi veritabanımızdan joinleyip birleştirdiğimiz veriyi çekme
  public function getAllData(Request $request, Response $response, $args)
  {
    $result = $this->postModel->getAllData();
    $response->getBody()->write($result);
    return $response->withHeader('Content-Type', 'application/json');
  }

  //Modele ID gönderip silme işlemini yapıyoruz.
  public function deletePost(Request $request, Response $response, $args)
  {
    $postId = $args['id'];
    $result = $this->postModel->deletePost($postId);
    $response->getBody()->write($result);
    return $response->withHeader('Content-Type', 'application/json');
  }
}

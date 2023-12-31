<?php
require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Resgatar o tipo do forumula´rio 
$type = filter_input(INPUT_POST, "type");
// Resgata dados do usuário
$userData = $userDao->verifyToken();


if ($type === "create") {
  // Recebendo os dados dos inputs
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $lenght = filter_input(INPUT_POST, "lenght");

  $movie = new Movie();

  // Validação mínima de dados
  if (!empty($title) && !empty($description) && !empty($category)) {

    $movie->title = $title;
    $movie->description = $description;
    $movie->category = $category;
    $movie->trailer = $trailer;
    $movie->lenght = $lenght;
    $movie->users_id = $userData->id;

    // Upload de imagem 
    if (isset($_FILES["image"])) {
      $image = $_FILES["image"];
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      $jpgArray = ["image/jpeg", "image/jpg"];
      // checando o tipo da imagem
      if (in_array($image["type"], $imageTypes)) {
        // Checar se imagem é jpg

        if (in_array($image["type"], $jpgArray)) {
          $imageFilde = imagecreatefromjpeg($image["tmp_name"]);
        } else {
          $imageFilde = imagecreatefrompng($image["tmp_name"]);
        }
        // Gerando o nome da imagem
        $imageName = $movie->imageGenerateName();
        // Salvando imagem na pasta 
        imagejpeg($imageFilde, "./img/movies/" . $imageName, 100);
        $movie->image = $imageName;
      } else {
        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
      }
    }

    $movieDao->create($movie);
  } else {
    $message->setMessage("Preencha os campos corretamente!", "error", "back");
  }
} else if ($type === "delete") {
  // Receber os dados do form
  $id = filter_input(INPUT_POST, "id");

  $movie = $movieDao->findById($id);
  var_dump($movie->users_id, $userData->id);

  if ($movie) {
    // verificar se o filme é do usuário
    if ($movie->users_id === $userData->id) {
      $movieDao->destroy($movie->id);
    }
  } else {
    $message->setMessage("Informações Inválidas", "error", "index.php");
  }
} else if ($type === "update") {
  // Recebendo os dados dos inputs
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $lenght = filter_input(INPUT_POST, "lenght");
  $id = filter_input(INPUT_POST, "id");

  $movieData = $movieDao->findById($id);

  // Verificar se veio algum filme 
  if ($movieData) {

    if ($movieData->users_id === $userData->id) {
        // Validação mínima de dados
  if (!empty($title) && !empty($description) && !empty($category)) {
      // Edição do filme
      $movieData->title = $title;
      $movieData->description= $description;
      $movieData->trailer = $trailer;
      $movieData->category = $category;
      $movieData->lenght = $lenght;

       // Upload de imagem 
    if (isset($_FILES["image"])) {
      $image = $_FILES["image"];
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      $jpgArray = ["image/jpeg", "image/jpg"];
      // checando o tipo da imagem
      if (in_array($image["type"], $imageTypes)) {
        // Checar se imagem é jpg

        if (in_array($image["type"], $jpgArray)) {
          $imageFilde = imagecreatefromjpeg($image["tmp_name"]);
        } else {
          $imageFilde = imagecreatefrompng($image["tmp_name"]);
        }
        // Gerando o nome da imagem
        $imageName = $movieData->imageGenerateName();
        // Salvando imagem na pasta 
        imagejpeg($imageFilde, "./img/movies/" . $imageName, 100);
        $movieData->image = $imageName;
      } else {
        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
      }
    }

    $movieDao->update($movieData);

  } else {
    $message->setMessage("Preencha os campos corretamente!", "error", "back");
  }
    } else {
      $message->setMessage("Informações Inválidas", "error", "index.php");
    }
  } else {
    $message->setMessage("Informações Inválidas", "error", "index.php");
  }
} else {
  $message->setMessage("Informações Inválidas" . "error", "index.php");
}

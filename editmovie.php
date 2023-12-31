<?php
require_once("templates/header.php");

// Verificar se o Usuário está autenticado
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$user = new User();
$userDao = new UserDao($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);

$movieDao = new MovieDAO($conn, $BASE_URL);

$id = filter_input(INPUT_GET, "id");
if (empty($id)) {
  $message->setMessage("O filme não foi encontrado!", "error", "index.php");
} else {
  $movie = $movieDao->findById($id);

  // Verifica se o filme existe
  if (!$movie) {
    $message->setMessage("O filme não foi encontrado!", "error", "index.php");
  }
}
// Checar se o filme tem imagem
if ($movie->image == "") {
  $movie->image = "movie_cover.jpg";
}
?>
<div id="main-container" class="container-fluid">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-6 offset-md-1">
        <h1><?= $movie->title ?></h1>
        <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
        <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="type" value="update">
          <input type="hidden" name="id" value="<?= $movie->id ?>">
          <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Título do filme" value="<?= $movie->title ?>">
          </div>
          <div class="form-group">
            <label for="image">Imagem:</label>
            <input type="file" name="image" id="image" class="form-control-file">
          </div>
          <div class="form-group">
            <label for="lenght">Duração</label>
            <input type="text" name="lenght" id="lenght" class="form-control" placeholder="Duração do filme" value="<?= $movie->lenght ?>">
          </div>
          <div class="form-group">
            <label for="category">Categoria</label>
            <select name="category" id="category" class="form-control">
              <option value="Ação" <?= $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
              <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
              <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
              <option value="Fantasia" <?= $movie->category === "Fantasia" ? "selected" : "" ?>>Fantasia</option>
              <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
            </select>
          </div>
          <div class="form-group">
              <label for="trailer">Trailer:</label>
              <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?= $movie->trailer ?>">
            </div>
          <div class="form-group">
            <label for="description">Descrição</label>
            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Descrição do filme"><?= $movie->description ?></textarea>
          </div>
          <input type="submit" class="btn card-btn" value="Editar Filme">
        </form>
      </div>
      <div class="col-md-3">
        <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>');"></div>
      </div>
    </div>
  </div>
</div>
<?php
require_once("templates/footer.php");
?>
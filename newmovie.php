<?php
  require_once("templates/header.php");

  // Verificar se o Usuário está autenticado
  require_once("models/User.php");
  require_once("dao/UserDAO.php");

  $user = new User();
  $userDao = new UserDao($conn, $BASE_URL);

  $userData = $userDao->verifyToken(true);
?>
<div id="main-container" class="container-fluid">
  <div class="offset-md-4 col-md-4 new-movie-container">
    <h1 class="page-title">Adicionar Filme</h1>
    <p class="page-description">Adicionar crítica</p>
    <form action="<?=$BASE_URL ?>movie_process.php" id="add-movie-form" method="post" enctype="multipart/form-data">
      <input type="hidden" name="type" value="create">
      <div class="form-group">
        <label for="title">Título</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Título do filme">
      </div>
      <div class="form-group">
        <label for="image">Imagem:</label>
        <input type="file" name="image" id="image" class="form-control-file">
      </div>
      <div class="form-group">
        <label for="lenght">Duração</label>
        <input type="text" name="lenght" id="lenght" class="form-control" placeholder="Duração do filme">
      </div>
      <div class="form-group">
        <label for="category">Categoria</label>
        <select name="category" id="category" class="form-control">
          <option value="Ação">Ação</option>
          <option value="Drama">Drama</option>
          <option value="Comédia">Comédia</option>
          <option value="Fantasia">Fantasia</option>
          <option value="Romance">Romance</option>
        </select>
      </div>
      <div class="form-group">
              <label for="trailer">Trailer:</label>
              <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
            </div>
      <div class="form-group">
        <label for="description">Descrição</label>
        <textarea name="description" id="description" cols="30" rows="5" class="form-control"
          placeholder="Descrição do filme"></textarea>
      </div>
      <input type="submit" class="btn card-btn" value="Adicionar filme">
    </form>
  </div>
</div>
<?php
  require_once("templates/footer.php");
?>
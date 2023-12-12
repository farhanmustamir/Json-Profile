<?php
  $url = "https://hibersunda.onrender.com/undakusukbasa";

  $data = file_get_contents($url);
  $result = json_decode($data, true);

  $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

  // Filter the results based on the search term
  $filteredResults = array_filter($result['words'], function($word) use ($searchTerm) {
    return (
      stripos($word['sorangan'], $searchTerm) !== false ||
      stripos($word['batur'], $searchTerm) !== false ||
      stripos($word['loma'], $searchTerm) !== false ||
      stripos($word['bindo'], $searchTerm) !== false ||
      stripos($word['english'], $searchTerm) !== false
    );
  });
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>API Bahasa Sunda</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">API</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login / Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <form method="GET" action="" class="search-form">
    <label for="search">Cari Kata:</label>
    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Cari</button>
  </form>

  <?php
    foreach ($filteredResults as $view) {
      echo '<div class="word-container">';
      echo '<div class="word-row">';
      echo '<div class="word-heading">Sorangan:</div>';
      echo '<div class="word-definition">' . $view['sorangan'] . '</div>';
      echo '</div>';
      echo '<div class="word-row">';
      echo '<div class="word-heading">Batur:</div>';
      echo '<div class="word-definition">' . $view['batur'] . '</div>';
      echo '</div>';
      echo '<div class="word-row">';
      echo '<div class="word-heading">Loma:</div>';
      echo '<div class="word-definition">' . $view['loma'] . '</div>';
      echo '</div>';
      echo '<div class="word-row">';
      echo '<div class="word-heading">Bindo:</div>';
      echo '<div class="word-definition">' . $view['bindo'] . '</div>';
      echo '</div>';
      echo '<div class="word-row">';
      echo '<div class="word-heading">English:</div>';
      echo '<div class="word-definition">' . $view['english'] . '</div>';
      echo '</div>';
      echo '<hr>';
      echo '</div>';
    }
  ?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($title); ?></title>
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    rel="stylesheet" />
  <!-- <link rel="stylesheet" href="/styles/style.css"> -->

  <style>
    <?php
    $css = file_get_contents(__DIR__ . '/../styles/style.css');
    echo $css;
    ?>
  </style>
</head>

<body>
  <?php include 'header.html'; ?>

  <main>
    {{content}}
  </main>

  <?php include 'footer.html'; ?>
</body>

</html>
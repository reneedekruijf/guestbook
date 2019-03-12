<?php 

include "includes/head.php";
include "includes/database.php";

$show = new ShowSingleId();

?>


<body>
  <main>
    <section>
      <h1 class="h1--name"><?= $show -> name()[0]["naam"]; ?></h1>
      
    <?php if ($show -> name() === null): ?>
      <article class="card card__noContent card--single">
        <p class="card__noContent__p">Er is iets mis gegaan. Ga terug naar de homepagina en probeer het openieuw!</p> 
      </article>
    <?php else: ?>
          <article class="card card--single">
            <h3 class="card__h3"><?= $show -> name()[0]["email"]; ?></h3>
            <p class="card__date"><?= $show -> name()[0]["published_at"]; ?></p>
            <p class="card__p card__p--review"><?= $show -> name()[0]["content"]; ?></p>
          </article>
    <?php endif; ?>
      <a href="index.php" class="button--home">home</a>
    </section>
  </main>

<?php 
  include "includes/footer.php";
?>
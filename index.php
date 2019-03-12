<?php include "includes/head.php"; ?>

<body>
  <main>
    <section>
      <h1>gastenboek</h1>
      <form method="post" id="form" novalidate>
        <fieldset>
          <legend>Beschrijf uw ervaring</legend>
          <div>
            <label for="naam">naam</label>
            <input name="naam"  type="text" id="naam" placeholder="Uw naam">
          </div>
          <div>
            <label for="email">email</label>
              <input name="email" type="email" id="email" placeholder="Uw e-mail adres">
          </div>
          <div>
            <label for="bericht">bericht</label>
              <textarea name="bericht" id="bericht" placeholder="Laat uw bericht achter"></textarea>
          </div>
        </fieldset>
          <button id="button">toevoegen</button>
      <div id="message" class="form__message"></div>
      </form>
    </section>
    <section id="insert__card"></section>
  </main>

<?php include "includes/footer.php"; ?>
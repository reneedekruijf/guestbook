/* eslint-disable */

// get the data from the php file
function getData() {

  $.ajax("get_data.php").done(function (data) {

    var results = JSON.parse(data);
    results.forEach((result) => {

      const card = `<a href="review.php?id=${result.id}">
      <article class="card">
      <h3 class="card__h3" id="card__h3">${result.naam}</h3>
      <p class="card__date" id="card__date">${result.published_at}</p>
      <p class="card__p" id="card__p">${result.content}</p>
      <span class="card__arrow">&nearr;</span>
      </article>
      </a>`;
      insertCard.append(card);
    })
  });
};

// show a message when data is succesfully send to the php file
function succesMessage(data) {
  insertCard.empty();
  $('input').val('');
  $('#bericht').val('');
  getData();
  message.append(data.responseJSON.msg);
}

// show an errormessage when the input is empty or incorrect
var click = false;

function errorMessage(data) {
  $('label').each(function () {
    if ($(this).attr("for") === "naam" && !click && data.responseJSON.error_name) {
      $(this).after(`<span class="form__Error">${data.responseJSON.error_name}<span>`);
    } else if ($(this).attr("for") === "email" && !click && data.responseJSON.error_mail) {
      $(this).after(`<span class="form__Error">${data.responseJSON.error_mail}<span>`);
    } else if ($(this).attr("for") === "bericht" && !click && data.responseJSON.error_bericht) {
      $(this).after(`<span class="form__Error">${data.responseJSON.error_bericht}<span>`);
    }
  });
  click = true;
}

const insertCard = $("#insert__card");

getData();

var message = $('#message');

// send the data of the form to the php file
$(document).ready(function () {
  $("#button").on("click", function (e) {

    e.preventDefault();
    $('#message').empty();

    var name = $('#naam').val();
    var email = $('#email').val();
    var content = $('#bericht').val();


    $.ajax({
      type: "post",
      url: "send_data.php",
      dataType: "json",
      data: { naam: name, email: email, bericht: content },
      ContentType: "application/json",
      complete: function (data) {
        if (data.responseJSON.code == "200") {
          succesMessage(data); // succesmessage
        } else {
          errorMessage(data); // errormessage
        }
      },
      fail: function (error) {
        message.append(error.responseText);
      }
    });
  })
});

// empty the form on focus of an input
$('input').on('focus', function () {
  $('#message').empty();
  $('.form__Error').remove();
  click = false;
});
$('#bericht').on('focus', function () {
  $('#message').empty();
  $('.form__Error').remove();
  click = false;
});

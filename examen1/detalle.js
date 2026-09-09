// Lógica de la página de detalle (reseña.html)
const API_URL_DETALLE = 'https://api.imdbapi.dev/titles/';

let peliculasLocal = [];
let peliculaActual = null;

$(document).ready(function () {
  actualizarContadorFavoritos();

  const params = new URLSearchParams(window.location.search);
  const id = params.get('id');

  if (!id) {
    mostrarError();
  } else {
    cargarPelicula(id);
  }

  // Modal de favoritos: se necesitan las 250 peliculas locales para poder listarlas
  $('#btnFavoritos').on('click', function () {
    if (peliculasLocal.length) {
      renderModalFavoritos(peliculasLocal);
    } else {
      $.getJSON('peliculas.json').done(function (data) {
        peliculasLocal = data;
        renderModalFavoritos(peliculasLocal);
      });
    }
    $('#favoritosModal').modal('show');
  });

  $('#btnEliminarTodos').on('click', function () {
    eliminarTodosFavoritos();
    renderModalFavoritos(peliculasLocal);
    actualizarBotonFavorito();
  });

  $(document).on('click', '.eliminar-favorito', function () {
    const idFav = $(this).data('id');
    eliminarFavorito(idFav);
    renderModalFavoritos(peliculasLocal);
    actualizarBotonFavorito();
  });

  $('#btnFavoritoDetalle').on('click', function () {
    if (!peliculaActual) return;
    toggleFavorito(peliculaActual.id);
    actualizarBotonFavorito();
  });
});

function cargarPelicula(id) {
  $('#loadingSpinner').removeClass('d-none');
  $('#detalleContainer').addClass('d-none');
  $('#errorContainer').addClass('d-none');

  $.ajax({
    url: API_URL_DETALLE + id,
    method: 'GET',
    dataType: 'json',
    timeout: 5000
  })
    .done(function (data) {
      peliculaActual = data;
      mostrarPelicula(data);
    })
    .fail(function () {
      // Si la API falla, se busca la película en el archivo local
      $.getJSON('peliculas.json')
        .done(function (data) {
          peliculasLocal = data;
          const encontrada = data.find(p => p.id === id);
          if (encontrada) {
            peliculaActual = encontrada;
            mostrarPelicula(encontrada);
          } else {
            mostrarError();
          }
        })
        .fail(mostrarError);
    });
}

function mostrarError() {
  $('#loadingSpinner').addClass('d-none');
  $('#errorContainer').removeClass('d-none');
}

function formatoMoneda(valor) {
  if (!valor && valor !== 0) return 'N/A';
  return '$' + Number(valor).toLocaleString('en-US');
}

function badgesHTML(lista, clase) {
  if (!lista || !lista.length) return '<span class="text-muted">N/A</span>';
  return lista.map(item => `<span class="badge ${clase} me-1 mb-1">${item}</span>`).join('');
}

function mostrarPelicula(p) {
  $('#loadingSpinner').addClass('d-none');

  $('#poster').attr('src', p.primaryImage || 'https://via.placeholder.com/350x520?text=Sin+imagen');
  $('#poster').attr('alt', p.primaryTitle || '');
  $('#titulo').text(p.primaryTitle || 'Sin título');
  $('#anio').html(`<i class="bi bi-calendar"></i> ${p.startYear || 'N/A'}`);
  $('#duracion').text(p.runtimeMinutes || 'N/A');
  $('#clasificacion').text(p.contentRating || 'N/A');

  $('#rating').text(p.averageRating || 'N/A');
  $('#estrellas').html(generarEstrellas(p.averageRating));
  $('#numVotos').text((p.numVotes || 0).toLocaleString('en-US'));

  $('#generos').html(badgesHTML(p.genres, 'bg-primary'));
  $('#intereses').html(badgesHTML(p.interests, 'bg-secondary'));

  const metascore = p.metascore;
  if (metascore || metascore === 0) {
    const color = metascore >= 70 ? 'bg-success' : (metascore >= 50 ? 'bg-warning' : 'bg-danger');
    $('#metascoreBar').css('width', metascore + '%').attr('class', 'progress-bar ' + color);
    $('#metascoreTexto').text(metascore + ' / 100');
  } else {
    $('#metascoreBar').css('width', '0%');
    $('#metascoreTexto').text('N/A');
  }

  $('#sinopsis').text(p.description || 'Sin sinopsis disponible.');
  $('#idiomas').text((p.spokenLanguages || []).join(', ') || 'N/A');
  $('#paises').text((p.countriesOfOrigin || []).join(', ') || 'N/A');
  $('#presupuesto').text(formatoMoneda(p.budget));
  $('#recaudacion').text(formatoMoneda(p.grossWorldwide));

  const productoras = (p.productionCompanies || []).map(pc => pc.name || pc).join(', ');
  $('#productoras').text(productoras || 'N/A');

  if (p.trailer) {
    $('#btnTrailer').attr('href', p.trailer).removeClass('d-none');
  } else {
    $('#btnTrailer').addClass('d-none');
  }

  const $enlaces = $('#enlacesExternos').empty();
  if (p.externalLinks && p.externalLinks.length) {
    p.externalLinks.forEach(link => {
      $enlaces.append(`<a href="${link}" target="_blank" class="d-block small">${link}</a>`);
    });
  } else {
    $enlaces.text('N/A');
  }

  actualizarBotonFavorito();

  $('#detalleContainer').removeClass('d-none').hide().fadeIn(400);
}

function actualizarBotonFavorito() {
  if (!peliculaActual) return;
  const activo = esFavorito(peliculaActual.id);
  $('#btnFavoritoDetalle').toggleClass('btn-outline-danger', !activo).toggleClass('btn-danger', activo);
  $('#iconoFavoritoDetalle').toggleClass('bi-heart', !activo).toggleClass('bi-heart-fill', activo);
}

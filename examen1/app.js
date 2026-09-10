const API_URL = 'https://api.imdbapi.dev/titles/top-rated';

let peliculas = [];
let decadaActual = 'todos';

$(document).ready(function () {
  actualizarContadorFavoritos();
  cargarPeliculas();

  $('#filtrosDecada button').on('click', function () {
    $('#filtrosDecada button').removeClass('active');
    $(this).addClass('active');
    decadaActual = $(this).data('decada');
    renderMovies();
  });

  $('#buscador').on('keyup', function () {
    renderMovies();
  });

  $('#formBuscador').on('submit', function (e) {
    e.preventDefault();
  });

  $('#btnReintentar').on('click', cargarPeliculas);

  $('#btnFavoritos').on('click', function () {
    renderModalFavoritos(peliculas);
    $('#favoritosModal').modal('show');
  });

  $('#btnEliminarTodos').on('click', function () {
    eliminarTodosFavoritos();
    renderModalFavoritos(peliculas);
    renderMovies();
  });

  $(document).on('click', '.eliminar-favorito', function () {
    const id = $(this).data('id');
    eliminarFavorito(id);
    renderModalFavoritos(peliculas);
    renderMovies();
  });

  $(document).on('click', '.favorite-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    const id = $(this).data('id');
    const activo = toggleFavorito(id);
    $(this).toggleClass('active', activo)
      .toggleClass('bi-heart', !activo)
      .toggleClass('bi-heart-fill', activo);
  });
});

function cargarPeliculas() {
  $('#errorContainer').addClass('d-none');
  $('#loadingSpinner').removeClass('d-none');
  $('#moviesGrid').empty();

  $.ajax({
    url: API_URL,
    method: 'GET',
    dataType: 'json',
    timeout: 5000
  })
    .done(function (data) {
      peliculas = Array.isArray(data) ? data : (data.titles || []);
      if (!peliculas.length) throw new Error('Respuesta vacía');
      finalizarCarga();
    })
    .fail(function () {
      // Si falla la API, se usa el archivo local peliculas.json
      $.getJSON('peliculas.json')
        .done(function (data) {
          peliculas = data;
          finalizarCarga();
        })
        .fail(function () {
          $('#loadingSpinner').addClass('d-none');
          $('#errorContainer').removeClass('d-none');
        });
    });
}

function finalizarCarga() {
  $('#loadingSpinner').addClass('d-none');
  renderMovies();
}

function estaEnRango(anio, decada) {
  if (decada === 'todos') return true;
  const [inicio, fin] = decada.split('-').map(Number);
  return anio >= inicio && anio <= fin;
}

function renderMovies() {
  const texto = $('#buscador').val().toLowerCase().trim();
  const $grid = $('#moviesGrid');
  $grid.empty();

  const filtradas = peliculas.filter(p => {
    const coincideTexto = (p.primaryTitle || '').toLowerCase().includes(texto);
    const coincideDecada = estaEnRango(p.startYear, decadaActual);
    return coincideTexto && coincideDecada;
  });

  if (filtradas.length === 0) {
    $grid.html('<div class="col-12 text-center text-muted py-5">No se encontraron películas.</div>');
    return;
  }

  filtradas.forEach(p => {
    const img = p.primaryImage || 'https://via.placeholder.com/300x445?text=Sin+imagen';
    const favoritoActivo = esFavorito(p.id);
    const iconoCorazon = favoritoActivo ? 'bi-heart-fill' : 'bi-heart';

    const card = `
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card h-100 movie-card">
          <div class="poster-wrap">
            <img src="${img}" class="card-img-top" alt="${p.primaryTitle}">
            <span class="rating-badge"><i class="bi bi-star-fill"></i> ${p.averageRating || 'N/A'}</span>
            <span class="year-badge">${p.startYear || 'N/A'}</span>
          </div>
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <h6 class="card-title mb-1">${p.primaryTitle}</h6>
              <i class="bi ${iconoCorazon} favorite-btn ${favoritoActivo ? 'active' : ''}" data-id="${p.id}"></i>
            </div>
            <div class="mb-3 small">${generarEstrellas(p.averageRating)}</div>
            <a href="resena.html?id=${p.id}" class="btn btn-primary mt-auto">Ver reseña</a>
          </div>
        </div>
      </div>
    `;
    $grid.append(card);
  });

  $('.movie-card').hover(
    function () { $(this).addClass('card-hover'); },
    function () { $(this).removeClass('card-hover'); }
  );
}
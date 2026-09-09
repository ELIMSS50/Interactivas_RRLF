// Lógica de la página de inicio: carga de datos, filtros, búsqueda y favoritos
const API_URL = 'https://api.imdbapi.dev/titles/top-rated';

let peliculas = [];
let decadaActual = 'todos';

$(document).ready(function () {
  actualizarContadorFavoritos();
  cargarPeliculas();

  // Filtro por década
  $('#filtrosDecada button').on('click', function () {
    $('#filtrosDecada button').removeClass('active');
    $(this).addClass('active');
    decadaActual = $(this).data('decada');
    renderMovies();
  });

  // Busqueda en tiempo real
  $('#buscador').on('keyup', function () {
    renderMovies();
  });

  // Reintentar carga
  $('#btnReintentar').on('click', cargarPeliculas);

  // Abrir modal de favoritos
  $('#btnFavoritos').on('click', function () {
    renderModalFavoritos(peliculas);
    $('#favoritosModal').modal('show');
  });

  // Eliminar todos los favoritos
  $('#btnEliminarTodos').on('click', function () {
    eliminarTodosFavoritos();
    renderModalFavoritos(peliculas);
    renderMovies();
  });

  // Delegación: favorito individual desde el modal y desde las cards
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
    $(this).toggleClass('active', activo);
    $(this).find('i').toggleClass('bi-heart bi-heart-fill');
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
          <img src="${img}" class="card-img-top" alt="${p.primaryTitle}">
          <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start">
              <h6 class="card-title mb-1">${p.primaryTitle}</h6>
              <i class="bi ${iconoCorazon} favorite-btn ${favoritoActivo ? 'active' : ''}" data-id="${p.id}"></i>
            </div>
            <div class="d-flex justify-content-between text-muted small mb-2">
              <span>${p.startYear || 'N/A'}</span>
              <span>${p.averageRating || 'N/A'} <i class="bi bi-star-fill text-warning"></i></span>
            </div>
            <div class="text-warning mb-2">${generarEstrellas(p.averageRating)}</div>
            <a href="resena.html?id=${p.id}" class="btn btn-primary mt-auto"><i class="bi bi-eye"></i> Ver reseña</a>
          </div>
        </div>
      </div>
    `;
    $grid.append(card);
  });

  // Efecto de elevación con jQuery .hover()
  $('.movie-card').hover(
    function () { $(this).addClass('card-hover'); },
    function () { $(this).removeClass('card-hover'); }
  );
}

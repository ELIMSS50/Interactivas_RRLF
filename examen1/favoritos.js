const FAVORITOS_KEY = 'movieReviews_favoritos';

function getFavoritos() {
  const data = localStorage.getItem(FAVORITOS_KEY);
  return data ? JSON.parse(data) : [];
}

function guardarFavoritos(favoritos) {
  localStorage.setItem(FAVORITOS_KEY, JSON.stringify(favoritos));
}

function esFavorito(id) {
  return getFavoritos().includes(id);
}

function toggleFavorito(id) {
  let favoritos = getFavoritos();
  if (favoritos.includes(id)) {
    favoritos = favoritos.filter(favId => favId !== id);
  } else {
    favoritos.push(id);
  }
  guardarFavoritos(favoritos);
  actualizarContadorFavoritos();
  return esFavorito(id);
}

function eliminarFavorito(id) {
  const favoritos = getFavoritos().filter(favId => favId !== id);
  guardarFavoritos(favoritos);
  actualizarContadorFavoritos();
}

function eliminarTodosFavoritos() {
  guardarFavoritos([]);
  actualizarContadorFavoritos();
}

function actualizarContadorFavoritos() {
  $('#favoritosCount').text(getFavoritos().length);
}

function generarEstrellas(rating) {
  const estrellas = Math.round((rating || 0) / 2);
  let html = '';
  for (let i = 1; i <= 5; i++) {
    html += i <= estrellas ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
  }
  return html;
}

function renderModalFavoritos(peliculas) {
  const favoritos = getFavoritos();
  const $body = $('#favoritosModalBody');
  const $count = $('#favoritosModalCount');
  $count.text(favoritos.length);

  if (favoritos.length === 0) {
    $body.html(`
      <div class="text-center py-4">
        <i class="bi bi-heart display-4 text-secondary"></i>
        <p class="mt-3 mb-1">No tienes películas favoritas</p>
        <p class="text-muted small">Agrega algunas desde la página de inicio</p>
      </div>
    `);
    return;
  }

  const peliculasFavoritas = peliculas.filter(p => favoritos.includes(p.id));
  let html = '<div class="row g-3">';
  peliculasFavoritas.forEach(p => {
    const img = p.primaryImage || 'https://via.placeholder.com/150x220?text=Sin+imagen';
    html += `
      <div class="col-6 col-md-3">
        <div class="card h-100">
          <img src="${img}" class="card-img-top fav-mini-img" alt="${p.primaryTitle}">
          <div class="card-body p-2">
            <p class="small fw-bold mb-1 text-truncate">${p.primaryTitle}</p>
            <p class="small text-muted mb-2">${p.startYear || ''} &middot; ${(p.averageRating || 0)} <i class="bi bi-star-fill text-warning"></i></p>
            <div class="d-flex gap-1">
              <a href="resena.html?id=${p.id}" class="btn btn-primary btn-sm flex-fill">Ver</a>
              <button class="btn btn-outline-danger btn-sm eliminar-favorito" data-id="${p.id}"><i class="bi bi-x"></i></button>
            </div>
          </div>
        </div>
      </div>
    `;
  });
  html += '</div>';
  $body.html(html);
}

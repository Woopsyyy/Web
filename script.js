document.addEventListener('DOMContentLoaded', function() {
  const modalElement = document.getElementById('modal');
  // Only wire modal helpers if modal exists on this page
  if (modalElement) {
    const modalImg = document.getElementById('modalImg');
    const modalTitle = document.getElementById('modalTitle');
    const modalDesc = document.getElementById('modalDesc');
    const closeModal = document.getElementById('closeModal');
    const modal = new bootstrap.Modal(modalElement);

    // Optional legacy clickable images support if present
    document.querySelectorAll('.card-image.clickable').forEach(function(img) {
      img.addEventListener('click', function() {
        const key = img.id;
        const dataAttr = img.dataset;
        if (dataAttr && dataAttr.src) {
          if (modalImg) modalImg.src = dataAttr.src;
          if (modalTitle) modalTitle.textContent = dataAttr.title || '';
          if (modalDesc) modalDesc.textContent = dataAttr.desc || '';
          modal.show();
        }
      });
    });

    if (closeModal) {
      closeModal.addEventListener('click', function() {
        modal.hide();
      });
    }

    modalElement.addEventListener('click', function(e) {
      if (e.target === this) {
        modal.hide();
      }
    });
  }

  // Remove dead code: legacy section toggles do not exist

});

// Login functionality

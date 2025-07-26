document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('modal');
  const modalImg = document.getElementById('modalImg');
  const modalTitle = document.getElementById('modalTitle');
  const modalDesc = document.getElementById('modalDesc');
  const closeModal = document.getElementById('closeModal');

  // Card data for modal
  const cardData = {
    chickenCurryImg: {
      img: 'Images/Chicken curry.jpg',
      title: 'Chicken curry',
      desc: 'Chicken curry is a flavorful dish made by simmering chicken pieces in a spiced gravy. It typically includes ingredients like onions, garlic, ginger, tomatoes, and a mix of spices such as turmeric, cumin, coriander, and chili powder. The curry can be made with or without coconut milk or cream, depending on the regional style. It\'s usually served with rice or bread like naan or roti. Popular across South Asia and around the world, chicken curry is known for its rich aroma, bold taste, and comforting warmth.'
    },
    butterChickenImg: {
      img: 'Images/Buter Chicken.jpg',
      title: 'Butter Chicken',
      desc: 'Butter Chicken is a rich and creamy North Indian dish made with tender chicken pieces cooked in a tomato-based sauce, butter, and a blend of aromatic spices. The sauce is smooth, slightly sweet, and mildly spiced, making it a favorite for many. It\'s best enjoyed with naan or steamed rice.'
    },
    crispyChilliChickenImg: {
      img: 'Images/Crispy Chilli Chicken.jpg',
      title: 'Crispy Chilli Chicken',
      desc: 'Crispy Chilli Chicken is a popular Indo-Chinese dish featuring battered and fried chicken tossed in a spicy, tangy sauce with bell peppers, onions, and green chilies. It\'s known for its crispy texture and bold flavors, perfect as an appetizer or with fried rice.'
    }
  };

  // Add event listeners to all .card-image elements
  document.querySelectorAll('.card-image.clickable').forEach(function(img) {
    img.addEventListener('click', function() {
      const key = img.id;
      if (cardData[key]) {
        modalImg.src = cardData[key].img;
        modalImg.alt = cardData[key].title;
        modalTitle.textContent = cardData[key].title;
        modalDesc.textContent = cardData[key].desc;
        modal.style.display = 'block';
      }
    });
  });

  closeModal.addEventListener('click', function() {
    modal.style.display = 'none';
  });

  window.addEventListener('click', function(e) {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });

  const menuBtn = document.getElementById('menuBtn');
  const menuSection = document.getElementById('menuSection');
  const closeMenuSection = document.getElementById('closeMenuSection');
  const branchBtn = document.getElementById('branchBtn');
  const aboutBtn = document.getElementById('aboutBtn');
  const branchSection = document.getElementById('branchSection');
  const aboutSection = document.getElementById('aboutSection');
  const closeBranchSection = document.getElementById('closeBranchSection');
  const closeAboutSection = document.getElementById('closeAboutSection');

  function hideAllSections() {
    menuSection.classList.remove('show');
    branchSection.classList.remove('show');
    aboutSection.classList.remove('show');
  }

  menuBtn.addEventListener('click', function(e) {
    e.preventDefault();
    hideAllSections();
    menuSection.classList.add('show');
  });

  branchBtn.addEventListener('click', function(e) {
    e.preventDefault();
    hideAllSections();
    branchSection.classList.add('show');
  });

  aboutBtn.addEventListener('click', function(e) {
    e.preventDefault();
    hideAllSections();
    aboutSection.classList.add('show');
  });

  closeMenuSection.addEventListener('click', hideAllSections);
  closeBranchSection.addEventListener('click', hideAllSections);
  closeAboutSection.addEventListener('click', hideAllSections);

  window.addEventListener('click', function(e) {
    if (menuSection.classList.contains('show') && e.target === menuSection) hideAllSections();
    if (branchSection.classList.contains('show') && e.target === branchSection) hideAllSections();
    if (aboutSection.classList.contains('show') && e.target === aboutSection) hideAllSections();
  });

  // Search bar functionality
  const searchInput = document.querySelector('.input[type="text"]');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const query = searchInput.value.toLowerCase();
      document.querySelectorAll('.menu-grid .card').forEach(function(card) {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        const label = card.querySelector('.card-label') ? card.querySelector('.card-label').textContent.toLowerCase() : '';
        if (title.includes(query) || label.includes(query)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }
});

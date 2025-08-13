document.addEventListener('DOMContentLoaded', function() {
  const modalElement = document.getElementById('modal');
  const modalImg = document.getElementById('modalImg');
  const modalTitle = document.getElementById('modalTitle');
  const modalDesc = document.getElementById('modalDesc');
  const closeModal = document.getElementById('closeModal');

  // Bootstrap modal functionality
  const modal = new bootstrap.Modal(modalElement);

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
        modal.show();
      }
    });
  });
  
  closeModal.addEventListener('click', function() {
    modal.hide();
  });

  // Close modal when clicking outside
  modalElement.addEventListener('click', function(e) {
    if (e.target === this) {
      modal.hide();
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

  // Search bar functionality for menu page
  const menuSearchInput = document.getElementById('Search');
  if (menuSearchInput) {
    menuSearchInput.addEventListener('input', function() {
      const query = this.value.toLowerCase().trim();
      
      if (query === '') {
        // Show all cards when search is empty
        document.querySelectorAll('.menu-grid .card').forEach(function(card) {
          card.style.display = '';
        });
        hideNoResultsMessage();
        return;
      }
      
      // Search through menu cards
      let foundItems = 0;
      document.querySelectorAll('.menu-grid .card').forEach(function(card) {
        const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
        const label = card.querySelector('.card-label')?.textContent.toLowerCase() || '';
        
        if (title.includes(query) || label.includes(query)) {
          card.style.display = '';
          foundItems++;
        } else {
          card.style.display = 'none';
        }
      });
      
      // Show no results message if no items found
      if (foundItems === 0) {
        showNoResultsMessage(query);
      } else {
        hideNoResultsMessage();
      }
    });
    
    // Add clear search functionality
    menuSearchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        this.value = '';
        this.dispatchEvent(new Event('input'));
      }
    });
  }
  
  function showNoResultsMessage(query) {
    hideNoResultsMessage(); // Remove existing message first
    
    const noResultsDiv = document.createElement('div');
    noResultsDiv.className = 'no-results';
    noResultsDiv.innerHTML = `
      <p>No dishes found for "${query}"</p>
      <p>Try searching for: Chicken, Curry, Butter, or Crispy</p>
    `;
    
    const menuGrid = document.querySelector('.menu-grid');
    if (menuGrid) {
      menuGrid.appendChild(noResultsDiv);
    }
  }
  
  function hideNoResultsMessage() {
    const existingMessage = document.querySelector('.no-results');
    if (existingMessage) {
      existingMessage.remove();
    }
  }
});

// Search bar functionality for the new search bar
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const searchBtn = document.getElementById('searchBtn');

  if (searchInput && searchBtn) {
    // Search on input change
    searchInput.addEventListener('input', performSearch);
    
    // Search on button click
    searchBtn.addEventListener('click', performSearch);
    
    // Search on Enter key
    searchInput.addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        performSearch();
      }
    });
  }

  function performSearch() {
    const query = searchInput.value.toLowerCase().trim();
    
    if (query === '') {
      // If search is empty, show all items
      showAllItems();
      return;
    }

    // Search through menu items (if on menu page)
    const menuCards = document.querySelectorAll('.menu-grid .card');
    if (menuCards.length > 0) {
      searchMenuItems(query, menuCards);
    }

    // Search through feature sections (if on home page)
    const features = document.querySelectorAll('.feature, .feature2, .feature3');
    if (features.length > 0) {
      searchFeatures(query, features);
    }

    // Show search results message
    showSearchResults(query);
  }

  function searchMenuItems(query, cards) {
    let foundItems = 0;
    
    cards.forEach(card => {
      const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
      const label = card.querySelector('.card-label')?.textContent.toLowerCase() || '';
      const desc = card.querySelector('.card-desc')?.textContent.toLowerCase() || '';
      
      if (title.includes(query) || label.includes(query) || desc.includes(query)) {
        card.style.display = '';
        foundItems++;
      } else {
        card.style.display = 'none';
      }
    });

    return foundItems;
  }

  function searchFeatures(query, features) {
    let foundFeatures = 0;
    
    features.forEach(feature => {
      const button = feature.querySelector('.feature-btn');
      const buttonText = button?.textContent.toLowerCase() || '';
      
      if (buttonText.includes(query)) {
        feature.style.opacity = '1';
        feature.style.transform = 'scale(1.02)';
        foundFeatures++;
      } else {
        feature.style.opacity = '0.6';
        feature.style.transform = 'scale(1)';
      }
    });

    return foundFeatures;
  }

  function showAllItems() {
    // Reset menu items
    const menuCards = document.querySelectorAll('.menu-grid .card');
    menuCards.forEach(card => {
      card.style.display = '';
    });

    // Reset features
    const features = document.querySelectorAll('.feature, .feature2, .feature3');
    features.forEach(feature => {
      feature.style.opacity = '1';
      feature.style.transform = 'scale(1)';
    });

    // Clear search results message
    clearSearchResults();
  }

  function showSearchResults(query) {
    // Remove existing search results message
    clearSearchResults();
    
    const resultsDiv = document.createElement('div');
    resultsDiv.id = 'searchResults';
    resultsDiv.className = 'search-results';
    resultsDiv.innerHTML = `
      <div class="search-results-content">
        <span>Search results for: "${query}"</span>
        <button onclick="clearSearch()" class="clear-search-btn">Clear Search</button>
      </div>
    `;
    
    // Insert after search container
    const searchContainer = document.querySelector('.search-container');
    if (searchContainer) {
      searchContainer.parentNode.insertBefore(resultsDiv, searchContainer.nextSibling);
    }
  }

  function clearSearchResults() {
    const existingResults = document.getElementById('searchResults');
    if (existingResults) {
      existingResults.remove();
    }
  }

  // Global function for clear search button
  window.clearSearch = function() {
    searchInput.value = '';
    showAllItems();
  };
});

// Login functionality

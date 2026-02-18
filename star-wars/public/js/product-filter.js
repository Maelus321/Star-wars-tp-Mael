// public/js/product-filter.js

class ProductFilter {
    constructor() {
        this.productsGrid = document.getElementById('productsGrid');
        this.searchInput = document.getElementById('searchInput');
        this.typeFilter = document.getElementById('typeFilter');
        
        // Attendre que le DOM soit complètement chargé
        if (!this.productsGrid || !this.searchInput || !this.typeFilter) {
            console.error('Éléments de filtre non trouvés');
            return;
        }
        
        // Récupérer toutes les cartes produits
        this.productCards = Array.from(document.querySelectorAll('.product-card'));
        
        // Vérifier qu'il y a des produits
        if (this.productCards.length === 0) {
            console.log('Aucun produit trouvé');
            return;
        }
        
        // Stocker les données des produits
        this.productsData = this.productCards.map(card => {
            // Récupérer le nom depuis le contenu texte plutôt que depuis data attribute
            const titleElement = card.querySelector('.product-title');
            const categoryElement = card.querySelector('.product-category');
            
            return {
                element: card,
                id: card.dataset.productId || '',
                // Utiliser le contenu texte du titre
                name: titleElement ? titleElement.textContent.toLowerCase().trim() : '',
                // Utiliser le contenu texte de la catégorie
                category: categoryElement ? categoryElement.textContent.toLowerCase().trim() : ''
            };
        });
        
        console.log('Produits chargés:', this.productsData); // Pour debug
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.filter();
    }
    
    setupEventListeners() {
        let searchTimeout;
        
        this.searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => this.filter(), 300);
        });
        
        this.typeFilter.addEventListener('change', () => this.filter());
    }
    
    filter() {
    const searchTerm = this.searchInput.value.toLowerCase().trim();
    const selectedType = this.typeFilter.value;

    let visibleCount = 0;

    this.productsData.forEach(product => {
        const matchesSearch = searchTerm === '' || 
            product.name.includes(searchTerm);

        const matchesType = selectedType === 'all' || 
            product.category === selectedType.toLowerCase();

        if (matchesSearch && matchesType) {

            product.element.classList.remove('filter-hide');
            product.element.style.display = '';
            
            // petit timeout pour déclencher l’animation
            requestAnimationFrame(() => {
                product.element.classList.add('filter-show');
            });

            visibleCount++;

            this.clearHighlights(product.element);

            if (searchTerm !== '') {
                this.highlightText(product.element, searchTerm);
            }

        } else {

            product.element.classList.remove('filter-show');
            product.element.classList.add('filter-hide');

            setTimeout(() => {
                product.element.style.display = 'none';
            }, 300);
        }
    });

    this.showEmptyState(visibleCount === 0);
}

    
    clearHighlights(cardElement) {
        const titleElement = cardElement.querySelector('.product-title');
        if (titleElement) {
            // Restaurer le texte original sans les balises mark
            const originalText = titleElement.textContent;
            titleElement.innerHTML = originalText;
        }
    }
    
    highlightText(cardElement, searchTerm) {
        const titleElement = cardElement.querySelector('.product-title');
        if (!titleElement) return;
        
        const originalText = titleElement.textContent;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        
        // Remplacer le texte par la version avec surbrillance
        titleElement.innerHTML = originalText.replace(
            regex, 
            '<span style="background-color: yellow; color: black; font-weight: bold;">$1</span>'
        );
    }
    
    showEmptyState(show) {
        // Supprimer l'ancien état vide s'il existe
        const oldEmptyState = document.querySelector('.empty-state-dynamic');
        if (oldEmptyState) {
            oldEmptyState.remove();
        }
        
        if (show) {
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-state empty-state-dynamic';
            emptyState.style.cssText = 'grid-column: 1 / -1; text-align: center; padding: 40px;';
            emptyState.innerHTML = `
                <p style="font-size: 18px; margin-bottom: 10px;">Aucun produit ne correspond à vos critères</p>
                <button onclick="document.getElementById('searchInput').value = ''; document.getElementById('typeFilter').value = 'all'; document.querySelector('.product-filter').filter();" 
                        style="background: yellow; color: black; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                    Réinitialiser les filtres
                </button>
            `;
            this.productsGrid.appendChild(emptyState);
        }
    }
    
    // Méthode pour réinitialiser les filtres
    resetFilters() {
        this.searchInput.value = '';
        this.typeFilter.value = 'all';
        this.filter();
    }
}

// Initialisation quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    // Ajouter une petite classe au conteneur pour faciliter la réinitialisation
    const filter = new ProductFilter();
    
    // Exposer l'instance globalement pour le bouton de réinitialisation
    window.productFilter = filter;
});
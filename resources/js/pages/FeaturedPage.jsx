import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import ProductCard from '../components/products/ProductCard';
import { mockProducts } from '../data/mockData';
import { FaFilter, FaSort } from 'react-icons/fa';

const FeaturedPage = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [sortOrder, setSortOrder] = useState('default');
  const [activeFilters, setActiveFilters] = useState([]);
  
  // Categories for filtering
  const categories = [...new Set(mockProducts.map(product => product.category))];
  
  useEffect(() => {
    // Simulate loading featured products from an API
    setTimeout(() => {
      // Add a featured flag to some products
      const featured = mockProducts.map(product => ({
        ...product,
        isFeatured: Math.random() > 0.3 // Random 70% of products are featured
      }));
      
      setProducts(featured);
      setLoading(false);
    }, 800);
  }, []);
  
  // Filter products based on active filters
  const filteredProducts = products
    .filter(product => product.isFeatured)
    .filter(product => activeFilters.length === 0 || activeFilters.includes(product.category));
  
  // Sort products based on current sort order
  const sortedProducts = [...filteredProducts].sort((a, b) => {
    switch(sortOrder) {
      case 'price-low':
        return (a.prixPromo || a.prix) - (b.prixPromo || b.prix);
      case 'price-high':
        return (b.prixPromo || b.prix) - (a.prixPromo || a.prix);
      case 'name-asc':
        return a.name.localeCompare(b.name);
      case 'name-desc':
        return b.name.localeCompare(a.name);
      default:
        return 0;
    }
  });
  
  const toggleFilter = (category) => {
    setActiveFilters(prev => 
      prev.includes(category)
        ? prev.filter(item => item !== category)
        : [...prev, category]
    );
  };
  
  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
      className="featured-page"
    >
      <div className="page-header">
        <div className="container">
          <h1>Featured Products</h1>
          <p>Discover our premium selection of hand-picked tech products</p>
        </div>
      </div>

      <div className="container">
        <div className="filter-sort-container">
          <div className="filter-section">
            <div className="filter-header">
              <FaFilter /> 
              <span>Filter by Category</span>
            </div>
            <div className="filter-options">
              {categories.map(category => (
                <button
                  key={category}
                  className={`filter-button ${activeFilters.includes(category) ? 'active' : ''}`}
                  onClick={() => toggleFilter(category)}
                >
                  {category}
                </button>
              ))}
            </div>
          </div>
          
          <div className="sort-section">
            <div className="sort-header">
              <FaSort />
              <span>Sort by</span>
            </div>
            <select 
              value={sortOrder}
              onChange={(e) => setSortOrder(e.target.value)}
              className="sort-select"
            >
              <option value="default">Featured</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="name-asc">Name: A to Z</option>
              <option value="name-desc">Name: Z to A</option>
            </select>
          </div>
        </div>
        
        {loading ? (
          <div className="loading-spinner">Loading featured products...</div>
        ) : (
          <>
            <div className="featured-count">
              <p>Showing {sortedProducts.length} featured products</p>
            </div>
            
            <motion.div 
              className="products-grid"
              initial="hidden"
              animate="visible"
              variants={{
                hidden: { opacity: 0 },
                visible: { 
                  opacity: 1,
                  transition: { staggerChildren: 0.1 }
                }
              }}
            >
              {sortedProducts.map(product => (
                <ProductCard key={product.id} product={product} />
              ))}
            </motion.div>
            
            {sortedProducts.length === 0 && (
              <div className="no-products">
                <h3>No products match your filter criteria</h3>
                <button 
                  className="clear-filters"
                  onClick={() => setActiveFilters([])}
                >
                  Clear Filters
                </button>
              </div>
            )}
          </>
        )}
      </div>
    </motion.div>
  );
};

export default FeaturedPage;

import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import ProductCard from '../components/products/ProductCard';
import { mockProducts } from '../data/mockData';
import { FaArrowLeft, FaFilter, FaSort } from 'react-icons/fa';

const CategoryDetailsPage = () => {
  const { slug } = useParams();
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [category, setCategory] = useState(null);
  const [sortOrder, setSortOrder] = useState('default');
  const [priceRange, setPriceRange] = useState([0, 2000]);
  
  // Category metadata with images and descriptions
  const categoryMeta = {
    'desktops': {
      name: 'Desktop PCs',
      description: 'High-performance gaming PCs and workstations built with premium components.',
      image: 'https://images.unsplash.com/photo-1593640495253-23196b27a87f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '💻'
    },
    'laptops': {
      name: 'Laptops',
      description: 'Portable computing solutions for professionals, gamers, and everyday users.',
      image: 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '💻'
    },
    'components': {
      name: 'Components',
      description: 'Essential hardware parts to build or upgrade your computer system.',
      image: 'https://images.unsplash.com/photo-1591488320449-011701bb6704?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '🔧'
    },
    'smartphones': {
      name: 'Smartphones',
      description: 'The latest mobile devices with cutting-edge technology and features.',
      image: 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '📱'
    },
    'peripherals': {
      name: 'Peripherals',
      description: 'Enhance your computing experience with keyboards, mice, and other accessories.',
      image: 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '⌨️'
    },
    'audio': {
      name: 'Audio',
      description: 'Premium sound equipment for an immersive audio experience.',
      image: 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '🎧'
    },
    'gaming': {
      name: 'Gaming',
      description: 'Level up your gaming setup with specialized gear and accessories.',
      image: 'https://images.unsplash.com/photo-1580327332925-a10e6cb11baa?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '🎮'
    },
    'networking': {
      name: 'Networking',
      description: 'Stay connected with reliable networking solutions for home and business.',
      image: 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80',
      icon: '🌐'
    }
  };
  
  useEffect(() => {
    // Set current category based on slug
    const currentCategory = categoryMeta[slug] || {
      name: slug.charAt(0).toUpperCase() + slug.slice(1),
      description: 'Browse our selection of products in this category',
      image: 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
      icon: '🛒'
    };
    
    setCategory(currentCategory);
    
    // Simulate loading products for this category
    setTimeout(() => {
      // Filter products based on category
      const categoryName = currentCategory.name;
      const filtered = mockProducts.filter(product => 
        product.category.toLowerCase() === categoryName.toLowerCase() ||
        product.category.toLowerCase().includes(slug.toLowerCase())
      );
      
      // If no exact matches, just use some random products as a fallback
      const categoryProducts = filtered.length > 0 ? filtered : 
        mockProducts.slice(0, 6).map(p => ({...p, category: currentCategory.name}));
      
      setProducts(categoryProducts);
      setLoading(false);
    }, 800);
  }, [slug]);
  
  // Handle price range change
  const handlePriceChange = (e, index) => {
    const newRange = [...priceRange];
    newRange[index] = Number(e.target.value);
    setPriceRange(newRange);
  };
  
  // Filter products based on price range
  const filteredProducts = products.filter(product => {
    const price = product.prixPromo || product.prix;
    return price >= priceRange[0] && price <= priceRange[1];
  });
  
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
  
  // Calculate slider track width for visual indication
  const minVal = priceRange[0];
  const maxVal = priceRange[1];
  const minPercent = ((minVal) / 2000) * 100;
  const maxPercent = ((maxVal) / 2000) * 100;
  const trackStyle = {
    left: `${minPercent}%`,
    width: `${maxPercent - minPercent}%`
  };
  
  if (loading) {
    return <div className="loading-spinner">Loading category products...</div>;
  }
  
  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
      className="category-details-page"
    >
      <div 
        className="category-banner"
        style={{ backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(${category.image})` }}
      >
        <div className="container">
          <Link to="/categories" className="back-link">
            <FaArrowLeft /> Back to Categories
          </Link>
          <div className="category-banner-content">
            <span className="category-icon">{category.icon}</span>
            <h1>{category.name}</h1>
            <p>{category.description}</p>
          </div>
        </div>
      </div>

      <div className="container">
        <div className="category-controls">
          <div className="filter-price">
            <h3>Price Range</h3>
            <div className="price-inputs">
              <div className="price-input-group">
                <label>Min</label>
                <div className="price-input-wrapper">
                  <span className="currency">€</span>
                  <input 
                    type="number" 
                    value={priceRange[0]} 
                    onChange={(e) => handlePriceChange(e, 0)} 
                    min="0" 
                    max={priceRange[1]}
                  />
                </div>
              </div>
              <span className="price-separator">-</span>
              <div className="price-input-group">
                <label>Max</label>
                <div className="price-input-wrapper">
                  <span className="currency">€</span>
                  <input 
                    type="number" 
                    value={priceRange[1]} 
                    onChange={(e) => handlePriceChange(e, 1)} 
                    min={priceRange[0]} 
                  />
                </div>
              </div>
            </div>
            <div className="price-range-slider">
              <input 
                type="range" 
                min="0" 
                max="2000" 
                value={priceRange[0]} 
                onChange={(e) => handlePriceChange(e, 0)} 
                className="slider slider-min"
              />
              <input 
                type="range" 
                min="0" 
                max="2000" 
                value={priceRange[1]} 
                onChange={(e) => handlePriceChange(e, 1)} 
                className="slider slider-max"
              />
              <div className="price-range-track" style={trackStyle}></div>
            </div>
          </div>
          
          <div className="sort-container">
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
        
        <div className="products-count">
          <p>Showing {sortedProducts.length} products in {category.name}</p>
        </div>
        
        {sortedProducts.length > 0 ? (
          <motion.div 
            className="products-grid category-products"
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
        ) : (
          <div className="no-products">
            <h3>No products match your filter criteria</h3>
            <button 
              className="reset-filters"
              onClick={() => setPriceRange([0, 2000])}
            >
              Reset Filters
            </button>
          </div>
        )}
      </div>
    </motion.div>
  );
};

export default CategoryDetailsPage;

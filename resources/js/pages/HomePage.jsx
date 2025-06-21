import React, { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Link } from 'react-router-dom';
import ProductCard from '../components/products/ProductCard';
import { mockProducts } from '../data/mockData';
import { FaDesktop, FaLaptop, FaMicrochip, FaMobile, FaKeyboard, FaHeadphones } from 'react-icons/fa';

const HomePage = () => {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  
  // Hero images - using product images from our mock data
  const heroImages = [
    mockProducts[0].image,
    mockProducts[1].image,
    mockProducts[2].image,
    mockProducts[4].image,
    mockProducts[6].image,
  ];
  
  useEffect(() => {
    // Simulate loading products from an API
    setTimeout(() => {
      setProducts(mockProducts);
      setLoading(false);
    }, 1000);
  }, []);
  
  // Set up image slider with 3-second interval
  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentImageIndex((prevIndex) => 
        prevIndex === heroImages.length - 1 ? 0 : prevIndex + 1
      );
    }, 3000);
    
    return () => clearInterval(interval);
  }, [heroImages.length]);
  
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: { 
      opacity: 1,
      transition: { 
        staggerChildren: 0.1
      } 
    }
  };
  
  // Group products by category for the categories section
  const categories = [
    { name: "Desktop PCs", icon: <FaDesktop />, path: "/category/desktops" },
    { name: "Laptops", icon: <FaLaptop />, path: "/category/laptops" },
    { name: "Components", icon: <FaMicrochip />, path: "/category/components" },
    { name: "Smartphones", icon: <FaMobile />, path: "/category/smartphones" },
    { name: "Peripherals", icon: <FaKeyboard />, path: "/category/peripherals" },
    { name: "Audio", icon: <FaHeadphones />, path: "/category/audio" },
  ];
  
  // Testimonials data
  const testimonials = [
    {
      id: 1,
      name: "Alex Johnson",
      role: "Tech Enthusiast",
      comment: "KOMPLEMON offers the best selection of gaming PCs I've seen. Their customer service is also top-notch!",
      avatar: "https://randomuser.me/api/portraits/men/32.jpg"
    },
    {
      id: 2,
      name: "Sarah Williams",
      role: "Software Developer",
      comment: "I bought my development workstation here and couldn't be happier. The performance is incredible.",
      avatar: "https://randomuser.me/api/portraits/women/44.jpg"
    },
    {
      id: 3,
      name: "Michael Chen",
      role: "Content Creator",
      comment: "The RTX 4090 I purchased has transformed my rendering workflow. Videos that took hours now finish in minutes!",
      avatar: "https://randomuser.me/api/portraits/men/22.jpg"
    }
  ];
  
  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
    >
      {/* Hero Section - No other changes needed as CSS will handle positioning */}
      <section className="hero-section">
        <div className="hero-slider">
          <AnimatePresence mode="wait">
            <motion.div 
              key={currentImageIndex}
              className="hero-slide"
              initial={{ opacity: 0, scale: 1.1 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.9 }}
              transition={{ duration: 1.2 }}
            >
              <img 
                src={heroImages[currentImageIndex]} 
                alt="Featured Product" 
                className="hero-image"
              />
              <div className="hero-content">
                <motion.h1 
                  initial={{ y: -20, opacity: 0 }}
                  animate={{ y: 0, opacity: 1 }}
                  transition={{ duration: 0.5, delay: 0.2 }}
                  className="hero-title"
                >
                  Next-Gen Tech at Your Fingertips
                </motion.h1>
                <motion.p
                  initial={{ y: 20, opacity: 0 }}
                  animate={{ y: 0, opacity: 1 }}
                  transition={{ duration: 0.5, delay: 0.4 }}
                  className="hero-description"
                >
                  Discover cutting-edge PCs, smartphones, and computer components 
                  at unbeatable prices
                </motion.p>
                <motion.button
                  initial={{ scale: 0.9, opacity: 0 }}
                  animate={{ scale: 1, opacity: 1 }}
                  transition={{ duration: 0.3, delay: 0.6 }}
                  className="hero-button"
                >
                  Shop Now
                </motion.button>
              </div>
            </motion.div>
          </AnimatePresence>
        </div>
      </section>
      
      {/* Categories Section */}
      <section className="categories-section">
        <div className="container">
          <h2 className="section-title">Browse Categories</h2>
          <div className="categories-grid">
            {categories.map((category, index) => (
              <motion.div 
                key={index}
                className="category-card"
                whileHover={{ y: -10, backgroundColor: "rgba(248, 161, 0, 0.1)" }}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: index * 0.1 }}
              >
                <Link to={category.path}>
                  <div className="category-icon">{category.icon}</div>
                  <h3>{category.name}</h3>
                </Link>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Featured Products Section - Enhanced */}
      <section className="featured-products">
        <div className="container">
          <div className="section-header">
            <h2 className="section-title">Featured Tech</h2>
            <Link to="/featured" className="view-all-link">View All</Link>
          </div>
          
          {loading ? (
            <div className="loading-spinner">Loading...</div>
          ) : (
            <motion.div 
              className="products-grid featured-grid"
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
              {products.slice(0, 4).map(product => (
                <ProductCard key={product.id} product={product} />
              ))}
            </motion.div>
          )}
        </div>
      </section>
      
      {/* Special Offer Banner */}
      <section className="special-offer-section">
        <div className="container">
          <div className="special-offer-content">
            <div className="offer-text">
              <h2>Summer Sale</h2>
              <h3>Up to 40% Off</h3>
              <p>On selected gaming PCs and laptops</p>
              <Link to="/sale" className="offer-button">Shop Now</Link>
            </div>
            <div className="offer-image">
              <img src="https://images.unsplash.com/photo-1593640495253-23196b27a87f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="Gaming PC" />
            </div>
          </div>
        </div>
      </section>
      
      {/* Latest Arrivals */}
      <section className="latest-arrivals">
        <div className="container">
          <div className="section-header">
            <h2 className="section-title">Latest Arrivals</h2>
            <Link to="/new" className="view-all-link">View All</Link>
          </div>
          
          <div className="arrivals-grid">
            {products.slice(4, 8).map(product => (
              <motion.div 
                key={product.id}
                className="arrival-card"
                whileHover={{ scale: 1.03 }}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3 }}
              >
                <div className="new-badge">NEW</div>
                <img src={product.image} alt={product.name} className="arrival-image" />
                <div className="arrival-details">
                  <h3>{product.name}</h3>
                  <p className="arrival-price">€{product.prixPromo || product.prix}</p>
                  <Link to={`/product/${product.id}`} className="view-details-button">View Details</Link>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Testimonials Section */}
      <section className="testimonials-section">
        <div className="container">
          <h2 className="section-title">What Our Customers Say</h2>
          <div className="testimonials-grid">
            {testimonials.map((testimonial, index) => (
              <motion.div 
                key={testimonial.id}
                className="testimonial-card"
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: index * 0.2 }}
              >
                <div className="testimonial-content">
                  <p>"{testimonial.comment}"</p>
                </div>
                <div className="testimonial-author">
                  <img src={testimonial.avatar} alt={testimonial.name} className="author-avatar" />
                  <div className="author-info">
                    <h4>{testimonial.name}</h4>
                    <p>{testimonial.role}</p>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
      
      {/* Newsletter Section */}
      <section className="newsletter-section">
        <div className="container">
          <div className="newsletter-content">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for the latest tech news and exclusive offers</p>
            <form className="newsletter-form">
              <input type="email" placeholder="Your email address" required />
              <button type="submit">Subscribe</button>
            </form>
          </div>
        </div>
      </section>
    </motion.div>
  );
};

export default HomePage;

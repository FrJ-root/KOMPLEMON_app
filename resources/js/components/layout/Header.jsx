import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { FaShoppingCart, FaSearch, FaUser, FaBars, FaTimes, FaSignInAlt, FaUserPlus } from 'react-icons/fa';
import logoImage from '../../assets/KOMPLEMON_logo.png';

const Header = () => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [cartCount, setCartCount] = useState(0);
  const [isTransparent, setIsTransparent] = useState(true);
  const [isHidden, setIsHidden] = useState(false);
  
  // Set up mouse movement detection
  useEffect(() => {
    let timeout;
    
    const handleMouseMove = () => {
      setIsTransparent(false);
      setIsHidden(false);
      
      // Clear any existing timeout
      if (timeout) {
        clearTimeout(timeout);
      }
      
      // Set timeout to make navbar transparent and then hidden after inactivity
      timeout = setTimeout(() => {
        setIsTransparent(true);
        
        // After becoming transparent, hide completely after another delay
        setTimeout(() => {
          setIsHidden(true);
        }, 1000);
      }, 3000);
    };
    
    // Add event listener
    window.addEventListener('mousemove', handleMouseMove);
    
    // Initial timeout
    timeout = setTimeout(() => {
      setIsTransparent(true);
      setTimeout(() => {
        setIsHidden(true);
      }, 1000);
    }, 3000);
    
    // Clean up
    return () => {
      window.removeEventListener('mousemove', handleMouseMove);
      if (timeout) clearTimeout(timeout);
    };
  }, []);
  
    const scrollToFooter = (section) => {
    const footer = document.querySelector('.footer');
    if (footer) {
      footer.scrollIntoView({ behavior: 'smooth' });
      sessionStorage.setItem('scrollToFooterSection', section);
    }
  };
  
  return (
    <header className={`header ${isTransparent ? 'transparent' : ''} ${isHidden ? 'hidden' : ''}`}>
      <div className="container header-content">
        <Link to="/" className="logo">
          <img 
            src={logoImage} 
            alt="KOMPLEMON" 
            className="logo-image" 
            style={{ height: '90px', maxWidth: '360px' }}
          />
        </Link>
        
        <div className={`nav-links ${mobileMenuOpen ? 'mobile-active' : ''}`}>
          <Link to="/">Home</Link>
          <Link to="/categories">Categories</Link>
          <Link to="/featured">Featured</Link>
          <a href="#" onClick={(e) => { e.preventDefault(); scrollToFooter('about'); }}>About</a>
          <a href="#" onClick={(e) => { e.preventDefault(); scrollToFooter('contact'); }}>Contact</a>
        </div>
        
        <div className="header-actions">
          <div className="auth-buttons">
            <Link to="/login" className="login-btn">
              <FaSignInAlt /> <span>Login</span>
            </Link>
            <Link to="/signup" className="signup-btn">
              <FaUserPlus /> <span>Sign Up</span>
            </Link>
          </div>
          
          <div className="icon-button-wrapper">
            <button className="icon-button search-button">
              <FaSearch className="icon-svg" />
              <span className="icon-tooltip">Search</span>
            </button>
          </div>
          
          <div className="icon-button-wrapper">
            <Link to="/account" className="icon-button account-button">
              <FaUser className="icon-svg" />
              <span className="icon-tooltip">Account</span>
            </Link>
          </div>
          
          <div className="icon-button-wrapper">
            <Link to="/cart" className="icon-button cart-button">
              <FaShoppingCart className="icon-svg" />
              {cartCount > 0 && <span className="cart-count">{cartCount}</span>}
              <span className="icon-tooltip">Cart</span>
            </Link>
          </div>
          
          <button 
            className="mobile-menu-toggle"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          >
            {mobileMenuOpen ? <FaTimes className="icon-svg" /> : <FaBars className="icon-svg" />}
          </button>
        </div>
      </div>
    </header>
  );
};

export default Header;
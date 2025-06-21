import React, { useEffect, useRef } from 'react';
import { Link } from 'react-router-dom';
import { FaFacebook, FaTwitter, FaInstagram, FaLinkedin, FaMapMarkerAlt, FaPhone, FaEnvelope, FaArrowRight } from 'react-icons/fa';

const Footer = () => {
  const aboutRef = useRef(null);
  const contactRef = useRef(null);
  
  useEffect(() => {
    const section = sessionStorage.getItem('scrollToFooterSection');
    if (section) {
      if (section === 'about' && aboutRef.current) {
        aboutRef.current.scrollIntoView({ behavior: 'smooth' });
      } else if (section === 'contact' && contactRef.current) {
        contactRef.current.scrollIntoView({ behavior: 'smooth' });
      }
      sessionStorage.removeItem('scrollToFooterSection');
    }
  }, []);
  
  return (
    <footer className="footer">
      <div className="footer-top">
        <div className="container">
          <div className="footer-content">
            <div className="footer-brand">
              <div className="footer-logo">
                <img 
                  src="/assets/KOMPLEMON_logo.png" 
                  alt="KOMPLEMON" 
                  style={{ maxWidth: '200px', height: 'auto' }}
                />
              </div>
              
              <p>Your trusted destination for premium tech products. From high-performance PCs to the latest smartphones and components.</p>
              <div className="social-icons">
                <a href="#" className="social-icon"><FaFacebook /></a>
                <a href="#" className="social-icon"><FaTwitter /></a>
                <a href="#" className="social-icon"><FaInstagram /></a>
                <a href="#" className="social-icon"><FaLinkedin /></a>
              </div>
            </div>
            
            <div className="footer-links-column" ref={aboutRef}>
              <h3>About Us</h3>
              <p className="footer-about-text">
                KOMPLEMON is your premier tech destination, offering a curated selection of high-quality computer hardware,
                smartphones, and peripherals. Founded with a passion for technology, we strive to provide exceptional
                products and service for tech enthusiasts and professionals alike.
              </p>
            </div>
            
            <div className="footer-links-column">
              <h3>Shop</h3>
              <ul className="footer-links">
                <li><Link to="/category/desktops"><FaArrowRight className="link-icon" /> Desktop PCs</Link></li>
                <li><Link to="/category/laptops"><FaArrowRight className="link-icon" /> Laptops</Link></li>
                <li><Link to="/category/components"><FaArrowRight className="link-icon" /> Components</Link></li>
                <li><Link to="/category/smartphones"><FaArrowRight className="link-icon" /> Smartphones</Link></li>
              </ul>
            </div>
            
            <div className="footer-contact" ref={contactRef}>
              <h3>Contact Us</h3>
              <ul className="contact-info">
                <li>
                  <FaMapMarkerAlt className="contact-icon" />
                  <span>123 Tech Street, Digital City, 10100</span>
                </li>
                <li>
                  <FaPhone className="contact-icon" />
                  <span>+1 (234) 567-8900</span>
                </li>
                <li>
                  <FaEnvelope className="contact-icon" />
                  <span>info@komplemon.com</span>
                </li>
              </ul>
              <div className="footer-newsletter">
                <input type="email" placeholder="Your email" />
                <button type="submit">Subscribe</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div className="footer-bottom">
        <div className="container">
          <p>&copy; {new Date().getFullYear()} KOMPLEMON. All Rights Reserved.</p>
          <div className="footer-bottom-links">
            <Link to="/privacy">Privacy Policy</Link>
            <Link to="/terms">Terms of Service</Link>
            <Link to="/sitemap">Sitemap</Link>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
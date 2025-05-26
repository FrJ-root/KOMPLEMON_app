import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FaArrowLeft, FaPlus, FaMinus } from 'react-icons/fa';
import { mockProducts } from '../data/mockData';

const ProductDetailsPage = () => {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [mainImage, setMainImage] = useState('');
  const [quantity, setQuantity] = useState(1);
  
  useEffect(() => {
    // Simulate fetching product details from an API
    setTimeout(() => {
      const foundProduct = mockProducts.find(p => p.id === parseInt(id));
      setProduct(foundProduct);
      setMainImage(foundProduct?.image);
      setLoading(false);
    }, 800);
  }, [id]);
  
  const handleQuantityChange = (action) => {
    if (action === 'increase') {
      setQuantity(prev => prev + 1);
    } else if (action === 'decrease' && quantity > 1) {
      setQuantity(prev => prev - 1);
    }
  };
  
  if (loading) {
    return <div className="loading-spinner">Loading...</div>;
  }
  
  if (!product) {
    return <div className="error-message">Product not found</div>;
  }
  
  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
    >
      <div className="container">
        <Link to="/" className="back-link">
          <FaArrowLeft /> Back to Products
        </Link>
        
        <div className="product-details">
          <motion.div 
            className="product-images"
            initial={{ x: -50, opacity: 0 }}
            animate={{ x: 0, opacity: 1 }}
            transition={{ duration: 0.5 }}
          >
            <img 
              src={mainImage} 
              alt={product.name} 
              className="main-image" 
            />
            
            <div className="image-thumbnails">
              <img 
                src={product.image} 
                alt={product.name} 
                className={`thumbnail ${mainImage === product.image ? 'active' : ''}`}
                onClick={() => setMainImage(product.image)}
              />
              {product.additionalImages && product.additionalImages.map((img, index) => (
                <img 
                  key={index}
                  src={img} 
                  alt={`${product.name} - ${index}`} 
                  className={`thumbnail ${mainImage === img ? 'active' : ''}`}
                  onClick={() => setMainImage(img)}
                />
              ))}
            </div>
          </motion.div>
          
          <motion.div 
            className="product-info-details"
            initial={{ x: 50, opacity: 0 }}
            animate={{ x: 0, opacity: 1 }}
            transition={{ duration: 0.5 }}
          >
            <h1 className="product-title-details">{product.name}</h1>
            
            <div className="product-price">
              <span className="regular-price">€{product.prixPromo || product.prix}</span>
              {product.prixPromo && (
                <span className="discount-price">€{product.prix}</span>
              )}
            </div>
            
            <p className="product-description">{product.description}</p>
            
            <div className="product-meta">
              <p><strong>Category:</strong> {product.category}</p>
              <p><strong>Availability:</strong> {product.stock > 0 ? 'In Stock' : 'Out of Stock'}</p>
              
              {product.specifications && (
                <div className="specifications">
                  <h3>Specifications</h3>
                  <ul>
                    {product.specifications.map((spec, index) => (
                      <li key={index}><strong>{spec.name}:</strong> {spec.value}</li>
                    ))}
                  </ul>
                </div>
              )}
            </div>
            
            <div className="quantity-selector">
              <button 
                className="quantity-button"
                onClick={() => handleQuantityChange('decrease')}
              >
                <FaMinus />
              </button>
              
              <input 
                type="number" 
                className="quantity-input"
                value={quantity}
                readOnly
              />
              
              <button 
                className="quantity-button"
                onClick={() => handleQuantityChange('increase')}
              >
                <FaPlus />
              </button>
            </div>
            
            <motion.button 
              className="add-to-cart-button"
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              Add to Cart
            </motion.button>
          </motion.div>
        </div>
      </div>
    </motion.div>
  );
};

export default ProductDetailsPage;

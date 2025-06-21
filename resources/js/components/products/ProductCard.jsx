import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';

const ProductCard = ({ product }) => {
  return (
    <motion.div 
      className="product-card"
      whileHover={{ y: -5 }}
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.3 }}
    >
      <Link to={`/product/${product.id}`}>
        <img 
          src={product.image} 
          alt={product.name} 
          className="product-image" 
        />
        
        <div className="product-info">
          <h3 className="product-title">{product.name}</h3>
          
          <div className="product-price">
            <span className="regular-price">€{product.prixPromo || product.prix}</span>
            {product.prixPromo && (
              <span className="discount-price">€{product.prix}</span>
            )}
          </div>
          
          <div className="product-category">{product.category}</div>
        </div>
      </Link>
    </motion.div>
  );
};

export default ProductCard;

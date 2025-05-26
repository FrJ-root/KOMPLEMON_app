import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FaTrash, FaArrowLeft } from 'react-icons/fa';

const CartPage = () => {
  const [cartItems, setCartItems] = useState([]);
  const [loading, setLoading] = useState(true);
  
  useEffect(() => {
    // Simulate fetching cart data
    setTimeout(() => {
      // Empty cart for now
      setCartItems([]);
      setLoading(false);
    }, 800);
  }, []);
  
  const calculateTotal = () => {
    return cartItems.reduce((total, item) => {
      const price = item.product.prixPromo || item.product.prix;
      return total + (price * item.quantity);
    }, 0).toFixed(2);
  };
  
  if (loading) {
    return <div className="loading-spinner">Loading...</div>;
  }
  
  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
      className="container"
    >
      <h1 className="page-title">Your Cart</h1>
      
      {cartItems.length === 0 ? (
        <div className="empty-cart">
          <h2>Your cart is empty</h2>
          <p>Looks like you haven't added any products to your cart yet.</p>
          <Link to="/" className="continue-shopping-button">
            <FaArrowLeft /> Continue Shopping
          </Link>
        </div>
      ) : (
        <div className="cart-content">
          <div className="cart-items">
            {cartItems.map((item) => (
              <div key={item.id} className="cart-item">
                <img 
                  src={item.product.image} 
                  alt={item.product.name} 
                  className="cart-item-image" 
                />
                
                <div className="cart-item-details">
                  <h3>{item.product.name}</h3>
                  <p className="item-price">
                    €{item.product.prixPromo || item.product.prix}
                  </p>
                </div>
                
                <div className="cart-item-quantity">
                  <button className="quantity-button">-</button>
                  <span>{item.quantity}</span>
                  <button className="quantity-button">+</button>
                </div>
                
                <div className="cart-item-total">
                  €{((item.product.prixPromo || item.product.prix) * item.quantity).toFixed(2)}
                </div>
                
                <button className="remove-item-button">
                  <FaTrash />
                </button>
              </div>
            ))}
          </div>
          
          <div className="cart-summary">
            <h3>Order Summary</h3>
            
            <div className="summary-row">
              <span>Subtotal</span>
              <span>€{calculateTotal()}</span>
            </div>
            
            <div className="summary-row">
              <span>Shipping</span>
              <span>Free</span>
            </div>
            
            <div className="summary-row total">
              <span>Total</span>
              <span>€{calculateTotal()}</span>
            </div>
            
            <button className="checkout-button">
              Proceed to Checkout
            </button>
            
            <Link to="/" className="continue-shopping-link">
              <FaArrowLeft /> Continue Shopping
            </Link>
          </div>
        </div>
      )}
    </motion.div>
  );
};

export default CartPage;

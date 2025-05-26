import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';
import { FaDesktop, FaLaptop, FaMicrochip, FaMobile, FaKeyboard, FaHeadphones, FaGamepad, FaNetworkWired, FaMemory, FaHdd, FaServer, FaCamera } from 'react-icons/fa';

const CategoriesPage = () => {
  // Define all categories with icons, descriptions, and paths
  const categories = [
    {
      id: 1,
      name: "Desktop PCs",
      icon: <FaDesktop />,
      description: "High-performance gaming PCs and workstations",
      path: "/category/desktops",
      bgImage: "https://images.unsplash.com/photo-1593640495253-23196b27a87f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 2,
      name: "Laptops",
      icon: <FaLaptop />,
      description: "Portable computing for work and play",
      path: "/category/laptops",
      bgImage: "https://images.unsplash.com/photo-1531297484001-80022131f5a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 3,
      name: "Components",
      icon: <FaMicrochip />,
      description: "CPUs, GPUs, and essential PC hardware",
      path: "/category/components",
      bgImage: "https://images.unsplash.com/photo-1591488320449-011701bb6704?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 4,
      name: "Smartphones",
      icon: <FaMobile />,
      description: "Latest mobile devices and accessories",
      path: "/category/smartphones",
      bgImage: "https://images.unsplash.com/photo-1598327105666-5b89351aff97?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 5,
      name: "Peripherals",
      icon: <FaKeyboard />,
      description: "Keyboards, mice, and input devices",
      path: "/category/peripherals",
      bgImage: "https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 6,
      name: "Audio",
      icon: <FaHeadphones />,
      description: "Headphones, speakers, and sound equipment",
      path: "/category/audio",
      bgImage: "https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 7,
      name: "Gaming",
      icon: <FaGamepad />,
      description: "Consoles, controllers, and gaming gear",
      path: "/category/gaming",
      bgImage: "https://images.unsplash.com/photo-1580327332925-a10e6cb11baa?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 8,
      name: "Networking",
      icon: <FaNetworkWired />,
      description: "Routers, switches, and connectivity solutions",
      path: "/category/networking",
      bgImage: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 9,
      name: "RAM & Memory",
      icon: <FaMemory />,
      description: "System memory and storage expansion",
      path: "/category/memory",
      bgImage: "https://images.unsplash.com/photo-1562976540-1502c2145186?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 10,
      name: "Storage",
      icon: <FaHdd />,
      description: "Hard drives, SSDs, and data storage",
      path: "/category/storage",
      bgImage: "https://images.unsplash.com/photo-1601737487795-dab272f52074?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 11,
      name: "Servers",
      icon: <FaServer />,
      description: "Enterprise hardware and infrastructure",
      path: "/category/servers",
      bgImage: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    },
    {
      id: 12,
      name: "Cameras",
      icon: <FaCamera />,
      description: "Digital cameras and photography equipment",
      path: "/category/cameras",
      bgImage: "https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    }
  ];

  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      transition={{ duration: 0.5 }}
      className="categories-page"
    >
      <div className="page-header">
        <div className="container">
          <h1>Browse Categories</h1>
          <p>Explore our wide range of tech products by category</p>
        </div>
      </div>

      <div className="container">
        <div className="categories-page-grid">
          {categories.map((category, index) => (
            <motion.div
              key={category.id}
              className="category-card-large"
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: index * 0.1 }}
              whileHover={{ 
                y: -10,
                boxShadow: "0 20px 30px rgba(0, 0, 0, 0.2)"
              }}
            >
              <Link to={category.path}>
                <div 
                  className="category-card-bg" 
                  style={{ backgroundImage: `url(${category.bgImage})` }}
                ></div>
                <div className="category-card-overlay"></div>
                <div className="category-card-content">
                  <div className="category-icon-large">{category.icon}</div>
                  <h3>{category.name}</h3>
                  <p>{category.description}</p>
                </div>
              </Link>
            </motion.div>
          ))}
        </div>
      </div>
    </motion.div>
  );
};

export default CategoriesPage;

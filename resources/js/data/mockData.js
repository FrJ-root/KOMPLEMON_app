export const mockProducts = [
  {
    id: 1,
    name: "Gaming PC Pro X",
    description: "High-performance gaming PC with RTX 4080, Intel i9 processor, 32GB RAM and 2TB SSD. Experience the latest games at ultra settings with uncompromising performance.",
    prix: 1899.99,
    prixPromo: 1699.99,
    stock: 15,
    category: "Desktop PCs",
    image: "https://images.unsplash.com/photo-1587202372775-e229f172b9d7?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    additionalImages: [
      "https://images.unsplash.com/photo-1593640495253-23196b27a87f?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
      "https://images.unsplash.com/photo-1591488320449-011701bb6704?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    ],
    specifications: [
      { name: "Processor", value: "Intel Core i9-13900K" },
      { name: "Graphics", value: "NVIDIA RTX 4080 16GB" },
      { name: "RAM", value: "32GB DDR5 5200MHz" },
      { name: "Storage", value: "2TB NVMe SSD" }
    ]
  },
  {
    id: 2,
    name: "UltraBook Pro 15",
    description: "Ultra-thin, lightweight laptop with exceptional battery life. Perfect for professionals on the go with a stunning 4K display and the latest hardware.",
    prix: 1299.99,
    prixPromo: null,
    stock: 28,
    category: "Laptops",
    image: "https://images.unsplash.com/photo-1531297484001-80022131f5a1?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    additionalImages: [
      "https://images.unsplash.com/photo-1541807084-5c52b6b3adef?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    ],
    specifications: [
      { name: "Processor", value: "Intel Core i7-1265U" },
      { name: "Display", value: "15.6\" 4K OLED" },
      { name: "RAM", value: "16GB LPDDR5" },
      { name: "Storage", value: "1TB SSD" }
    ]
  },
  {
    id: 3,
    name: "SmartPhone X14 Pro",
    description: "Flagship smartphone with a revolutionary camera system, all-day battery life, and the most powerful mobile processor available today.",
    prix: 999.99,
    prixPromo: 899.99,
    stock: 42,
    category: "Smartphones",
    image: "https://images.unsplash.com/photo-1598327105666-5b89351aff97?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    specifications: [
      { name: "Processor", value: "A16 Bionic" },
      { name: "Display", value: "6.7\" Super Retina XDR" },
      { name: "Camera", value: "48MP Pro Camera System" },
      { name: "Storage", value: "256GB" }
    ]
  },
  {
    id: 4,
    name: "AMD Ryzen 9 7950X",
    description: "High-end desktop processor with 16 cores and 32 threads for extreme multitasking and content creation. The ultimate CPU for enthusiasts.",
    prix: 599.99,
    prixPromo: 549.99,
    stock: 22,
    category: "Components",
    image: "https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    additionalImages: [
      "https://images.unsplash.com/photo-1555618254-5a9d4ab34284?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80"
    ],
    specifications: [
      { name: "Cores/Threads", value: "16/32" },
      { name: "Base Clock", value: "4.5 GHz" },
      { name: "Boost Clock", value: "5.7 GHz" },
      { name: "TDP", value: "170W" }
    ]
  },
  {
    id: 5,
    name: "4K Gaming Monitor 32\"",
    description: "Premium gaming monitor with 4K resolution, 144Hz refresh rate, and 1ms response time. Provides exceptional color accuracy and HDR support.",
    prix: 799.99,
    prixPromo: null,
    stock: 18,
    category: "Monitors",
    image: "https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    specifications: [
      { name: "Resolution", value: "3840 x 2160" },
      { name: "Refresh Rate", value: "144Hz" },
      { name: "Response Time", value: "1ms GTG" },
      { name: "Panel Type", value: "IPS" }
    ]
  },
  {
    id: 6,
    name: "Mechanical Gaming Keyboard",
    description: "Premium mechanical keyboard with RGB lighting, N-key rollover, and durable aluminum construction. Features Cherry MX switches for the ultimate typing experience.",
    prix: 149.99,
    prixPromo: 129.99,
    stock: 50,
    category: "Peripherals",
    image: "https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    specifications: [
      { name: "Switches", value: "Cherry MX Red" },
      { name: "Connectivity", value: "USB-C, Bluetooth" },
      { name: "Backlighting", value: "RGB Per-key" },
      { name: "Battery Life", value: "70 hours" }
    ]
  },
  {
    id: 7,
    name: "RTX 4090 Graphics Card",
    description: "The most powerful consumer graphics card on the market. Delivers unparalleled performance for gaming, rendering, and AI workloads.",
    prix: 1599.99,
    prixPromo: null,
    stock: 8,
    category: "Components",
    image: "https://images.unsplash.com/photo-1587202372616-b43abea06c2a?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    specifications: [
      { name: "CUDA Cores", value: "16384" },
      { name: "Memory", value: "24GB GDDR6X" },
      { name: "Boost Clock", value: "2.52 GHz" },
      { name: "Power", value: "450W" }
    ]
  },
  {
    id: 8,
    name: "Wireless Gaming Headset",
    description: "Immersive gaming headset with 3D audio, noise-cancelling microphone, and 30-hour battery life. Experience crystal clear sound and communication.",
    prix: 199.99,
    prixPromo: 169.99,
    stock: 35,
    category: "Peripherals",
    image: "https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80",
    specifications: [
      { name: "Driver", value: "50mm Neodymium" },
      { name: "Frequency Response", value: "20-20,000 Hz" },
      { name: "Battery Life", value: "30 hours" },
      { name: "Connectivity", value: "2.4GHz Wireless, Bluetooth 5.0" }
    ]
  }
];

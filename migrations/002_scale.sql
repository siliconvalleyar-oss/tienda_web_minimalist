USE tienda_minimal;

-- Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name, slug) VALUES
('Sillones y Sillas', 'sillones-sillas'),
('Iluminación', 'iluminacion'),
('Mesas y Superficies', 'mesas-superficies'),
('Decoración', 'decoracion'),
('Almacenamiento', 'almacenamiento')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Extended products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(500) NOT NULL,
    stock INT DEFAULT 0,
    featured BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO products (category_id, name, slug, description, price, image, stock, featured) VALUES
(1, 'Silla Eames', 'silla-eames', 'Icónica silla de diseño mid-century con estructura de madera y asiento acolchado.', 320.00, 'https://picsum.photos/id/20/400/300', 10, 1),
(1, 'Silla de Lectura Oslo', 'silla-lectura-oslo', 'Sillón ergonómico con reposabrazos de cuero y base de roble.', 450.00, 'https://picsum.photos/id/21/400/300', 5, 1),
(1, 'Banco Minimalista', 'banco-minimalista', 'Banco de madera maciza con líneas depuradas y acabado natural.', 180.00, 'https://picsum.photos/id/22/400/300', 8, 0),
(2, 'Lámpara de pie', 'lampara-pie', 'Lámpara de pie con pantalla de lino y estructura de acero negro mate.', 185.00, 'https://picsum.photos/id/26/400/300', 12, 1),
(2, 'Lámpara colgante Nórdica', 'lampara-colgante-nordica', 'Pantalla de vidrio soplado con cable textil trenzado.', 120.00, 'https://picsum.photos/id/27/400/300', 15, 0),
(2, 'Velador de Mesa Atómico', 'velador-mesa-atomico', 'Lámpara de escritorio ajustable con brazo articulado de aluminio.', 95.00, 'https://picsum.photos/id/28/400/300', 20, 0),
(3, 'Mesa auxiliar', 'mesa-auxiliar', 'Mesa lateral de dos niveles con bandeja de acero esmaltado.', 99.00, 'https://picsum.photos/id/30/400/300', 10, 1),
(3, 'Mesa de comedor Roble', 'mesa-comedor-roble', 'Mesa extensible para 6-8 personas con tablero de roble macizo.', 680.00, 'https://picsum.photos/id/31/400/300', 3, 1),
(3, 'Escritorio Compacto', 'escritorio-compacto', 'Escritorio flotante con cajones ocultos y organizador interno.', 340.00, 'https://picsum.photos/id/32/400/300', 6, 0),
(4, 'Espejo redondo', 'espejo-redondo', 'Espejo de pared con marco de aluminio anodizado y soporte oculto.', 79.00, 'https://picsum.photos/id/89/400/300', 25, 1),
(4, 'Jarrón Cerámica Artesanal', 'jarron-ceramica-artesanal', 'Pieza única hecha a mano con esmaltado natural.', 65.00, 'https://picsum.photos/id/33/400/300', 8, 0),
(4, 'Macetero Colgante', 'macetero-colgante', 'Maceta de fibra natural con soporte de cuero vegano.', 45.00, 'https://picsum.photos/id/34/400/300', 30, 0),
(5, 'Estantería Modular', 'estanteria-modular', 'Sistema de estantes flotantes con soporte invisible.', 210.00, 'https://picsum.photos/id/35/400/300', 7, 0),
(5, 'Cómoda Nórdica', 'comoda-nordica', 'Cómoda de 6 cajones con frente lacado blanco y patas de haya.', 520.00, 'https://picsum.photos/id/36/400/300', 4, 1)
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Customers
CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(50),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
);

-- Order items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

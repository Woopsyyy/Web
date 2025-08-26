-- Create database and use it
CREATE DATABASE IF NOT EXISTS cafe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafe;

-- Create users table for authentication
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  username VARCHAR(64) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- sample password fucking shit
INSERT INTO users (name, username, email, password_hash, role) VALUES
('Administrator', 'admin', 'admin@example.com', '$2y$10$y3Lq5hMR9v7f5FIVvS1xReb1kqfVqTRg2q4m5wqvY4Ykq0vHj6mVO', 'admin'),
('John Smith', 'john', 'john@example.com', '$2y$10$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Sarah Johnson', 'sarah', 'sarah@example.com', '$2y$10$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Mike Wilson', 'mike', 'mike@example.com', '$2y$10$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Emily Davis', 'emily', 'emily@example.com', '$2y$10$2y$10$92IXUNpkjOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('David Brown', 'david', 'david@example.com', '$2y$10$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user')
ON DUPLICATE KEY UPDATE username = username;

CREATE TABLE IF NOT EXISTS dishes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(255) NOT NULL,
  category ENUM('main','appetizer','dessert','beverage') NOT NULL,
  price VARCHAR(32) NOT NULL,
  ingredients TEXT NOT NULL,
  prep_time VARCHAR(64) NOT NULL,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data (paths and fields match your current menu.html)
INSERT INTO dishes (title, description, image, category, price, ingredients, prep_time) VALUES
('Butter Chicken',
 'Creamy, rich butter chicken with tender pieces of chicken in a tomato-based sauce, served with aromatic basmati rice.',
 'Images/Buter Chicken.jpg', 'main', '$12.99',
 'Chicken breast, tomatoes, cream, butter, garam masala, fenugreek, ginger, garlic, onions, cashews',
 '25-30 minutes'
),
('Chicken Curry',
 'Spicy chicken curry with aromatic spices and herbs, a perfect blend of heat and flavor.',
 'Images/Chicken curry.jpg', 'main', '$11.99',
 'Chicken thighs, curry powder, turmeric, cumin, coriander, chili, coconut milk, tomatoes, onions',
 '20-25 minutes'
),
('Crispy Chilli Chicken',
 'Crispy fried chicken with sweet and spicy chili sauce, a crowd favorite with perfect crunch.',
 'Images/Crispy Chilli Chicken.jpg', 'main', '$13.99',
 'Chicken breast, cornstarch, soy sauce, chili sauce, honey, garlic, ginger, green onions, sesame seeds',
 '15-20 minutes'
),
('Spring Rolls',
 'Fresh vegetable spring rolls with crispy wrapper, served with sweet chili sauce.',
 'Images/spring rolls.jpg', 'appetizer', '$6.99',
 'Rice paper, carrots, cucumber, lettuce, mint, cilantro, rice vermicelli, shrimp, peanut sauce',
 '10-15 minutes'
),
('Chocolate Lava Cake',
 'Warm chocolate cake with molten center, served with vanilla ice cream.',
 'Images/chocolate lava cake.jpg', 'dessert', '$8.99',
 'Dark chocolate, butter, eggs, sugar, flour, vanilla extract, cocoa powder, vanilla ice cream',
 '12-15 minutes'
),
('Iced Coffee',
 'Smooth cold brew coffee with cream and vanilla, perfect for hot days.',
 'Images/ice coffee.jpg', 'beverage', '$4.99',
 'Cold brew coffee, heavy cream, vanilla syrup, ice, whipped cream, chocolate shavings',
 '5 minutes'
),
('Milk tea',
 'Milk tea is a popular beverage made by combining tea with milk, sugar, and often ice.',
 'Images/milk tea.jpg', 'beverage', '$4.99',
 'Tea, milk, sweeterner, ice (optional), flavoring',
 '5 minutes'
),
('Pickled Carrots',
 'Crunchy and tangy pickled carrots, perfect as a side dish or topping for salads and sandwiches.',
 'Images/Pickled Carrots.jpg', 'appetizer', '$3.99',
 'carrots, white vinegar, water, sugar, garlic, salt, black pepper, allspice berries',
 '10 minutes'
),
('Americano',
 'Classic espresso with hot water, creating a light layer of crema and a rich, bold flavor.',
 'Images/americano.jpg', 'beverage', '$3.99',
 'Espresso, hot water',
 '3-5 minutes'
),
('Cappuccino',
 'Perfectly balanced espresso with steamed milk and a thick layer of milk foam.',
 'Images/Cappuccino.jpg', 'beverage', '$4.99',
 'Espresso, steamed milk, milk foam',
 '5-7 minutes'
),
('Coffee Jelly',
 'Refreshing coffee-flavored jelly dessert, perfect for coffee lovers.',
 'Images/coffee jelly.jpg', 'dessert', '$5.99',
 'Coffee, gelatin, sugar, cream, vanilla extract',
 '15-20 minutes'
),
('Mocha Cheesecake',
 'Rich chocolate cheesecake with coffee flavor, topped with chocolate ganache.',
 'Images/mocha cheesecake.jpg', 'dessert', '$9.99',
 'Cream cheese, chocolate, coffee, graham crackers, sugar, eggs, vanilla extract',
 '45-50 minutes'
),
('Mini Spaghetti and Meatball',
 'Classic Italian spaghetti with homemade meatballs in rich tomato sauce.',
 'Images/Mini Spaghetti and Meatball.jpg', 'main', '$14.99',
 'Spaghetti, ground beef, breadcrumbs, parmesan cheese, eggs, tomato sauce, garlic, herbs',
 '30-35 minutes'
),
('Jeros Lechedo',
 'Traditional Filipino dessert with layers of sponge cake, custard, and caramel.',
 'Images/jeros lechedo.jpg', 'dessert', '$7.99',
 'Sponge cake, custard, caramel sauce, milk, eggs, sugar, vanilla',
 '40-45 minutes'
);

-- Create feedback table for user ratings and feedback
CREATE TABLE IF NOT EXISTS feedback (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  type ENUM('rating', 'feedback') NOT NULL,
  rating INT CHECK (rating >= 1 AND rating <= 5),
  message TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
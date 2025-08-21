-- Create database and use it
CREATE DATABASE IF NOT EXISTS cafe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafe;

-- Create dishes table (matches Backend/menu.php expectations)
CREATE TABLE IF NOT EXISTS dishes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  image VARCHAR(255) NOT NULL,
  category ENUM('main','appetizer','dessert','beverage') NOT NULL,
  price VARCHAR(32) NOT NULL,
  ingredients TEXT NOT NULL,
  prep_time VARCHAR(64) NOT NULL,
  nutrition_json JSON NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed data (paths and fields match your current menu.html)
INSERT INTO dishes (title, description, image, category, price, ingredients, prep_time, nutrition_json) VALUES
('Butter Chicken',
 'Creamy, rich butter chicken with tender pieces of chicken in a tomato-based sauce, served with aromatic basmati rice.',
 'Images/Buter Chicken.jpg', 'main', '$12.99',
 'Chicken breast, tomatoes, cream, butter, garam masala, fenugreek, ginger, garlic, onions, cashews',
 '25-30 minutes',
 JSON_OBJECT('calories','450','protein','28g','carbs','12g','fat','32g')
),
('Chicken Curry',
 'Spicy chicken curry with aromatic spices and herbs, a perfect blend of heat and flavor.',
 'Images/Chicken curry.jpg', 'main', '$11.99',
 'Chicken thighs, curry powder, turmeric, cumin, coriander, chili, coconut milk, tomatoes, onions',
 '20-25 minutes',
 JSON_OBJECT('calories','380','protein','26g','carbs','8g','fat','28g')
),
('Crispy Chilli Chicken',
 'Crispy fried chicken with sweet and spicy chili sauce, a crowd favorite with perfect crunch.',
 'Images/Crispy Chilli Chicken.jpg', 'main', '$13.99',
 'Chicken breast, cornstarch, soy sauce, chili sauce, honey, garlic, ginger, green onions, sesame seeds',
 '15-20 minutes',
 JSON_OBJECT('calories','520','protein','32g','carbs','45g','fat','24g')
),
('Spring Rolls',
 'Fresh vegetable spring rolls with crispy wrapper, served with sweet chili sauce.',
 'Images/feature.jpg', 'appetizer', '$6.99',
 'Rice paper, carrots, cucumber, lettuce, mint, cilantro, rice vermicelli, shrimp, peanut sauce',
 '10-15 minutes',
 JSON_OBJECT('calories','180','protein','8g','carbs','28g','fat','6g')
),
('Chocolate Lava Cake',
 'Warm chocolate cake with molten center, served with vanilla ice cream.',
 'Images/chocolate lava cake.jpg', 'dessert', '$8.99',
 'Dark chocolate, butter, eggs, sugar, flour, vanilla extract, cocoa powder, vanilla ice cream',
 '12-15 minutes',
 JSON_OBJECT('calories','420','protein','6g','carbs','52g','fat','22g')
),
('Iced Coffee',
 'Smooth cold brew coffee with cream and vanilla, perfect for hot days.',
 'Images/ice coffee.jpg', 'beverage', '$4.99',
 'Cold brew coffee, heavy cream, vanilla syrup, ice, whipped cream, chocolate shavings',
 '5 minutes',
 JSON_OBJECT('calories','180','protein','2g','carbs','18g','fat','12g')
),
('Milk tea',
 'Milk tea is a popular beverage made by combining tea with milk, sugar, and often ice.',
 'Images/milk tea.jpg', 'beverage', '$4.99',
 'Tea, milk, sweeterner, ice (optional), flavoring',
 '5 minutes',
 JSON_OBJECT('calories','180','protein','2g','carbs','18g','fat','12g')
),
('Pickled Carrots',
 'Crunchy and tangy pickled carrots, perfect as a side dish or topping for salads and sandwiches.',
 'Images/Pickled Carrots.jpg', 'appetizer', '$3.99',
 'carrots, white vinegar, water, sugar, garlic, salt, black pepper, allspice berries',
 '10 minutes',
 JSON_OBJECT(
   'calories','54','protein','1g','carbs','12g','fat','1g',
   'SaturatedFat','1g','Sodium','913mg','Potasium','186mg','Fiber','2g',
   'Sugar','6g','VitaminC','3mg','Calcium','24mg','Iron','1mg'
 )
);
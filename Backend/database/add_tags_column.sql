-- Add tags column to dishes table
USE cafe;

-- Add tags column
ALTER TABLE dishes ADD COLUMN tags VARCHAR(255) DEFAULT NULL;

-- Update existing items with some sample tags
UPDATE dishes SET tags = 'chicken,spicy,curry' WHERE title LIKE '%Chicken%' AND category = 'main';
UPDATE dishes SET tags = 'chicken,crispy,spicy' WHERE title LIKE '%Crispy%' AND title LIKE '%Chicken%';
UPDATE dishes SET tags = 'chicken,curry,spicy' WHERE title LIKE '%Chicken%' AND title LIKE '%Curry%';
UPDATE dishes SET tags = 'chicken,appetizer' WHERE title LIKE '%Chicken%' AND category = 'appetizer';
UPDATE dishes SET tags = 'coffee,hot' WHERE title LIKE '%Americano%';
UPDATE dishes SET tags = 'coffee,milk,foam' WHERE title LIKE '%Cappuccino%';
UPDATE dishes SET tags = 'coffee,cold' WHERE title LIKE '%ice coffee%';
UPDATE dishes SET tags = 'tea,milk' WHERE title LIKE '%milk tea%';
UPDATE dishes SET tags = 'chocolate,dessert' WHERE title LIKE '%Chocolate%';
UPDATE dishes SET tags = 'coffee,dessert,jelly' WHERE title LIKE '%Coffee Jelly%';
UPDATE dishes SET tags = 'chocolate,cheesecake' WHERE title LIKE '%Mocha Cheesecake%';
UPDATE dishes SET tags = 'pasta,meatball' WHERE title LIKE '%Spaghetti%';
UPDATE dishes SET tags = 'dessert,filipino' WHERE title LIKE '%Jeros%';

-- Verify the table structure
DESCRIBE dishes;

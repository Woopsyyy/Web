-- Update menu structure to support multiple tags and better categorization

-- First, let's add a tags column to the dishes table
ALTER TABLE dishes ADD COLUMN tags VARCHAR(255) DEFAULT '';

-- Update existing dishes with appropriate tags based on their current categories
UPDATE dishes SET tags = 'main,chicken' WHERE category = 'main' AND (title LIKE '%chicken%' OR title LIKE '%Chicken%');
UPDATE dishes SET tags = 'main,pork' WHERE category = 'main' AND (title LIKE '%pork%' OR title LIKE '%Pork%');
UPDATE dishes SET tags = 'main,beef' WHERE category = 'main' AND (title LIKE '%beef%' OR title LIKE '%Beef%');
UPDATE dishes SET tags = 'appetizer' WHERE category = 'appetizer';
UPDATE dishes SET tags = 'dessert' WHERE category = 'dessert';
UPDATE dishes SET tags = 'beverage,brewed-coffee' WHERE category = 'beverage' AND (title LIKE '%coffee%' OR title LIKE '%Coffee%' OR title LIKE '%americano%' OR title LIKE '%cappuccino%');
UPDATE dishes SET tags = 'beverage,blended-beverage' WHERE category = 'beverage' AND (title LIKE '%blend%' OR title LIKE '%smoothie%' OR title LIKE '%shake%');
UPDATE dishes SET tags = 'beverage,teavana-tea' WHERE category = 'beverage' AND (title LIKE '%tea%' OR title LIKE '%Tea%');
UPDATE dishes SET tags = 'beverage,chocolate-more' WHERE category = 'beverage' AND (title LIKE '%chocolate%' OR title LIKE '%milk%' OR title LIKE '%hot chocolate%');

-- Set default tags for any remaining items
UPDATE dishes SET tags = 'main' WHERE tags = '' AND category = 'main';
UPDATE dishes SET tags = 'beverage' WHERE tags = '' AND category = 'beverage';
UPDATE dishes SET tags = 'appetizer' WHERE tags = '' AND category = 'appetizer';
UPDATE dishes SET tags = 'dessert' WHERE tags = '' AND category = 'dessert';

-- Add an index for better performance
CREATE INDEX idx_dishes_tags ON dishes(tags);

-- Update database to remove nutrition_json column
USE cafe;

-- Remove nutrition_json column from dishes table
ALTER TABLE dishes DROP COLUMN IF EXISTS nutrition_json;

-- Update any existing records to ensure they don't have nutrition data
UPDATE dishes SET nutrition_json = NULL WHERE nutrition_json IS NOT NULL;

-- Verify the table structure
DESCRIBE dishes;

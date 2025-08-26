-- Add unique constraint to prevent duplicate titles
-- This will prevent future duplicates from being created

ALTER TABLE dishes ADD UNIQUE KEY unique_title (title);

-- If you get an error about existing duplicates, run the cleanup first:
-- 1. Go to your admin panel
-- 2. Click "Check Duplicates" button
-- 3. Confirm the cleanup
-- 4. Then run this SQL command again

-- Add is_admin column to users table with default value of 0 (false)
ALTER TABLE users ADD COLUMN is_admin BOOLEAN NOT NULL DEFAULT 0;

-- Set an existing user as admin (replace 1 with the ID of the user you want to make admin)
UPDATE users SET is_admin = 1 WHERE id = 1;

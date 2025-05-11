-- Add a UNIQUE constraint to the email column
ALTER TABLE subscribers ADD CONSTRAINT unique_email UNIQUE (email);
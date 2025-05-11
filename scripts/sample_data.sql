-- Insert sample data into the `subscribers` table
INSERT INTO subscribers (first_name, last_name, email, phone, status, token)
VALUES
('John', 'Doe', 'john.doe@example.com', '1234567890', 'pending', 'sampletoken1'),
('Jane', 'Smith', 'jane.smith@example.com', '0987654321', 'confirmed', 'sampletoken2'),
('Alice', 'Johnson', 'alice.johnson@example.com', NULL, 'unsubscribed', 'sampletoken3'),
('Bob', 'Brown', 'bob.brown@example.com', '555-555-5555', 'pending', 'sampletoken4');
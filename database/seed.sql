USE vehicle_service_management;

INSERT INTO users (username, password_hash)
SELECT 'admin', '$2y$10$igVCerAQLA0tXb9TkPS0ReGjbFEANwBYyJhWeQkyzUDV6ZEhoAP3O'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

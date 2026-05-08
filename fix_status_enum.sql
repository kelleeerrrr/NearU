ALTER TABLE dorm_listings DROP CONSTRAINT IF EXISTS dorm_listings_status_check;
ALTER TABLE dorm_listings ADD CONSTRAINT dorm_listings_status_check CHECK (status IN ('Available', 'Taken', 'Unavailable'));

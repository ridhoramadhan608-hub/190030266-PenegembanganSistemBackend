-- Optional indexes to improve performance for search and filters.
-- Run these after creating/importing the main schema.

-- 1) Index to speed up queries that filter by user_id and status
ALTER TABLE `tasks`
  ADD INDEX `idx_user_status` (`user_id`, `status`);

-- 2) FULLTEXT index on (title, description) for efficient keyword search
-- Note: FULLTEXT is supported on InnoDB in MySQL 5.6+ and on recent MariaDB versions.
-- If your server supports FULLTEXT on InnoDB, uncomment the statement below and run it.
-- ALTER TABLE `tasks`
--   ADD FULLTEXT INDEX `ft_title_description` (`title`, `description`);

-- If FULLTEXT is not available, the application will fall back to LIKE-based searches.

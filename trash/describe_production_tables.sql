-- SQL script to describe all tables on PRODUCTION database (t_review_production)
USE t_review_production;

-- Admin related tables
DESCRIBE admin_notifications;
DESCRIBE admin_password_resets;
DESCRIBE admins;

-- Core application tables
DESCRIBE advertisements;
DESCRIBE anotifications;
DESCRIBE appointments;

-- Cache tables
DESCRIBE cache;
DESCRIBE cache_locks;

-- Business related tables
DESCRIBE categories;
DESCRIBE certificates;
DESCRIBE companies;
DESCRIBE company_statistics;
DESCRIBE company_subscriptions;
DESCRIBE company_wallets;

-- Device and system tables
DESCRIBE device_tokens;
DESCRIBE extensions;

-- Job and queue tables
DESCRIBE failed_jobs;
DESCRIBE job_batches;
DESCRIBE jobs;

-- Feature and content tables
DESCRIBE features;
DESCRIBE forms;
DESCRIBE frontends;

-- Settings and configuration
DESCRIBE general_settings;

-- Language and localization
DESCRIBE languages;

-- Lead management
DESCRIBE lead_purchases;
DESCRIBE lead_visibilities;
DESCRIBE leads;

-- Location data
DESCRIBE locations;
DESCRIBE vietnam_districts;

-- Loyalty and rewards
DESCRIBE loyalty_points;
DESCRIBE referral_rewards;

-- System tables
DESCRIBE migrations;

-- Notification system
DESCRIBE notification_logs;
DESCRIBE notification_templates;
DESCRIBE notifications;

-- Content pages
DESCRIBE pages;

-- Authentication tables
DESCRIBE password_reset_tokens;
DESCRIBE password_resets;
DESCRIBE personal_access_tokens;

-- Portfolio and reviews
DESCRIBE portfolios;
DESCRIBE rating_details;
DESCRIBE ratings;
DESCRIBE reviews;

-- Session management
DESCRIBE sessions;

-- Subscription system
DESCRIBE subscription_packages;

-- Support system
DESCRIBE support_attachments;
DESCRIBE support_messages;
DESCRIBE support_tickets;

-- System logs and updates
DESCRIBE update_logs;

-- User management
DESCRIBE user_logins;
DESCRIBE user_notifications;
DESCRIBE users;

-- Financial transactions
DESCRIBE wallet_transactions; 
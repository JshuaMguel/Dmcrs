-- DMCRS Database Export for Supabase Import
-- Generated on: 2025-10-29 02:04:38

-- Table: approvals
CREATE TABLE "approvals" (
  "id" bigint unsigned NOT NULL ,
  "make_up_class_request_id" bigint unsigned NOT NULL,
  "chair_id" bigint unsigned NOT NULL,
  "position" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "decision" enum('recommended','rejected','approved') COLLATE utf8mb4_unicode_ci NOT NULL,
  "status" enum('approved','rejected','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  "is_final" tinyINTEGER NOT NULL DEFAULT '0',
  "remarks" text COLLATE utf8mb4_unicode_ci,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "approvals_make_up_class_request_id_foreign" ("make_up_class_request_id"),
  KEY "approvals_chair_id_foreign" ("chair_id"),
  CONSTRAINT "approvals_chair_id_foreign" FOREIGN KEY ("chair_id") REFERENCES "users" ("id") ON DELETE CASCADE,
  CONSTRAINT "approvals_make_up_class_request_id_foreign" FOREIGN KEY ("make_up_class_request_id") REFERENCES "make_up_class_requests" ("id") ON DELETE CASCADE
)   

-- Table: cache
CREATE TABLE "cache" (
  "key" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "value" mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  "expiration" int NOT NULL,
  PRIMARY KEY ("key")
)   

-- Table: cache_locks
CREATE TABLE "cache_locks" (
  "key" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "owner" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "expiration" int NOT NULL,
  PRIMARY KEY ("key")
)   

-- Table: departments
CREATE TABLE "departments" (
  "id" bigint unsigned NOT NULL ,
  "name" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id")
)  =5  

INSERT INTO "departments" ("id", "name", "created_at", "updated_at") VALUES (1, 'BSIT - Bachelor of Science in Information Technology', '2025-10-24 15:01:50', '2025-10-24 15:01:50');
INSERT INTO "departments" ("id", "name", "created_at", "updated_at") VALUES (2, 'BSA - Bachelor of Science in Agriculture', '2025-10-24 15:01:50', '2025-10-24 15:01:50');
INSERT INTO "departments" ("id", "name", "created_at", "updated_at") VALUES (3, 'BTLED - Bachelor of Technology and Livelihood Education', '2025-10-24 15:01:51', '2025-10-24 15:01:51');
INSERT INTO "departments" ("id", "name", "created_at", "updated_at") VALUES (4, 'BAT - Bachelor in Agricultural Technology', '2025-10-24 15:01:51', '2025-10-24 15:01:51');

-- Table: failed_jobs
CREATE TABLE "failed_jobs" (
  "id" bigint unsigned NOT NULL ,
  "uuid" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "connection" text COLLATE utf8mb4_unicode_ci NOT NULL,
  "queue" text COLLATE utf8mb4_unicode_ci NOT NULL,
  "payload" longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  "exception" longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  "failed_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY ("id"),
  UNIQUE KEY "failed_jobs_uuid_unique" ("uuid")
)   

-- Table: job_batches
CREATE TABLE "job_batches" (
  "id" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "name" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "total_jobs" int NOT NULL,
  "pending_jobs" int NOT NULL,
  "failed_jobs" int NOT NULL,
  "failed_job_ids" longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  "options" mediumtext COLLATE utf8mb4_unicode_ci,
  "cancelled_at" int DEFAULT NULL,
  "created_at" int NOT NULL,
  "finished_at" int DEFAULT NULL,
  PRIMARY KEY ("id")
)   

-- Table: jobs
CREATE TABLE "jobs" (
  "id" bigint unsigned NOT NULL ,
  "queue" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "payload" longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  "attempts" tinyint unsigned NOT NULL,
  "reserved_at" int unsigned DEFAULT NULL,
  "available_at" int unsigned NOT NULL,
  "created_at" int unsigned NOT NULL,
  PRIMARY KEY ("id"),
  KEY "jobs_queue_index" ("queue")
)   

-- Table: make_up_class_confirmations
CREATE TABLE "make_up_class_confirmations" (
  "id" bigint unsigned NOT NULL ,
  "make_up_class_request_id" bigint unsigned NOT NULL,
  "student_id" bigint unsigned NOT NULL,
  "status" enum('confirmed','declined') COLLATE utf8mb4_unicode_ci NOT NULL,
  "reason" text COLLATE utf8mb4_unicode_ci,
  "attended" tinyINTEGER NOT NULL DEFAULT '0',
  "confirmation_date" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "make_up_class_confirmations_make_up_class_request_id_foreign" ("make_up_class_request_id"),
  KEY "make_up_class_confirmations_student_id_foreign" ("student_id"),
  CONSTRAINT "make_up_class_confirmations_make_up_class_request_id_foreign" FOREIGN KEY ("make_up_class_request_id") REFERENCES "make_up_class_requests" ("id") ON DELETE CASCADE,
  CONSTRAINT "make_up_class_confirmations_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "users" ("id") ON DELETE CASCADE
)   

-- Table: make_up_class_requests
CREATE TABLE "make_up_class_requests" (
  "id" bigint unsigned NOT NULL ,
  "faculty_id" bigint unsigned NOT NULL,
  "subject_id" bigint unsigned DEFAULT NULL,
  "section_id" bigint unsigned DEFAULT NULL,
  "subject" VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  "subject_title" VARCHAR(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "room" VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  "reason" text COLLATE utf8mb4_unicode_ci NOT NULL,
  "preferred_date" date NOT NULL,
  "preferred_time" time NOT NULL,
  "end_time" time DEFAULT NULL,
  "status" enum('pending','CHAIR_APPROVED','CHAIR_REJECTED','HEAD_REJECTED','APPROVED','REJECTED','declined') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  "attachment" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "student_list" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "tracking_number" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "chair_remarks" text COLLATE utf8mb4_unicode_ci,
  "head_remarks" text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY ("id"),
  UNIQUE KEY "make_up_class_requests_tracking_number_unique" ("tracking_number"),
  KEY "make_up_class_requests_faculty_id_foreign" ("faculty_id"),
  KEY "make_up_class_requests_subject_id_foreign" ("subject_id"),
  KEY "make_up_class_requests_section_id_foreign" ("section_id"),
  CONSTRAINT "make_up_class_requests_faculty_id_foreign" FOREIGN KEY ("faculty_id") REFERENCES "users" ("id") ON DELETE CASCADE,
  CONSTRAINT "make_up_class_requests_section_id_foreign" FOREIGN KEY ("section_id") REFERENCES "sections" ("id") ON DELETE SET NULL,
  CONSTRAINT "make_up_class_requests_subject_id_foreign" FOREIGN KEY ("subject_id") REFERENCES "subjects" ("id") ON DELETE SET NULL
)   

-- Table: migrations
CREATE TABLE "migrations" (
  "id" int unsigned NOT NULL ,
  "migration" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "batch" int NOT NULL,
  PRIMARY KEY ("id")
)  =32  

INSERT INTO "migrations" ("id", "migration", "batch") VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (4, '2025_09_14_000000_create_departments_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (5, '2025_09_15_000000_update_user_roles', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (6, '2025_09_15_000001_update_user_roles_string', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (7, '2025_09_15_000002_recreate_users_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (8, '2025_09_15_121113_create_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (9, '2025_09_16_020723_create_notifications_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (10, '2025_09_16_035836_add_remarks_and_update_status_to_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (11, '2025_09_19_000001_create_rooms_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (12, '2025_09_19_000002_create_schedules_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (13, '2025_09_19_121000_create_approvals_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (14, '2025_09_23_000000_create_make_up_class_confirmations_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (15, '2025_09_24_153806_add_profile_fields_to_users_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (16, '2025_09_25_000001_add_position_status_isfinal_to_approvals_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (17, '2025_09_25_000002_update_decision_enum_in_approvals_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (18, '2025_09_26_000002_update_users_department_id', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (19, '2025_10_02_000001_add_subject_title_to_schedules_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (20, '2025_10_03_000001_add_student_list_to_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (21, '2025_10_03_000006_add_end_time_to_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (22, '2025_10_03_023749_add_subject_title_to_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (23, '2025_10_07_035903_add_is_active_to_users_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (24, '2025_10_09_232938_create_subjects_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (25, '2025_10_10_000834_add_subject_id_to_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (26, '2025_10_10_012213_create_sections_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (27, '2025_10_10_012637_add_section_id_to_make_up_class_requests_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (28, '2025_10_15_000627_add_class_type_to_schedules_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (29, '2025_10_15_100729_add_type_to_schedules_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (30, '2025_10_15_110500_add_type_to_schedules_table_v2', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (31, '2025_10_28_205945_add_attendance_to_make_up_class_confirmations_table', 2);

-- Table: notifications
CREATE TABLE "notifications" (
  "id" char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  "type" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "notifiable_type" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "notifiable_id" bigint unsigned NOT NULL,
  "data" text COLLATE utf8mb4_unicode_ci NOT NULL,
  "read_at" timestamp NULL DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "notifications_notifiable_type_notifiable_id_index" ("notifiable_type","notifiable_id")
)   

-- Table: password_reset_tokens
CREATE TABLE "password_reset_tokens" (
  "email" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "token" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("email")
)   

-- Table: rooms
CREATE TABLE "rooms" (
  "id" bigint unsigned NOT NULL ,
  "name" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "location" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "capacity" int DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id")
)   

-- Table: schedules
CREATE TABLE "schedules" (
  "id" bigint unsigned NOT NULL ,
  "semester" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "day_of_week" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "time_start" time NOT NULL,
  "time_end" time NOT NULL,
  "subject_code" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "subject_title" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "section" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "instructor_id" bigint unsigned DEFAULT NULL,
  "instructor_name" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "room" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "department_id" bigint unsigned NOT NULL,
  "status" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  "type" VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'REGULAR',
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  KEY "schedules_instructor_id_foreign" ("instructor_id"),
  KEY "schedules_department_id_foreign" ("department_id"),
  CONSTRAINT "schedules_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE CASCADE,
  CONSTRAINT "schedules_instructor_id_foreign" FOREIGN KEY ("instructor_id") REFERENCES "users" ("id") ON DELETE SET NULL
)   

-- Table: sections
CREATE TABLE "sections" (
  "id" bigint unsigned NOT NULL ,
  "department_id" bigint unsigned NOT NULL,
  "year_level" int NOT NULL,
  "section_name" VARCHAR(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE KEY "sections_department_id_year_level_section_name_unique" ("department_id","year_level","section_name"),
  CONSTRAINT "sections_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE CASCADE
)   

-- Table: sessions
CREATE TABLE "sessions" (
  "id" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "user_id" bigint unsigned DEFAULT NULL,
  "ip_address" VARCHAR(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "user_agent" text COLLATE utf8mb4_unicode_ci,
  "payload" longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  "last_activity" int NOT NULL,
  PRIMARY KEY ("id"),
  KEY "sessions_user_id_index" ("user_id"),
  KEY "sessions_last_activity_index" ("last_activity")
)   

INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('68ZkFV0K1KUDptrw91urdRrPT8SVq1mggnZ8Utua', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUnliUG03OTgyQ2h4S1o3TUtLWnBnZ1R5WnlmT2xtblhVbjNqUnJmNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('72klYabMAs9bZEpaZ5E9YFfvHc0Ay8nDSxDLVi0w', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoicENHd2FaZlRROHZ3eURya3hHVFJpZFJYSUZzcUVUWDA5WWppUG0yNyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('cjrt3hZOuqbrx5ARTybxUWOVeGCzAjy2VFcoDnz0', NULL, '::1', 'Go-http-client/2.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ0lvU1dBOXhTcXBEN2ZPODFXNjloTWwyUDBiMHRnMEQ5T0thYnJNVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9kbWNycy5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6QXg3MmFuQXZuTGZmMFZuTSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761658930);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('CQC04HI0rkbm3BRSfh3D2zwmxWhc5osbYVVd9qLm', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZFJYSHVLQUppa2JEamVPVTZZZm1iUnpNRjlrajhLSFA3cnFLNlRQeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('cQPtHZH5sYjPnVI3GfFDUg1nlNBVy3ggt0N44omG', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWnoycnBtdGd6NmNCdDRrRlMzN0l4dVRCMEI4S3RKU2pjUk5jdXdoWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('fIoY30rIq27GsKVT9SeWr4mbxmJkAUwy1GziplWF', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnlCMTF1anJnSTFyb0MxSXp4RVpib25VNGd3QXFoVWJvQXFRbmRWeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9kbWNycy5vbnJlbmRlci5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1761659060);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('HdwknJyk0OZYNoSPG328tZljC5sMq9Rp7mJ9AkBD', NULL, '::1', 'Go-http-client/1.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiN05GblpIMmlGQVB2d29HbjdzOVU5RHNwaDZjTnNyR1g2ZWtmTVFNSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761657616);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('iWNDHWLqcT6QOXTqmbKaT0W1CNhQ1NVqKxllQLdF', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoid2dyYWZnYURUbVVKMmR2UVdyWTE4U2ZDenBSdnhzbm1xOGRYTTd5byI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('Lw8R4r2s206yrvEDMYgPzUN42uwjgMsrAR5E5LWD', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiNk1ndUdSUlA2UzhUNnZZREhORmVLYXhkTFJHZ3pFVkZxQzlXMmdVcSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('N7pVCRplDydnExuZiALjcfOjEKfchWQqpJSdmjqF', NULL, '::1', 'Go-http-client/1.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTEhyZ09EMGZlbGxLRFUyUTdGWEtoNUdGbmRRWUIyc2tTTXJsdmN2VCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761658924);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('ossl8EhNmx679dlg9JWd4b98E9jSgq8JvH7YN3gw', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiWmlqWXFOQm1vSWphbVR6UXJ3czNhZXdMQ2g1b2xVajlZcnBXMFNCMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('RYv883B4TptdvO5zRaSI3M5FIkx6mDXJlNWvyNZ5', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTGtzQ3NURHFjQUFtclY1clNvMnhHZWVZMDVvb2NmR0pEMHRnVUh1QSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('tSguV9EcDzOERPDstP2SwzwC0Jem5lg92Xfqr9B9', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZkxnSzgyVVhnMDFLMkJoMkF1MFF2S0tPU2pSS2UwUWhKbEJ4MDZ5USI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761656010);
INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('ZIvolpw697SPLGukTwUfOsQGZ1S8HiKo7dks2YLN', NULL, '::1', 'Go-http-client/2.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUFERGhSZTNDRzloWEw3czZwVTFVNXRsYjVYUWNpVEpzSGdIbGFXZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9kbWNycy5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6Mjc6ImdlbmVyYXRlZDo6ODIzU0YyRlNUckRpMkdjNCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761657624);

-- Table: subjects
CREATE TABLE "subjects" (
  "id" bigint unsigned NOT NULL ,
  "department_id" bigint unsigned NOT NULL,
  "subject_code" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "subject_title" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "description" text COLLATE utf8mb4_unicode_ci,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  PRIMARY KEY ("id"),
  UNIQUE KEY "subjects_subject_code_unique" ("subject_code"),
  KEY "subjects_department_id_foreign" ("department_id"),
  CONSTRAINT "subjects_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE CASCADE
)  =38  

INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (1, 1, 'IT111', 'Information Technology 111', 'Introduction to Information Technology', '2025-10-24 15:01:52', '2025-10-24 15:01:52');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (2, 1, 'IT112', 'Information Technology 112', 'Basic Computer Concepts', '2025-10-24 15:01:52', '2025-10-24 15:01:52');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (3, 1, 'IT212', 'Information Technology 212', 'Advanced Programming Concepts', '2025-10-24 15:01:53', '2025-10-24 15:01:53');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (4, 1, 'IT213', 'Information Technology 213', 'System Analysis and Design', '2025-10-24 15:01:53', '2025-10-24 15:01:53');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (5, 1, 'IT215', 'Information Technology 215', 'Data Structures and Algorithms', '2025-10-24 15:01:53', '2025-10-24 15:01:53');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (6, 1, 'IT311', 'Information Technology 311', 'Database Management Systems', '2025-10-24 15:01:54', '2025-10-24 15:01:54');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (7, 1, 'IT312', 'Information Technology 312', 'Web Development', '2025-10-24 15:01:54', '2025-10-24 15:01:54');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (8, 1, 'IT314', 'Information Technology 314', 'Network and Security Fundamentals', '2025-10-24 15:01:54', '2025-10-24 15:01:54');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (9, 1, 'IT315', 'Information Technology 315', 'Software Engineering', '2025-10-24 15:01:54', '2025-10-24 15:01:54');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (10, 1, 'IT414', 'Information Technology 414', 'Capstone Project / IT Seminar', '2025-10-24 15:01:55', '2025-10-24 15:01:55');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (11, 1, 'ICT313', 'Information and Communications Technology 313', 'ICT in Education and Society', '2025-10-24 15:01:56', '2025-10-24 15:01:56');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (12, 1, 'ICT314', 'Information and Communications Technology 314', 'Networking and Systems Integration', '2025-10-24 15:02:00', '2025-10-24 15:02:00');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (13, 1, 'ICT321', 'Information and Communications Technology 321', 'Web Systems and Applications', '2025-10-24 15:02:03', '2025-10-24 15:02:03');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (14, 1, 'ICT329', 'Information and Communications Technology 329', 'ICT Project Implementation', '2025-10-24 15:02:05', '2025-10-24 15:02:05');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (15, 4, 'AFAE125-BAT', 'Agricultural and Fishery Arts Education 125', 'Introduction to AFAE principles', '2025-10-24 15:02:08', '2025-10-24 15:02:08');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (16, 2, 'AFAE125-BSA', 'Agricultural and Fishery Arts Education 125', 'Introduction to AFAE principles', '2025-10-24 15:02:08', '2025-10-24 15:02:08');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (17, 4, 'AFAE319-BAT', 'Agricultural and Fishery Arts Education 319', 'Advanced AFAE studies', '2025-10-24 15:02:09', '2025-10-24 15:02:09');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (18, 2, 'AFAE319-BSA', 'Agricultural and Fishery Arts Education 319', 'Advanced AFAE studies', '2025-10-24 15:02:09', '2025-10-24 15:02:09');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (19, 4, 'AgEcon111-BAT', 'Agricultural Economics 111', 'Economics in agriculture and agribusiness', '2025-10-24 15:02:10', '2025-10-24 15:02:10');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (20, 2, 'AgEcon111-BSA', 'Agricultural Economics 111', 'Economics in agriculture and agribusiness', '2025-10-24 15:02:11', '2025-10-24 15:02:11');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (21, 4, 'AgExt212-BAT', 'Agricultural Extension 212', 'Extension methods and practices', '2025-10-24 15:02:12', '2025-10-24 15:02:12');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (22, 2, 'AgExt212-BSA', 'Agricultural Extension 212', 'Extension methods and practices', '2025-10-24 15:02:12', '2025-10-24 15:02:12');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (23, 4, 'AgEx411-BAT', 'Agricultural Extension 411', 'Advanced agricultural extension', '2025-10-24 15:02:13', '2025-10-24 15:02:13');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (24, 2, 'AgEx411-BSA', 'Agricultural Extension 411', 'Advanced agricultural extension', '2025-10-24 15:02:13', '2025-10-24 15:02:13');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (25, 4, 'Agri111-BAT', 'Agriculture 111', 'Introduction to agriculture', '2025-10-24 15:02:14', '2025-10-24 15:02:14');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (26, 2, 'Agri111-BSA', 'Agriculture 111', 'Introduction to agriculture', '2025-10-24 15:02:14', '2025-10-24 15:02:14');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (27, 4, 'Agri212-BAT', 'Agriculture 212', 'Crop production management', '2025-10-24 15:02:15', '2025-10-24 15:02:15');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (28, 2, 'Agri212-BSA', 'Agriculture 212', 'Crop production management', '2025-10-24 15:02:28', '2025-10-24 15:02:28');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (29, 4, 'Agri213-BAT', 'Agriculture 213', 'Animal husbandry practices', '2025-10-24 15:02:38', '2025-10-24 15:02:38');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (30, 2, 'Agri213-BSA', 'Agriculture 213', 'Animal husbandry practices', '2025-10-24 15:02:48', '2025-10-24 15:02:48');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (31, 4, 'AgriIT211a-BAT', 'Agricultural Information Technology 211a', 'IT applications in agriculture (part A)', '2025-10-24 15:02:48', '2025-10-24 15:02:48');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (32, 2, 'AgriIT211a-BSA', 'Agricultural Information Technology 211a', 'IT applications in agriculture (part A)', '2025-10-24 15:02:51', '2025-10-24 15:02:51');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (33, 4, 'AgriIT211b-BAT', 'Agricultural Information Technology 211b', 'IT applications in agriculture (part B)', '2025-10-24 15:02:55', '2025-10-24 15:02:55');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (34, 2, 'AgriIT211b-BSA', 'Agricultural Information Technology 211b', 'IT applications in agriculture (part B)', '2025-10-24 15:02:56', '2025-10-24 15:02:56');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (35, 4, 'AgTech311-BAT', 'Agricultural Technology 311', 'Modern agricultural technologies', '2025-10-24 15:03:01', '2025-10-24 15:03:01');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (36, 2, 'AgTech311-BSA', 'Agricultural Technology 311', 'Modern agricultural technologies', '2025-10-24 15:03:25', '2025-10-24 15:03:25');
INSERT INTO "subjects" ("id", "department_id", "subject_code", "subject_title", "description", "created_at", "updated_at") VALUES (37, 4, 'AgTech312-BAT', 'Agricultural Technology 312', 'Agricultural innovations and trends', '2025-10-24 15:03:40', '2025-10-24 15:03:40');

-- Table: users
CREATE TABLE "users" (
  "id" bigint unsigned NOT NULL ,
  "name" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "email" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "email_verified_at" timestamp NULL DEFAULT NULL,
  "password" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "role" VARCHAR(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'faculty',
  "department_id" bigint unsigned DEFAULT NULL,
  "is_active" tinyINTEGER NOT NULL DEFAULT '1',
  "remember_token" VARCHAR(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "created_at" timestamp NULL DEFAULT NULL,
  "updated_at" timestamp NULL DEFAULT NULL,
  "profile_image" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "contact_number" VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  "bio" text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY ("id"),
  UNIQUE KEY "users_email_unique" ("email"),
  KEY "users_department_id_foreign" ("department_id"),
  CONSTRAINT "users_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE SET NULL
)  =2  

INSERT INTO "users" ("id", "name", "email", "email_verified_at", "password", "role", "department_id", "is_active", "remember_token", "created_at", "updated_at", "profile_image", "contact_number", "bio") VALUES (1, 'System Admin', 'admin@ustp.edu.ph', NULL, '$2y$12$s7KYz.t5L3hMELZ7sB7T9uZTkklfBQ4jc.e2vnKGmDI4XaOT3ab.i', 'super_admin', NULL, 1, NULL, '2025-10-24 15:12:11', '2025-10-24 15:12:11', NULL, NULL, NULL);


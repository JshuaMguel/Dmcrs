-- DMCRS Database Export for Supabase Import (PostgreSQL Compatible)
-- Generated on: 2025-10-29 02:04:38

-- Enable UUID extension for notifications table
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Table: departments (create first due to foreign key dependencies)
CREATE TABLE "departments" (
  "id" BIGSERIAL PRIMARY KEY,
  "name" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO "departments" ("id", "name", "created_at", "updated_at") VALUES 
(1, 'BSIT - Bachelor of Science in Information Technology', '2025-10-24 15:01:50', '2025-10-24 15:01:50'),
(2, 'BSA - Bachelor of Science in Agriculture', '2025-10-24 15:01:50', '2025-10-24 15:01:50'),
(3, 'BTLED - Bachelor of Technology and Livelihood Education', '2025-10-24 15:01:51', '2025-10-24 15:01:51'),
(4, 'BAT - Bachelor in Agricultural Technology', '2025-10-24 15:01:51', '2025-10-24 15:01:51');

-- Table: users (create second due to foreign key dependencies)
CREATE TABLE "users" (
  "id" BIGSERIAL PRIMARY KEY,
  "name" VARCHAR(255) NOT NULL,
  "email" VARCHAR(255) NOT NULL UNIQUE,
  "email_verified_at" TIMESTAMP NULL DEFAULT NULL,
  "password" VARCHAR(255) NOT NULL,
  "role" VARCHAR(255) NOT NULL DEFAULT 'faculty',
  "department_id" BIGINT DEFAULT NULL,
  "is_active" BOOLEAN NOT NULL DEFAULT true,
  "remember_token" VARCHAR(100) DEFAULT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  "profile_image" VARCHAR(255) DEFAULT NULL,
  "contact_number" VARCHAR(255) DEFAULT NULL,
  "bio" TEXT,
  CONSTRAINT "users_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE SET NULL
);

INSERT INTO "users" ("id", "name", "email", "email_verified_at", "password", "role", "department_id", "is_active", "remember_token", "created_at", "updated_at", "profile_image", "contact_number", "bio") VALUES 
(1, 'System Admin', 'admin@ustp.edu.ph', NULL, '$2y$12$s7KYz.t5L3hMELZ7sB7T9uZTkklfBQ4jc.e2vnKGmDI4XaOT3ab.i', 'super_admin', NULL, true, NULL, '2025-10-24 15:12:11', '2025-10-24 15:12:11', NULL, NULL, NULL);

-- Table: subjects
CREATE TABLE "subjects" (
  "id" BIGSERIAL PRIMARY KEY,
  "department_id" BIGINT NOT NULL,
  "subject_code" VARCHAR(255) NOT NULL UNIQUE,
  "subject_title" VARCHAR(255) NOT NULL,
  "description" TEXT,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT "subjects_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE CASCADE
);

-- Table: sections
CREATE TABLE "sections" (
  "id" BIGSERIAL PRIMARY KEY,
  "department_id" BIGINT NOT NULL,
  "year_level" INTEGER NOT NULL,
  "section_name" VARCHAR(10) NOT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT "sections_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE CASCADE,
  UNIQUE ("department_id", "year_level", "section_name")
);

-- Table: rooms
CREATE TABLE "rooms" (
  "id" BIGSERIAL PRIMARY KEY,
  "name" VARCHAR(255) NOT NULL,
  "location" VARCHAR(255) DEFAULT NULL,
  "capacity" INTEGER DEFAULT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL
);

-- Table: schedules
CREATE TABLE "schedules" (
  "id" BIGSERIAL PRIMARY KEY,
  "semester" VARCHAR(255) NOT NULL,
  "day_of_week" VARCHAR(255) NOT NULL,
  "time_start" TIME NOT NULL,
  "time_end" TIME NOT NULL,
  "subject_code" VARCHAR(255) NOT NULL,
  "subject_title" VARCHAR(255) DEFAULT NULL,
  "section" VARCHAR(255) NOT NULL,
  "instructor_id" BIGINT DEFAULT NULL,
  "instructor_name" VARCHAR(255) DEFAULT NULL,
  "room" VARCHAR(255) NOT NULL,
  "department_id" BIGINT NOT NULL,
  "status" VARCHAR(255) NOT NULL DEFAULT 'pending',
  "type" VARCHAR(20) NOT NULL DEFAULT 'REGULAR',
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT "schedules_instructor_id_foreign" FOREIGN KEY ("instructor_id") REFERENCES "users" ("id") ON DELETE SET NULL,
  CONSTRAINT "schedules_department_id_foreign" FOREIGN KEY ("department_id") REFERENCES "departments" ("id") ON DELETE CASCADE
);

-- Table: make_up_class_requests
CREATE TABLE "make_up_class_requests" (
  "id" BIGSERIAL PRIMARY KEY,
  "faculty_id" BIGINT NOT NULL,
  "subject_id" BIGINT DEFAULT NULL,
  "section_id" BIGINT DEFAULT NULL,
  "subject" VARCHAR(100) NOT NULL,
  "subject_title" VARCHAR(200) DEFAULT NULL,
  "room" VARCHAR(100) NOT NULL,
  "reason" TEXT NOT NULL,
  "preferred_date" DATE NOT NULL,
  "preferred_time" TIME NOT NULL,
  "end_time" TIME DEFAULT NULL,
  "status" VARCHAR(50) NOT NULL DEFAULT 'pending',
  "attachment" VARCHAR(255) DEFAULT NULL,
  "student_list" VARCHAR(255) DEFAULT NULL,
  "tracking_number" VARCHAR(255) NOT NULL UNIQUE,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  "chair_remarks" TEXT,
  "head_remarks" TEXT,
  CONSTRAINT "make_up_class_requests_faculty_id_foreign" FOREIGN KEY ("faculty_id") REFERENCES "users" ("id") ON DELETE CASCADE,
  CONSTRAINT "make_up_class_requests_section_id_foreign" FOREIGN KEY ("section_id") REFERENCES "sections" ("id") ON DELETE SET NULL,
  CONSTRAINT "make_up_class_requests_subject_id_foreign" FOREIGN KEY ("subject_id") REFERENCES "subjects" ("id") ON DELETE SET NULL
);

-- Table: approvals
CREATE TABLE "approvals" (
  "id" BIGSERIAL PRIMARY KEY,
  "make_up_class_request_id" BIGINT NOT NULL,
  "chair_id" BIGINT NOT NULL,
  "position" VARCHAR(255) DEFAULT NULL,
  "decision" VARCHAR(50) NOT NULL,
  "status" VARCHAR(50) NOT NULL DEFAULT 'pending',
  "is_final" BOOLEAN NOT NULL DEFAULT false,
  "remarks" TEXT,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT "approvals_chair_id_foreign" FOREIGN KEY ("chair_id") REFERENCES "users" ("id") ON DELETE CASCADE,
  CONSTRAINT "approvals_make_up_class_request_id_foreign" FOREIGN KEY ("make_up_class_request_id") REFERENCES "make_up_class_requests" ("id") ON DELETE CASCADE
);

-- Table: make_up_class_confirmations
CREATE TABLE "make_up_class_confirmations" (
  "id" BIGSERIAL PRIMARY KEY,
  "make_up_class_request_id" BIGINT NOT NULL,
  "student_id" BIGINT NOT NULL,
  "status" VARCHAR(50) NOT NULL,
  "reason" TEXT,
  "attended" BOOLEAN NOT NULL DEFAULT false,
  "confirmation_date" TIMESTAMP NULL DEFAULT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT "make_up_class_confirmations_make_up_class_request_id_foreign" FOREIGN KEY ("make_up_class_request_id") REFERENCES "make_up_class_requests" ("id") ON DELETE CASCADE,
  CONSTRAINT "make_up_class_confirmations_student_id_foreign" FOREIGN KEY ("student_id") REFERENCES "users" ("id") ON DELETE CASCADE
);

-- Table: cache
CREATE TABLE "cache" (
  "key" VARCHAR(255) PRIMARY KEY,
  "value" TEXT NOT NULL,
  "expiration" INTEGER NOT NULL
);

-- Table: cache_locks
CREATE TABLE "cache_locks" (
  "key" VARCHAR(255) PRIMARY KEY,
  "owner" VARCHAR(255) NOT NULL,
  "expiration" INTEGER NOT NULL
);

-- Table: failed_jobs
CREATE TABLE "failed_jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "uuid" VARCHAR(255) NOT NULL UNIQUE,
  "connection" TEXT NOT NULL,
  "queue" TEXT NOT NULL,
  "payload" TEXT NOT NULL,
  "exception" TEXT NOT NULL,
  "failed_at" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Table: job_batches
CREATE TABLE "job_batches" (
  "id" VARCHAR(255) PRIMARY KEY,
  "name" VARCHAR(255) NOT NULL,
  "total_jobs" INTEGER NOT NULL,
  "pending_jobs" INTEGER NOT NULL,
  "failed_jobs" INTEGER NOT NULL,
  "failed_job_ids" TEXT NOT NULL,
  "options" TEXT,
  "cancelled_at" INTEGER DEFAULT NULL,
  "created_at" INTEGER NOT NULL,
  "finished_at" INTEGER DEFAULT NULL
);

-- Table: jobs
CREATE TABLE "jobs" (
  "id" BIGSERIAL PRIMARY KEY,
  "queue" VARCHAR(255) NOT NULL,
  "payload" TEXT NOT NULL,
  "attempts" SMALLINT NOT NULL,
  "reserved_at" INTEGER DEFAULT NULL,
  "available_at" INTEGER NOT NULL,
  "created_at" INTEGER NOT NULL
);

CREATE INDEX "jobs_queue_index" ON "jobs" ("queue");

-- Table: migrations
CREATE TABLE "migrations" (
  "id" SERIAL PRIMARY KEY,
  "migration" VARCHAR(255) NOT NULL,
  "batch" INTEGER NOT NULL
);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES 
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_14_000000_create_departments_table', 1),
(5, '2025_09_15_000000_update_user_roles', 1),
(6, '2025_09_15_000001_update_user_roles_string', 1),
(7, '2025_09_15_000002_recreate_users_table', 1),
(8, '2025_09_15_121113_create_make_up_class_requests_table', 1),
(9, '2025_09_16_020723_create_notifications_table', 1),
(10, '2025_09_16_035836_add_remarks_and_update_status_to_make_up_class_requests_table', 1),
(11, '2025_09_19_000001_create_rooms_table', 1),
(12, '2025_09_19_000002_create_schedules_table', 1),
(13, '2025_09_19_121000_create_approvals_table', 1),
(14, '2025_09_23_000000_create_make_up_class_confirmations_table', 1),
(15, '2025_09_24_153806_add_profile_fields_to_users_table', 1),
(16, '2025_09_25_000001_add_position_status_isfinal_to_approvals_table', 1),
(17, '2025_09_25_000002_update_decision_enum_in_approvals_table', 1),
(18, '2025_09_26_000002_update_users_department_id', 1),
(19, '2025_10_02_000001_add_subject_title_to_schedules_table', 1),
(20, '2025_10_03_000001_add_student_list_to_make_up_class_requests_table', 1),
(21, '2025_10_03_000006_add_end_time_to_make_up_class_requests_table', 1),
(22, '2025_10_03_023749_add_subject_title_to_make_up_class_requests_table', 1),
(23, '2025_10_07_035903_add_is_active_to_users_table', 1),
(24, '2025_10_09_232938_create_subjects_table', 1),
(25, '2025_10_10_000834_add_subject_id_to_make_up_class_requests_table', 1),
(26, '2025_10_10_012213_create_sections_table', 1),
(27, '2025_10_10_012637_add_section_id_to_make_up_class_requests_table', 1),
(28, '2025_10_15_000627_add_class_type_to_schedules_table', 1),
(29, '2025_10_15_100729_add_type_to_schedules_table', 1),
(30, '2025_10_15_110500_add_type_to_schedules_table_v2', 1),
(31, '2025_10_28_205945_add_attendance_to_make_up_class_confirmations_table', 2);

-- Table: notifications
CREATE TABLE "notifications" (
  "id" UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  "type" VARCHAR(255) NOT NULL,
  "notifiable_type" VARCHAR(255) NOT NULL,
  "notifiable_id" BIGINT NOT NULL,
  "data" TEXT NOT NULL,
  "read_at" TIMESTAMP NULL DEFAULT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL,
  "updated_at" TIMESTAMP NULL DEFAULT NULL
);

CREATE INDEX "notifications_notifiable_type_notifiable_id_index" ON "notifications" ("notifiable_type", "notifiable_id");

-- Table: password_reset_tokens
CREATE TABLE "password_reset_tokens" (
  "email" VARCHAR(255) PRIMARY KEY,
  "token" VARCHAR(255) NOT NULL,
  "created_at" TIMESTAMP NULL DEFAULT NULL
);

-- Table: sessions
CREATE TABLE "sessions" (
  "id" VARCHAR(255) PRIMARY KEY,
  "user_id" BIGINT DEFAULT NULL,
  "ip_address" VARCHAR(45) DEFAULT NULL,
  "user_agent" TEXT,
  "payload" TEXT NOT NULL,
  "last_activity" INTEGER NOT NULL
);

CREATE INDEX "sessions_user_id_index" ON "sessions" ("user_id");
CREATE INDEX "sessions_last_activity_index" ON "sessions" ("last_activity");

-- Update sequences to match existing data
SELECT setval('departments_id_seq', 4, true);
SELECT setval('users_id_seq', 1, true);
SELECT setval('migrations_id_seq', 31, true);
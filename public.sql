/*
 Navicat Premium Dump SQL

 Source Server         : postgre
 Source Server Type    : PostgreSQL
 Source Server Version : 140018 (140018)
 Source Host           : localhost:5432
 Source Catalog        : jdd
 Source Schema         : public

 Target Server Type    : PostgreSQL
 Target Server Version : 140018 (140018)
 File Encoding         : 65001

 Date: 13/08/2025 21:38:04
*/

-- ----------------------------
-- Type structure for auth_provider
-- ----------------------------
DROP TYPE IF EXISTS "public"."auth_provider";
CREATE TYPE "public"."auth_provider" AS ENUM (
  'google',
  'email'
);
ALTER TYPE "public"."auth_provider" OWNER TO "bilal";

-- ----------------------------
-- Type structure for form_datatype
-- ----------------------------
DROP TYPE IF EXISTS "public"."form_datatype";
CREATE TYPE "public"."form_datatype" AS ENUM (
  'text',
  'number',
  'date',
  'datetime',
  'checkbox',
  'dropdown',
  'file'
);
ALTER TYPE "public"."form_datatype" OWNER TO "bilal";

-- ----------------------------
-- Type structure for transaction_status
-- ----------------------------
DROP TYPE IF EXISTS "public"."transaction_status";
CREATE TYPE "public"."transaction_status" AS ENUM (
  'pending',
  'paid',
  'cancelled',
  'expired'
);
ALTER TYPE "public"."transaction_status" OWNER TO "bilal";

-- ----------------------------
-- Sequence structure for migrations_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "public"."migrations_id_seq";
CREATE SEQUENCE "public"."migrations_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;
ALTER SEQUENCE "public"."migrations_id_seq" OWNER TO "bilal";

-- ----------------------------
-- Table structure for event_form_tickets
-- ----------------------------
DROP TABLE IF EXISTS "public"."event_form_tickets";
CREATE TABLE "public"."event_form_tickets" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "event_form_id" uuid NOT NULL,
  "ticket_id" uuid NOT NULL,
  "value" text COLLATE "pg_catalog"."default",
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."event_form_tickets" OWNER TO "bilal";

-- ----------------------------
-- Records of event_form_tickets
-- ----------------------------
BEGIN;
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('acda7078-ab41-439f-8e60-26350f32aed3', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '3a361a37-8ab5-4c9b-a6b0-8933e36052ae', 'VIP', '2025-08-06 21:46:38.622601+07', '2025-08-06 21:46:38.622601+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('52424de6-f8db-4f66-ac91-54f6284481f5', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '39b31615-63bd-4dce-a338-651813e38706', 'VIP', '2025-08-06 21:46:51.301166+07', '2025-08-06 21:46:51.301166+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('5413d957-a2dc-4d5e-ad3f-d081ea1188fa', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '5767b525-be67-4ed9-ac46-e7b1a92b3e5d', 'VIP', '2025-08-06 21:46:51.304602+07', '2025-08-06 21:46:51.304602+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('0d0d11c6-424c-4e8b-a7f0-20788bf273b5', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', 'caaa4af2-51b1-456d-b59c-895ce21db2a5', 'VIP', '2025-08-06 21:48:03.086056+07', '2025-08-06 21:48:03.086056+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('0ef82dd6-8cc8-444d-9f34-3d18109d6c1f', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '5535dc61-c557-44e6-81ff-87e0789a9aab', 'VIP', '2025-08-06 21:48:03.089721+07', '2025-08-06 21:48:03.089721+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('d939c43f-9b75-4ee0-9cad-6ba58d2c721d', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '9e682342-415e-4790-8428-d358f737bd30', 'VIP', '2025-08-06 21:49:05.007627+07', '2025-08-06 21:49:05.007627+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('50751a4e-faeb-4709-96b0-6a766fdeee97', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', 'f07baf6e-9180-456f-b87d-ad2a95385d3d', 'VIP', '2025-08-06 21:49:05.011642+07', '2025-08-06 21:49:05.011642+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('8f807615-1ddd-430f-bafb-10e0e67fe541', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '599f6f99-75fe-466f-9c17-526bfd91f74d', 'VIP', '2025-08-06 21:50:04.055744+07', '2025-08-06 21:50:04.055744+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('87670cba-4735-4e4f-b766-f0100ecd5f46', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', 'c7b7a5da-0166-4fd5-aa5a-2091482aacdd', 'VIP', '2025-08-06 21:50:04.058885+07', '2025-08-06 21:50:04.058885+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('7d9b07cb-da08-49d3-bfd5-91c246d475be', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '8ccd1706-7184-4e18-bb35-5fdfe7430811', 'VIP', '2025-08-06 21:50:14.990936+07', '2025-08-06 21:50:14.990936+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('30c48902-17f4-4049-b6d1-f864d6bc02a5', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '14eeb63c-90b0-4a3c-b1d8-5dde02ad26c5', 'VIP', '2025-08-06 21:50:14.994469+07', '2025-08-06 21:50:14.994469+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('485644c5-03ac-433e-b96a-44f05b9d1a9b', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', 'da9e910b-0c8a-4a44-a7a1-c4d70f2ab7cd', 'VIP', '2025-08-06 22:01:26.289949+07', '2025-08-06 22:01:26.289949+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('a3ef5e18-892a-4918-af23-48a96593e986', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '963d9a66-f1e2-4fca-a714-16e3d8160571', 'VIP', '2025-08-06 22:01:26.297071+07', '2025-08-06 22:01:26.297071+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('22813f51-b721-43e8-8731-f59f0c710815', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', 'aadea792-69d8-488b-b88e-c9b2950279fc', 'VIP', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
INSERT INTO "public"."event_form_tickets" ("id", "event_form_id", "ticket_id", "value", "created_at", "updated_at") VALUES ('6d42f65c-6f1b-40eb-8839-9b3755307956', '8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '97a560c7-9c59-4864-abf5-7e1a4d035c16', 'VIP', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
COMMIT;

-- ----------------------------
-- Table structure for event_forms
-- ----------------------------
DROP TABLE IF EXISTS "public"."event_forms";
CREATE TABLE "public"."event_forms" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "event_id" uuid NOT NULL,
  "label" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "datatype" "public"."form_datatype" NOT NULL,
  "options" text[] COLLATE "pg_catalog"."default",
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."event_forms" OWNER TO "bilal";

-- ----------------------------
-- Records of event_forms
-- ----------------------------
BEGIN;
INSERT INTO "public"."event_forms" ("id", "event_id", "label", "datatype", "options", "created_at", "updated_at") VALUES ('8aca3bbb-6c6e-4b28-b757-b0f2b2901878', '63ccec4c-4677-460b-95db-248e21db4b49', 'VIP Ticket', 'text', '{premium}', '2025-07-27 20:42:35.160803+07', '2025-07-27 20:42:35.160803+07');
INSERT INTO "public"."event_forms" ("id", "event_id", "label", "datatype", "options", "created_at", "updated_at") VALUES ('d8b7e427-a631-4d35-82d3-70d7dbe2d36a', '63ccec4c-4677-460b-95db-248e21db4b49', 'VIP Ticket', 'text', '{oke,test}', '2025-07-27 20:42:35.153642+07', '2025-07-27 20:42:35.153642+07');
COMMIT;

-- ----------------------------
-- Table structure for event_tickets
-- ----------------------------
DROP TABLE IF EXISTS "public"."event_tickets";
CREATE TABLE "public"."event_tickets" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "event_id" uuid NOT NULL,
  "name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "price" numeric(10,2) NOT NULL,
  "quota" int4 NOT NULL,
  "start_date" timestamptz(6) NOT NULL,
  "end_date" timestamptz(6) NOT NULL,
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."event_tickets" OWNER TO "bilal";

-- ----------------------------
-- Records of event_tickets
-- ----------------------------
BEGIN;
INSERT INTO "public"."event_tickets" ("id", "event_id", "name", "price", "quota", "start_date", "end_date", "created_at", "updated_at") VALUES ('98b9bfbe-763a-482b-b743-8f27840c0e9f', '63ccec4c-4677-460b-95db-248e21db4b49', 'premium', 100000.00, 100, '2025-08-01 17:00:00+07', '2025-08-01 17:00:00+07', '2025-07-27 20:22:35.476423+07', '2025-07-27 20:22:35.476423+07');
INSERT INTO "public"."event_tickets" ("id", "event_id", "name", "price", "quota", "start_date", "end_date", "created_at", "updated_at") VALUES ('b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '63ccec4c-4677-460b-95db-248e21db4b49', 'premium', 100000.00, 94, '2025-08-01 17:00:00+07', '2025-08-01 17:00:00+07', '2025-07-27 20:21:13.7854+07', '2025-07-27 20:21:13.7854+07');
COMMIT;

-- ----------------------------
-- Table structure for events
-- ----------------------------
DROP TABLE IF EXISTS "public"."events";
CREATE TABLE "public"."events" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "title" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "description" text COLLATE "pg_catalog"."default",
  "start_date" timestamptz(6) NOT NULL,
  "end_date" timestamptz(6) NOT NULL,
  "images" text COLLATE "pg_catalog"."default",
  "venue_name" varchar(255) COLLATE "pg_catalog"."default",
  "venue_address" text COLLATE "pg_catalog"."default",
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."events" OWNER TO "bilal";

-- ----------------------------
-- Records of events
-- ----------------------------
BEGIN;
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('63ccec4c-4677-460b-95db-248e21db4b49', 'Event 1', 'This is the description for event 1', '2025-08-07 13:00:24+07', '2025-07-31 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 1', 'Address 1', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('c7fddef3-3d81-4681-a7c8-5f4acb37bac9', 'Event 2', 'This is the description for event 2', '2025-08-17 13:00:24+07', '2025-08-10 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 2', 'Address 2', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('3c668005-8a01-4883-bad4-366447dd2a7a', 'Event 3', 'This is the description for event 3', '2025-08-11 13:00:24+07', '2025-08-11 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 3', 'Address 3', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('a7f05249-f9c4-4fe4-8af1-460adf9fb883', 'Event 4', 'This is the description for event 4', '2025-08-09 13:00:24+07', '2025-08-04 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 4', 'Address 4', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('7215ffb3-3ff3-4ee8-9e95-2f73e4fc503d', 'Event 5', 'This is the description for event 5', '2025-08-18 13:00:24+07', '2025-08-08 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 5', 'Address 5', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('e8aef099-f80a-4e76-80bf-6e190199b6b2', 'Event 6', 'This is the description for event 6', '2025-08-22 13:00:24+07', '2025-08-21 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 6', 'Address 6', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('80662c5d-5786-4b86-90c6-cb824aba5d14', 'Event 7', 'This is the description for event 7', '2025-08-13 13:00:24+07', '2025-08-05 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 7', 'Address 7', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('0ce1d92a-933d-40de-8fe2-8fc9ce426684', 'Event 8', 'This is the description for event 8', '2025-08-16 13:00:24+07', '2025-08-26 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 8', 'Address 8', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('2339e8e9-9803-4a3a-9e4d-27887f61c551', 'Event 9', 'This is the description for event 9', '2025-08-13 13:00:24+07', '2025-08-05 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 9', 'Address 9', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
INSERT INTO "public"."events" ("id", "title", "description", "start_date", "end_date", "images", "venue_name", "venue_address", "created_at", "updated_at") VALUES ('6a01929f-7d9e-436e-8a76-728e6a7a1001', 'Event 10', 'This is the description for event 10', '2025-08-20 13:00:24+07', '2025-08-17 15:00:24+07', '["image1.jpg","image2.jpg"]', 'Venue 10', 'Address 10', '2025-07-27 13:00:24+07', '2025-07-27 13:00:24+07');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS "public"."migrations";
CREATE TABLE "public"."migrations" (
  "id" int4 NOT NULL DEFAULT nextval('migrations_id_seq'::regclass),
  "migration" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "batch" int4 NOT NULL
)
;
ALTER TABLE "public"."migrations" OWNER TO "bilal";

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for ticket_users
-- ----------------------------
DROP TABLE IF EXISTS "public"."ticket_users";
CREATE TABLE "public"."ticket_users" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "user_id" uuid NOT NULL,
  "ticket_id" uuid NOT NULL,
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."ticket_users" OWNER TO "bilal";

-- ----------------------------
-- Records of ticket_users
-- ----------------------------
BEGIN;
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('b27a1ff5-fa4b-4a8a-a743-afdabb4cb7b0', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '3a361a37-8ab5-4c9b-a6b0-8933e36052ae', '2025-08-06 21:46:38.620672+07', '2025-08-06 21:46:38.620672+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('1d533dd9-fb0b-469f-8a52-36d1ce7eef35', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '39b31615-63bd-4dce-a338-651813e38706', '2025-08-06 21:46:51.299584+07', '2025-08-06 21:46:51.299584+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('2ea5b379-2e7f-46cc-8866-d17fe3a5c31e', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '5767b525-be67-4ed9-ac46-e7b1a92b3e5d', '2025-08-06 21:46:51.304039+07', '2025-08-06 21:46:51.304039+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('3263c983-3911-4dce-afd0-aa5f06f34a9e', '2a99b56e-74aa-4ee4-858d-d96601cdb263', 'caaa4af2-51b1-456d-b59c-895ce21db2a5', '2025-08-06 21:48:03.085053+07', '2025-08-06 21:48:03.085053+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('9de38dbf-0a36-40f1-833b-202408f4f501', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '5535dc61-c557-44e6-81ff-87e0789a9aab', '2025-08-06 21:48:03.089204+07', '2025-08-06 21:48:03.089204+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('4f8ea6af-4d09-413e-9545-19c0e2789f3e', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '9e682342-415e-4790-8428-d358f737bd30', '2025-08-06 21:49:05.006378+07', '2025-08-06 21:49:05.006378+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('7f6c640a-1b89-4fcc-b970-23ed38b823c2', '2a99b56e-74aa-4ee4-858d-d96601cdb263', 'f07baf6e-9180-456f-b87d-ad2a95385d3d', '2025-08-06 21:49:05.010864+07', '2025-08-06 21:49:05.010864+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('5c10ce30-93c5-448a-8d0c-15a270103932', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '599f6f99-75fe-466f-9c17-526bfd91f74d', '2025-08-06 21:50:04.054602+07', '2025-08-06 21:50:04.054602+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('981e0d91-b845-4bd3-9ec6-1c984dfc506f', '2a99b56e-74aa-4ee4-858d-d96601cdb263', 'c7b7a5da-0166-4fd5-aa5a-2091482aacdd', '2025-08-06 21:50:04.058442+07', '2025-08-06 21:50:04.058442+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('4fbd84a6-35e4-41b3-9e2d-264696849eb1', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '8ccd1706-7184-4e18-bb35-5fdfe7430811', '2025-08-06 21:50:14.98962+07', '2025-08-06 21:50:14.98962+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('339330ee-d27d-46db-85a6-4b636139cb43', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '14eeb63c-90b0-4a3c-b1d8-5dde02ad26c5', '2025-08-06 21:50:14.993807+07', '2025-08-06 21:50:14.993807+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('c57c02f3-5094-404c-81e0-97f4b75728b3', '2a99b56e-74aa-4ee4-858d-d96601cdb263', 'da9e910b-0c8a-4a44-a7a1-c4d70f2ab7cd', '2025-08-06 22:01:26.288048+07', '2025-08-06 22:01:26.288048+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('651c8ed5-246f-4579-9fd8-245bf918a295', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '963d9a66-f1e2-4fca-a714-16e3d8160571', '2025-08-06 22:01:26.29554+07', '2025-08-06 22:01:26.29554+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('e9606f7a-3141-488e-8f1c-053e833d4909', '2a99b56e-74aa-4ee4-858d-d96601cdb263', 'aadea792-69d8-488b-b88e-c9b2950279fc', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
INSERT INTO "public"."ticket_users" ("id", "user_id", "ticket_id", "created_at", "updated_at") VALUES ('0f2952ae-1d31-4fad-9c05-38d84a47241c', '2a99b56e-74aa-4ee4-858d-d96601cdb263', '97a560c7-9c59-4864-abf5-7e1a4d035c16', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
COMMIT;

-- ----------------------------
-- Table structure for tickets
-- ----------------------------
DROP TABLE IF EXISTS "public"."tickets";
CREATE TABLE "public"."tickets" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "email" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "code" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "event_ticket_id" uuid NOT NULL,
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."tickets" OWNER TO "bilal";

-- ----------------------------
-- Records of tickets
-- ----------------------------
BEGIN;
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('3a361a37-8ab5-4c9b-a6b0-8933e36052ae', 'bilal', 'mb336450@gmail.com', 'dp2i3jYmOW', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:46:38.617439+07', '2025-08-06 21:46:38.617439+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('39b31615-63bd-4dce-a338-651813e38706', 'bilal', 'mb336450@gmail.com', 'qHG6by3qWQ', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:46:51.298289+07', '2025-08-06 21:46:51.298289+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('5767b525-be67-4ed9-ac46-e7b1a92b3e5d', 'bilal', 'mb336450@gmail.com', 'gAH1r76mEy', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:46:51.303519+07', '2025-08-06 21:46:51.303519+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('caaa4af2-51b1-456d-b59c-895ce21db2a5', 'bilal', 'mb336450@gmail.com', 'sslcjXEGu9', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:48:03.084339+07', '2025-08-06 21:48:03.084339+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('5535dc61-c557-44e6-81ff-87e0789a9aab', 'bilal', 'mb336450@gmail.com', 'fzsBxcHRhx', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:48:03.08875+07', '2025-08-06 21:48:03.08875+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('9e682342-415e-4790-8428-d358f737bd30', 'bilal', 'mb336450@gmail.com', '8KZjvs17AO', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:49:05.005444+07', '2025-08-06 21:49:05.005444+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('f07baf6e-9180-456f-b87d-ad2a95385d3d', 'bilal', 'mb336450@gmail.com', 'NSliDXNL0r', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:49:05.010087+07', '2025-08-06 21:49:05.010087+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('599f6f99-75fe-466f-9c17-526bfd91f74d', 'bilal', 'mb336450@gmail.com', 'vNxAR24Ykc', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:50:04.053864+07', '2025-08-06 21:50:04.053864+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('c7b7a5da-0166-4fd5-aa5a-2091482aacdd', 'bilal', 'mb336450@gmail.com', 'syvgxW0Yfv', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:50:04.057884+07', '2025-08-06 21:50:04.057884+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('8ccd1706-7184-4e18-bb35-5fdfe7430811', 'bilal', 'mb336450@gmail.com', 'KUqz7EIsGz', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:50:14.988656+07', '2025-08-06 21:50:14.988656+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('14eeb63c-90b0-4a3c-b1d8-5dde02ad26c5', 'bilal', 'mb336450@gmail.com', 'l5yBCHXzs1', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 21:50:14.993099+07', '2025-08-06 21:50:14.993099+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('da9e910b-0c8a-4a44-a7a1-c4d70f2ab7cd', 'bilal', 'mb336450@gmail.com', 'zDR0dkzGFT', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 22:01:26.286186+07', '2025-08-06 22:01:26.286186+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('963d9a66-f1e2-4fca-a714-16e3d8160571', 'bilal', 'mb336450@gmail.com', '6DkKcEMMvn', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 22:01:26.294083+07', '2025-08-06 22:01:26.294083+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('aadea792-69d8-488b-b88e-c9b2950279fc', 'bilal', 'mb336450@gmail.com', 'RLnf0ZQEmy', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
INSERT INTO "public"."tickets" ("id", "name", "email", "code", "event_ticket_id", "created_at", "updated_at") VALUES ('97a560c7-9c59-4864-abf5-7e1a4d035c16', 'bilal', 'mb336450@gmail.com', 'cKqyCFqHS2', 'b704a18c-5a1a-4ece-8c5d-f3eb5c7c8fc0', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
COMMIT;

-- ----------------------------
-- Table structure for transaction_tickets
-- ----------------------------
DROP TABLE IF EXISTS "public"."transaction_tickets";
CREATE TABLE "public"."transaction_tickets" (
  "transaction_id" uuid NOT NULL,
  "ticket_id" uuid NOT NULL,
  "quantity" int4 NOT NULL DEFAULT 1,
  "price_at_purchase" numeric(10,2) NOT NULL
)
;
ALTER TABLE "public"."transaction_tickets" OWNER TO "bilal";

-- ----------------------------
-- Records of transaction_tickets
-- ----------------------------
BEGIN;
INSERT INTO "public"."transaction_tickets" ("transaction_id", "ticket_id", "quantity", "price_at_purchase") VALUES ('f67c5dec-4216-435e-bf96-5eedaf8c8bdc', 'aadea792-69d8-488b-b88e-c9b2950279fc', 1, 100000.00);
INSERT INTO "public"."transaction_tickets" ("transaction_id", "ticket_id", "quantity", "price_at_purchase") VALUES ('f67c5dec-4216-435e-bf96-5eedaf8c8bdc', '97a560c7-9c59-4864-abf5-7e1a4d035c16', 1, 100000.00);
COMMIT;

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS "public"."transactions";
CREATE TABLE "public"."transactions" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "event_id" uuid NOT NULL,
  "user_id" uuid,
  "status" "public"."transaction_status" NOT NULL DEFAULT 'pending'::transaction_status,
  "amount" numeric(10,2) NOT NULL,
  "payment_reference" varchar(255) COLLATE "pg_catalog"."default",
  "payment_url" text COLLATE "pg_catalog"."default",
  "payment_expired_at" timestamptz(6),
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."transactions" OWNER TO "bilal";

-- ----------------------------
-- Records of transactions
-- ----------------------------
BEGIN;
INSERT INTO "public"."transactions" ("id", "event_id", "user_id", "status", "amount", "payment_reference", "payment_url", "payment_expired_at", "created_at", "updated_at") VALUES ('f67c5dec-4216-435e-bf96-5eedaf8c8bdc', '63ccec4c-4677-460b-95db-248e21db4b49', '2a99b56e-74aa-4ee4-858d-d96601cdb263', 'pending', 214000.00, 'f67c5dec-4216-435e-bf96-5eedaf8c8bdc', 'https://sandbox.ipaymu.com/qris-basic/250806-92048-174191-222817', '2025-08-07 22:28:16+07', '2025-08-06 22:28:15.442605+07', '2025-08-06 22:28:15.442605+07');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS "public"."users";
CREATE TABLE "public"."users" (
  "id" uuid NOT NULL DEFAULT gen_random_uuid(),
  "email" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "password_hash" varchar(255) COLLATE "pg_catalog"."default",
  "full_name" varchar(255) COLLATE "pg_catalog"."default",
  "auth_provider" "public"."auth_provider" NOT NULL DEFAULT 'email'::auth_provider,
  "google_id" varchar(255) COLLATE "pg_catalog"."default",
  "is_email_verified" bool DEFAULT false,
  "email_verification_token" varchar(255) COLLATE "pg_catalog"."default",
  "email_verification_expires_at" timestamp(6),
  "password_reset_token" varchar(255) COLLATE "pg_catalog"."default",
  "password_reset_expires_at" timestamp(6),
  "created_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP,
  "updated_at" timestamptz(6) DEFAULT CURRENT_TIMESTAMP
)
;
ALTER TABLE "public"."users" OWNER TO "bilal";

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO "public"."users" ("id", "email", "password_hash", "full_name", "auth_provider", "google_id", "is_email_verified", "email_verification_token", "email_verification_expires_at", "password_reset_token", "password_reset_expires_at", "created_at", "updated_at") VALUES ('2a99b56e-74aa-4ee4-858d-d96601cdb263', 'mb336450@gmail.com', '$2y$12$fQt58yV4ElNVkKm7VPpGSuXGI2tU0NMSIY.glJk.naPVlTXKWTFWO', 'bilal', 'email', NULL, 'f', NULL, NULL, NULL, NULL, '2025-08-06 21:42:30.865514+07', '2025-08-06 21:42:30.865514+07');
COMMIT;

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "public"."migrations_id_seq"
OWNED BY "public"."migrations"."id";
SELECT setval('"public"."migrations_id_seq"', 1, false);

-- ----------------------------
-- Indexes structure for table event_form_tickets
-- ----------------------------
CREATE INDEX "idx_event_form_tickets_form_id" ON "public"."event_form_tickets" USING btree (
  "event_form_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_event_form_tickets_ticket_id" ON "public"."event_form_tickets" USING btree (
  "ticket_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table event_form_tickets
-- ----------------------------
ALTER TABLE "public"."event_form_tickets" ADD CONSTRAINT "uq_form_ticket_pair" UNIQUE ("event_form_id", "ticket_id");

-- ----------------------------
-- Primary Key structure for table event_form_tickets
-- ----------------------------
ALTER TABLE "public"."event_form_tickets" ADD CONSTRAINT "event_form_tickets_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table event_forms
-- ----------------------------
CREATE INDEX "idx_event_forms_event_id" ON "public"."event_forms" USING btree (
  "event_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table event_forms
-- ----------------------------
ALTER TABLE "public"."event_forms" ADD CONSTRAINT "event_forms_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table event_tickets
-- ----------------------------
CREATE INDEX "idx_event_tickets_event_id" ON "public"."event_tickets" USING btree (
  "event_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table event_tickets
-- ----------------------------
ALTER TABLE "public"."event_tickets" ADD CONSTRAINT "event_tickets_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table events
-- ----------------------------
ALTER TABLE "public"."events" ADD CONSTRAINT "events_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table migrations
-- ----------------------------
ALTER TABLE "public"."migrations" ADD CONSTRAINT "migrations_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table ticket_users
-- ----------------------------
CREATE INDEX "idx_ticket_users_ticket_id" ON "public"."ticket_users" USING btree (
  "ticket_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_ticket_users_user_id" ON "public"."ticket_users" USING btree (
  "user_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table ticket_users
-- ----------------------------
ALTER TABLE "public"."ticket_users" ADD CONSTRAINT "uq_ticket_user_pair" UNIQUE ("user_id", "ticket_id");

-- ----------------------------
-- Primary Key structure for table ticket_users
-- ----------------------------
ALTER TABLE "public"."ticket_users" ADD CONSTRAINT "ticket_users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table tickets
-- ----------------------------
CREATE INDEX "idx_tickets_code" ON "public"."tickets" USING btree (
  "code" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tickets_email" ON "public"."tickets" USING btree (
  "email" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "idx_tickets_event_ticket_id" ON "public"."tickets" USING btree (
  "event_ticket_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table tickets
-- ----------------------------
ALTER TABLE "public"."tickets" ADD CONSTRAINT "tickets_code_key" UNIQUE ("code");

-- ----------------------------
-- Primary Key structure for table tickets
-- ----------------------------
ALTER TABLE "public"."tickets" ADD CONSTRAINT "tickets_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table transaction_tickets
-- ----------------------------
CREATE INDEX "idx_transaction_tickets_ticket_id" ON "public"."transaction_tickets" USING btree (
  "ticket_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_transaction_tickets_transaction_id" ON "public"."transaction_tickets" USING btree (
  "transaction_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Primary Key structure for table transaction_tickets
-- ----------------------------
ALTER TABLE "public"."transaction_tickets" ADD CONSTRAINT "transaction_tickets_pkey" PRIMARY KEY ("transaction_id", "ticket_id");

-- ----------------------------
-- Indexes structure for table transactions
-- ----------------------------
CREATE INDEX "idx_transactions_event_id" ON "public"."transactions" USING btree (
  "event_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);
CREATE INDEX "idx_transactions_payment_reference" ON "public"."transactions" USING btree (
  "payment_reference" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "idx_transactions_status" ON "public"."transactions" USING btree (
  "status" "pg_catalog"."enum_ops" ASC NULLS LAST
);
CREATE INDEX "idx_transactions_user_id" ON "public"."transactions" USING btree (
  "user_id" "pg_catalog"."uuid_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table transactions
-- ----------------------------
ALTER TABLE "public"."transactions" ADD CONSTRAINT "transactions_payment_reference_key" UNIQUE ("payment_reference");

-- ----------------------------
-- Primary Key structure for table transactions
-- ----------------------------
ALTER TABLE "public"."transactions" ADD CONSTRAINT "transactions_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Indexes structure for table users
-- ----------------------------
CREATE INDEX "idx_users_email" ON "public"."users" USING btree (
  "email" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);
CREATE INDEX "idx_users_google_id" ON "public"."users" USING btree (
  "google_id" COLLATE "pg_catalog"."default" "pg_catalog"."text_ops" ASC NULLS LAST
);

-- ----------------------------
-- Uniques structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_email_key" UNIQUE ("email");
ALTER TABLE "public"."users" ADD CONSTRAINT "users_google_id_key" UNIQUE ("google_id");
ALTER TABLE "public"."users" ADD CONSTRAINT "users_email_verification_token_key" UNIQUE ("email_verification_token");
ALTER TABLE "public"."users" ADD CONSTRAINT "users_password_reset_token_key" UNIQUE ("password_reset_token");

-- ----------------------------
-- Primary Key structure for table users
-- ----------------------------
ALTER TABLE "public"."users" ADD CONSTRAINT "users_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Keys structure for table event_form_tickets
-- ----------------------------
ALTER TABLE "public"."event_form_tickets" ADD CONSTRAINT "fk_form_ticket_event_form" FOREIGN KEY ("event_form_id") REFERENCES "public"."event_forms" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE "public"."event_form_tickets" ADD CONSTRAINT "fk_form_ticket_ticket" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table event_forms
-- ----------------------------
ALTER TABLE "public"."event_forms" ADD CONSTRAINT "fk_event_form_event" FOREIGN KEY ("event_id") REFERENCES "public"."events" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table event_tickets
-- ----------------------------
ALTER TABLE "public"."event_tickets" ADD CONSTRAINT "fk_event_ticket_event" FOREIGN KEY ("event_id") REFERENCES "public"."events" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table ticket_users
-- ----------------------------
ALTER TABLE "public"."ticket_users" ADD CONSTRAINT "fk_ticket_user_ticket" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;
ALTER TABLE "public"."ticket_users" ADD CONSTRAINT "fk_ticket_user_user" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table tickets
-- ----------------------------
ALTER TABLE "public"."tickets" ADD CONSTRAINT "fk_ticket_event_ticket" FOREIGN KEY ("event_ticket_id") REFERENCES "public"."event_tickets" ("id") ON DELETE RESTRICT ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table transaction_tickets
-- ----------------------------
ALTER TABLE "public"."transaction_tickets" ADD CONSTRAINT "fk_tt_ticket" FOREIGN KEY ("ticket_id") REFERENCES "public"."tickets" ("id") ON DELETE RESTRICT ON UPDATE NO ACTION;
ALTER TABLE "public"."transaction_tickets" ADD CONSTRAINT "fk_tt_transaction" FOREIGN KEY ("transaction_id") REFERENCES "public"."transactions" ("id") ON DELETE CASCADE ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table transactions
-- ----------------------------
ALTER TABLE "public"."transactions" ADD CONSTRAINT "fk_transaction_event" FOREIGN KEY ("event_id") REFERENCES "public"."events" ("id") ON DELETE RESTRICT ON UPDATE NO ACTION;
ALTER TABLE "public"."transactions" ADD CONSTRAINT "fk_transaction_user" FOREIGN KEY ("user_id") REFERENCES "public"."users" ("id") ON DELETE SET NULL ON UPDATE NO ACTION;

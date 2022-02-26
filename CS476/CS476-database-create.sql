DROP TABLE IF EXISTS CS476_contributors;
DROP TABLE IF EXISTS CS476_reminders;
DROP TABLE IF EXISTS CS476_expenses;
DROP TABLE IF EXISTS CS476_tools;
DROP TABLE IF EXISTS CS476_plants;
DROP TABLE IF EXISTS CS476_seeds;
DROP TABLE IF EXISTS CS476_event_entries;
DROP TABLE IF EXISTS CS476_note_entries;
DROP TABLE IF EXISTS CS476_photo_entries;
DROP TABLE IF EXISTS CS476_journal_entries;
DROP TABLE IF EXISTS CS476_journal_pages;
DROP TABLE IF EXISTS CS476_journals;
DROP TABLE IF EXISTS CS476_users;

CREATE TABLE CS476_users (
    user_id INT AUTO_INCREMENT,
    email varchar(320) NOT NULL,
    username varchar(64) NOT NULL,
    password varchar(64) NOT NULL,
    avatar varchar(64),
    city varchar(32),
    prov varchar(32),
    bday date NOT NULL,
    is_logged_in boolean DEFAULT 0,
    PRIMARY KEY(user_id)
);

CREATE TABLE CS476_journals (
    journal_id INT AUTO_INCREMENT,
    user_id INT,
    title varchar(80),
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(journal_id),
    FOREIGN KEY(user_id) REFERENCES CS476_users(user_id)
);

CREATE TABLE CS476_contributors (
    contributor_id INT AUTO_INCREMENT,
    user_id INT, 
    journal_id INT,
    PRIMARY KEY(contributor_id),
    FOREIGN KEY(user_id) REFERENCES CS476_users(user_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);

CREATE TABLE CS476_journal_pages (
    page_id INT AUTO_INCREMENT,
    journal_id INT,
    page_date date,
    weather_high INT,
    weather_low INT,
    conditions varchar(80),
    PRIMARY KEY(page_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);

CREATE TABLE CS476_journal_entries (
    entry_id INT AUTO_INCREMENT,
    page_id INT,
    entry_type INT,
    PRIMARY KEY(entry_id),
    FOREIGN KEY(page_id) REFERENCES CS476_journal_pages(page_id)
);

CREATE TABLE CS476_event_entries (
    event_ID INT AUTO_INCREMENT,
    entry_id INT,
    event_type ENUM('planted', 'watered', 'weeding', 'fertalized', 'harvested'),
    event_note varchar(100),
    PRIMARY KEY(event_ID),
    FOREIGN KEY(entry_id) REFERENCES CS476_journal_entries(entry_id)
);

CREATE TABLE CS476_note_entries (
    note_id INT AUTO_INCREMENT,
    entry_id INT,
    note_path varchar(256),
    PRIMARY KEY(note_id),
    FOREIGN KEY(entry_id) REFERENCES CS476_journal_entries(entry_id)
);

CREATE TABLE CS476_photo_entries (
    photo_id INT AUTO_INCREMENT,
    entry_id INT,
    journal_id INT, 
    photo_path varchar(256),
    photo_date date DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(photo_id),
    FOREIGN KEY(entry_id) REFERENCES CS476_journal_entries(entry_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);

CREATE TABLE CS476_expenses (
    expense_id INT AUTO_INCREMENT,
    journal_id INT,
    entry_id INT,
    expensse_type ENUM('seeds', 'tools', 'other'),
    expense_description varchar(80),
    expence_cost DECIMAL(10, 2),
    expence_date date,
    PRIMARY KEY(expense_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id),
    FOREIGN KEY(entry_id) REFERENCES CS476_journal_entries(entry_id)
);

CREATE TABLE CS476_tools (
    tool_id INT AUTO_INCREMENT,
    journal_id INT,
    tool_name varchar(80),
    tool_condition ENUM('NEW', 'SLITELY USED', 'WELL WORN', 'NEEDS MAINTANANCE', 'REPLACE'),
    PRIMARY KEY(tool_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);

CREATE TABLE CS476_plants (
    plant_id INT AUTO_INCREMENT,
    journal_id INT,
    plant_name varchar(80),
    plant_type ENUM('Frut/Vegtible', 'Herb' ,'Flower', 'Tree/Bush'),
    number_planted INT,
    date_planted date,
    date_to_harvest date,
    PRIMARY KEY(plant_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);

CREATE TABLE CS476_seeds (
    seed_id INT AUTO_INCREMENT,
    journal_id INT,
    seed_name varchar(80),
    date_bought date,
    bought_from varchar(80),
    note varchar(256),
    PRIMARY KEY(seed_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);

CREATE TABLE CS476_reminders (
    reminder_id INT AUTO_INCREMENT,
    journal_id INT,
    reminder_note varchar(100),
    reminder_status boolean DEFAULT 0,
    due_by date,
    PRIMARY KEY(reminder_id),
    FOREIGN KEY(journal_id) REFERENCES CS476_journals(journal_id)
);
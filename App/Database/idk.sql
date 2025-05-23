-- inicializar.sql

-- MEDIA TABLES --

CREATE TABLE Service_Media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    service_id INTEGER,
    type TEXT CHECK(type IN ('image', 'video')),
    path TEXT,
    FOREIGN KEY (service_id) REFERENCES Service(service_id)
);


-- USER TABLES --

CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100),
    username VARCHAR(30) UNIQUE,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    balance NUMERIC(6,2) DEFAULT 0
);

CREATE TABLE Client (
    id INTEGER PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES User(id)
);

CREATE TABLE Freelancer (
    id INTEGER PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES User(id)
);

CREATE TABLE Admin (
    id INTEGER PRIMARY KEY,
    FOREIGN KEY (id) REFERENCES User(id)
);


-- SERVICE TABLES --

CREATE TABLE Category (
    category_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT UNIQUE NOT NULL
);

CREATE TABLE Service (
    service_id INTEGER PRIMARY KEY AUTOINCREMENT,
    freelancer_id INTEGER,
    category_id INTEGER,
    title TEXT,
    description TEXT,
    base_price NUMERIC(6,2),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (freelancer_id) REFERENCES Freelancer(id),
    FOREIGN KEY (category_id) REFERENCES Category(category_id),
    UNIQUE (service_id, freelancer_id)
);


CREATE TABLE Conversation (
    conversation_id INTEGER PRIMARY KEY AUTOINCREMENT,
    service_id INTEGER,
    client_id INTEGER,
    freelancer_id INTEGER,
    order_id INTEGER,
    FOREIGN KEY (service_id) REFERENCES Service(service_id),
    FOREIGN KEY (client_id) REFERENCES Client(id),
    FOREIGN KEY (freelancer_id) REFERENCES Freelancer(id),
    FOREIGN KEY (order_id) REFERENCES Demand(order_id)
);

CREATE TABLE Message (
    message_id INTEGER PRIMARY KEY AUTOINCREMENT,
    conversation_id INTEGER,
    send_id INTEGER,
    text TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (send_id) REFERENCES User(id),
    FOREIGN KEY (conversation_id) REFERENCES Conversation(conversation_id)
);

CREATE TABLE Demand (
    order_id INTEGER PRIMARY KEY AUTOINCREMENT,
    service_id INTEGER,
    client_id INTEGER,
    completed INTEGER DEFAULT 0,
    date_completed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES Service(service_id),
    FOREIGN KEY (client_id) REFERENCES Client(id)
);

CREATE TABLE Review (
    service_id INTEGER,
    client_id INTEGER,
    rating INTEGER,
    comment TEXT,
    date_pub TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (service_id, client_id),
    FOREIGN KEY (service_id) REFERENCES Service(service_id),
    FOREIGN KEY (client_id) REFERENCES Client(id)
);

CREATE TABLE UserTransaction (
    transaction_id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER,
    amount NUMERIC(6,2),
    description TEXT,
    requested INTEGER DEFAULT 0,
    paid INTEGER DEFAULT 0,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES Demand(order_id)
);

CREATE TABLE Proposal (
    proposal_id INTEGER PRIMARY KEY AUTOINCREMENT,
    conversation_id INTEGER NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES Conversation(conversation_id)
);

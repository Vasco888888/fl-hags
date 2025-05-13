-- inicializar.sql

-- IMAGE TABLES --

/* CREATE TABLE Service_Image (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    path TEXT
);

CREATE TABLE User_Image (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    path TEXT
); */

-- USER TABLES --

CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100),
    username VARCHAR(30),
    email VARCHAR(255),
    password VARCHAR(255),
    img_id INTEGER DEFAULT NULL,
    FOREIGN KEY (img_id) REFERENCES User_Image(id)
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

CREATE TABLE Service (
    service_id INTEGER PRIMARY KEY AUTOINCREMENT,
    freelancer_id INTEGER,
    title TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    base_price NUMERIC(6,2),
    img_id INTEGER DEFAULT NULL,
    FOREIGN KEY (img_id) REFERENCES Service_Image(id),
    FOREIGN KEY (freelancer_id) REFERENCES Freelancer(id)
);


CREATE TABLE Conversation (
    conversation_id INTEGER PRIMARY KEY AUTOINCREMENT,
    service_id INTEGER,
    client_id INTEGER,
    freelancer_id INTEGER,
    FOREIGN KEY (service_id) REFERENCES Service(service_id),
    FOREIGN KEY (client_id) REFERENCES Client(id),
    FOREIGN KEY (freelancer_id) REFERENCES Freelancer(id)
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
    date_completed TIMESTAMP,
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

--ACTIONS--

/* CREATE TRIGGER update_date_completed
AFTER UPDATE ON Demand
FOR EACH ROW
WHEN NEW.completed = TRUE AND OLD.completed = FALSE
BEGIN
    UPDATE Demand
    SET date_completed = CURRENT_TIMESTAMP
    WHERE order_id = NEW.order_id;
END;



CREATE TRIGGER delete_messages_after_conversation
AFTER DELETE ON Conversation
FOR EACH ROW
BEGIN
    DELETE FROM Message WHERE conversation_id = OLD.conversation_id;
END;



CREATE TRIGGER prevent_user_delete
BEFORE DELETE ON User
FOR EACH ROW
WHEN EXISTS (SELECT 1 FROM Service WHERE freelancer_id = OLD.id)
   OR EXISTS (SELECT 1 FROM Demand WHERE client_id = OLD.id)
BEGIN
    SELECT RAISE(ABORT, 'Cannot delete user linked to services or orders.');
END;



CREATE TRIGGER trg_auto_update_service_time
BEFORE UPDATE ON Service
FOR EACH ROW
BEGIN
    UPDATE Service
    SET updated_at = CURRENT_TIMESTAMP
    WHERE service_id = OLD.service_id;
END;



CREATE TRIGGER trg_auto_update_review_date
BEFORE UPDATE ON Review
FOR EACH ROW
BEGIN
    UPDATE Review
    SET date_pub = CURRENT_TIMESTAMP
    WHERE service_id = OLD.service_id AND client_id = OLD.client_id;
END; */

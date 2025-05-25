-- inicializar.sql
DROP TABLE IF EXISTS Proposal;
DROP TABLE IF EXISTS UserTransaction;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Demand;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Conversation;
DROP TABLE IF EXISTS Service;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Freelancer;
DROP TABLE IF EXISTS Client;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Service_Media;
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


-- Insert data

-- Insert Admins
INSERT INTO User (name, username, email, password) VALUES 
('Admin One', 'admin1', 'admin1@example.com', 'admin1'),
('Admin Two', 'admin2', 'admin2@example.com', 'admin2');

INSERT INTO Admin (id) VALUES (1), (2);

-- Insert Freelancers
INSERT INTO User (name, username, email, password) VALUES 
('Freelancer One', 'freelancer1', 'freelancer1@example.com', 'freelancer1'),
('Freelancer Two', 'freelancer2', 'freelancer2@example.com', 'freelancer2'),
('Freelancer Three', 'freelancer3', 'freelancer3@example.com', 'freelancer3'),
('Freelancer Four', 'freelancer4', 'freelancer4@example.com', 'freelancer4'),
('Freelancer Five', 'freelancer5', 'freelancer5@example.com', 'freelancer5'),
('Freelancer Six', 'freelancer6', 'freelancer6@example.com', 'freelancer6'),
('Freelancer Seven', 'freelancer7', 'freelancer7@example.com', 'freelancer7'),
('Freelancer Eight', 'freelancer8', 'freelancer8@example.com', 'freelancer8'),
('Freelancer Nine', 'freelancer9', 'freelancer9@example.com', 'freelancer9'),
('Freelancer Ten', 'freelancer10', 'freelancer10@example.com', 'freelancer10');

INSERT INTO Freelancer (id) VALUES 
(3), (4), (5), (6), (7), (8), (9), (10), (11), (12);

-- Insert Clients
INSERT INTO User (name, username, email, password) VALUES 
('Client One', 'client1', 'client1@example.com', 'client1'),
('Client Two', 'client2', 'client2@example.com', 'client2'),
('Client Three', 'client3', 'client3@example.com', 'client3'),
('Client Four', 'client4', 'client4@example.com', 'client4'),
('Client Five', 'client5', 'client5@example.com', 'client5'),
('Client Six', 'client6', 'client6@example.com', 'client6'),
('Client Seven', 'client7', 'client7@example.com', 'client7'),
('Client Eight', 'client8', 'client8@example.com', 'client8'),
('Client Nine', 'client9', 'client9@example.com', 'client9'),
('Client Ten', 'client10', 'client10@example.com', 'client10'),
('Client Eleven', 'client11', 'client11@example.com', 'client11'),
('Client Twelve', 'client12', 'client12@example.com', 'client12'),
('Client Thirteen', 'client13', 'client13@example.com', 'client13'),
('Client Fourteen', 'client14', 'client14@example.com', 'client14'),
('Client Fifteen', 'client15', 'client15@example.com', 'client15');

INSERT INTO Client (id) VALUES 
(13), (14), (15), (16), (17), (18), (19), (20), (21), (22), (23), (24), (25), (26), (27);

-- Insert Categories
INSERT INTO Category (name) VALUES 
('House Cleaning'),
('Laundry Services'),
('Handyman Repairs'),
('Garden Maintenance'),
('Appliance Installation'),
('Babysitting'),
('Dog Walking'),
('Home Decoration Help'),
('Other');

-- Insert Services for each Freelancer (7 each, 70 total)
-- Category ID reference:
-- 1: House Cleaning
-- 2: Laundry Services
-- 3: Handyman Repairs
-- 4: Garden Maintenance
-- 5: Appliance Installation
-- 6: Babysitting
-- 7: Dog Walking
-- 8: Home Decoration Help
-- 9: Other

-- Sample services for freelancers
-- Loop over freelancer_id 3 to 12 (10 total), assign 7 services each
-- Rotate through category_id 1-8

INSERT INTO Service (freelancer_id, category_id, title, description, base_price) VALUES
-- Freelancer 1
(3, 1, 'Sparkle Home Cleaning', 'Thorough cleaning of all rooms, kitchen, and bathroom.', 45),
(3, 2, 'Quick Laundry Pickup', 'Fast laundry pickup and drop-off at your doorstep.', 30),
(3, 3, 'Fix It All Repairs', 'Basic plumbing, furniture fixes, and more.', 50),
(3, 4, 'Lawn and Hedge Trim', 'Keep your garden clean and sharp.', 35),
(3, 5, 'Appliance Setup Help', 'Install microwaves, TVs, and other appliances.', 40),
(3, 6, 'Evening Babysitting', 'Reliable care for your kids while you’re out.', 25),
(3, 7, 'Daily Dog Walk', '30-minute walks with your furry friend.', 20),

-- Freelancer 2
(4, 8, 'Decor Help', 'Rearrange and beautify your space.', 38),
(4, 1, 'Weekend Cleaning', 'Deep cleaning for weekends.', 42),
(4, 2, 'Delicate Laundry Care', 'Gentle cleaning of sensitive fabrics.', 28),
(4, 3, 'Light Fixture Fixes', 'Install or repair lights and outlets.', 50),
(4, 4, 'Garden Refresh', 'Weeding and planting new flowers.', 37),
(4, 5, 'Dishwasher Setup', 'Quick and easy appliance installation.', 45),
(4, 6, 'Morning Babysitting', 'Great with infants and toddlers.', 30),

-- Freelancer 3
(5, 7, 'Weekend Dog Walking', 'Flexible dog walking on weekends.', 22),
(5, 8, 'Wall Art Setup', 'Hang art and mirrors professionally.', 33),
(5, 1, 'Full Home Cleanup', 'End-to-end home cleaning services.', 48),
(5, 2, 'Laundry Folding Service', 'Wash, dry, and fold for you.', 27),
(5, 3, 'Furniture Assembly', 'IKEA, Wayfair, you name it.', 50),
(5, 4, 'Bush Trimming', 'Keep shrubs neat and tidy.', 34),
(5, 5, 'Microwave Mounting', 'Wall-mount your microwave securely.', 40),

-- Freelancer 4
(6, 6, 'Afternoon Babysitting', 'Available weekday afternoons.', 26),
(6, 7, 'Neighborhood Dog Walk', 'Friendly and safe walks around town.', 18),
(6, 8, 'Room Makeover', 'Help with color schemes and layouts.', 39),
(6, 1, 'Quick Clean Session', '1-hour express cleaning.', 32),
(6, 2, 'Same-day Laundry', 'Fast, reliable same-day service.', 36),
(6, 3, 'Fix Door Hinges', 'Squeaky doors? I fix them.', 42),
(6, 4, 'Garden Cleanup', 'Rake leaves, remove debris.', 29),

-- Freelancer 5
(7, 5, 'TV Wall Mount', 'Secure TV installation.', 48),
(7, 6, 'Evening Child Care', 'Play, feed, and entertain kids.', 30),
(7, 7, 'Run & Play Dog Time', 'High-energy dog walking.', 24),
(7, 8, 'Small Room Design', 'Help redesign small spaces.', 35),
(7, 1, 'Dust & Mop Service', 'Floors and surfaces cleaned.', 39),
(7, 2, 'Wash & Iron Clothes', 'Laundry with ironing included.', 33),
(7, 3, 'Minor Repairs', 'Small electrical and hardware fixes.', 46),

-- Freelancer 6
(8, 4, 'Hedge Cutting', 'Shape hedges and small trees.', 38),
(8, 5, 'Oven Setup', 'Fast oven installation.', 44),
(8, 6, 'Toddler Sitting', 'Gentle and safe care.', 29),
(8, 7, 'Park Walk for Dogs', 'Fun park time for dogs.', 23),
(8, 8, 'Bedroom Styling', 'Redecorate and style your room.', 37),
(8, 1, 'Deep Floor Cleaning', 'Tile and hardwood cleaning.', 41),
(8, 2, 'Clothing Refresh', 'Wash and scent refresh.', 25),

-- Freelancer 7
(9, 3, 'Wall Patch & Paint', 'Small wall repairs and touch-up.', 45),
(9, 4, 'Leaf Raking', 'Autumn cleanup for lawns.', 28),
(9, 5, 'Washer Setup', 'Connect your new washer.', 46),
(9, 6, 'Emergency Babysitting', 'Available on short notice.', 34),
(9, 7, 'Group Dog Walks', 'Social walks for dogs.', 21),
(9, 8, 'Living Room Redesign', 'New look for your lounge.', 43),
(9, 1, 'Eco Cleaning', 'Use of eco-friendly products.', 40),

-- Freelancer 8
(10, 2, 'Iron-Only Service', 'Pressed and folded laundry.', 26),
(10, 3, 'Toolbox Help', 'General handyman help.', 50),
(10, 4, 'Backyard Weed Pulling', 'Manual weeding service.', 33),
(10, 5, 'Fridge Installation', 'Proper fridge placement & plug-in.', 44),
(10, 6, 'Weekend Sitting', 'Saturday & Sunday available.', 31),
(10, 7, 'Long Walks', '1-hour dog walks.', 27),
(10, 8, 'Hallway Decor', 'Entrance and hallway makeover.', 36),

-- Freelancer 9
(11, 1, 'Spring Cleaning', 'Seasonal home cleaning.', 47),
(11, 2, 'Laundry & Sort', 'Clean and organize clothes.', 29),
(11, 3, 'Mount Shelves', 'Drill and mount shelves.', 50),
(11, 4, 'Flower Planting', 'Add beauty to your garden.', 35),
(11, 5, 'Air Conditioner Setup', 'Window and wall units.', 50),
(11, 6, 'After School Babysitting', 'Care after school hours.', 28),
(11, 7, 'Morning Dog Walk', 'Before-work dog walking.', 20),

-- Freelancer 10
(12, 8, 'Kitchen Decor', 'Modernize your kitchen space.', 42),
(12, 1, 'Move-in Cleaning', 'Prepare your new home.', 45),
(12, 2, 'Bedding Laundry', 'Wash duvets, pillows.', 30),
(12, 3, 'Hang Curtains', 'Install rods and curtains.', 39),
(12, 4, 'Mulch Spreading', 'Add mulch to garden beds.', 34),
(12, 5, 'Microwave Install', 'Quick install service.', 38),
(12, 6, 'Overnight Babysitting', 'Reliable care overnight.', 50);

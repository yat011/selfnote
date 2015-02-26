
CREATE TABLE notes (
	id INTEGER PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	excerpt TEXT, 
	content TEXT NOT NULL,
	users_id INTEGER UNSIGNED,
	user_like INTEGER UNSINGED DEFAULT 0,
	user_dislike INTEGER UNSINGED DEFAULT 0,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (users_id) REFERENCES users(id)
);

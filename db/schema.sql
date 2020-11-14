DROP TABLE IF EXISTS searchDestinations;

CREATE TABLE searchDestinations(
  destinationID INTEGER PRIMARY KEY,
  name TEXT,
  description TEXT,
  mainURL TEXT,
  searchURL TEXT,
  tags TEXT
);

package main

import (
	"fmt"

	"github.com/gocql/gocql"

	"github.com/gookit/color"
)

func main() {
	// Define the Cassandra cluster configuration
	cluster := gocql.NewCluster("127.0.0.1:9042") // Replace with your Cassandra host
	// cluster.Keyspace = "your-keyspace"            // Replace with your desired keyspace
	session, err := cluster.CreateSession()

	// function to render the text in red
	red := color.FgRed.Render

	if err != nil {
		panic(red(err))

	}
	defer session.Close()

	// Create a keyspace
	if err := createKeyspace(session); err != nil {
		panic(red(err))
		// color.Redp("Simple to use color")
	}

	// Create a table
	if err := createTable(session); err != nil {
		panic(red(err))
	}

	// Truncate the table
	if err := truncateTable(session); err != nil {
		panic(red(err))
	}

	// Insert data (x3)
	if err := insertData(session); err != nil {
		panic(red(err))
	}

	// Select and display data
	selectAndDisplayData(session)
}

func createKeyspace(session *gocql.Session) error {
	query := `CREATE KEYSPACE IF NOT EXISTS mb_demo_go
              WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 1}`
	return session.Query(query).Exec()
}

func createTable(session *gocql.Session) error {
	query := `CREATE TABLE IF NOT EXISTS mb_demo_go.mb_demo_table (
              id UUID PRIMARY KEY,
              name TEXT,
              age INT)`
	return session.Query(query).Exec()
}

func truncateTable(session *gocql.Session) error {
	query := `TRUNCATE mb_demo_go.mb_demo_table`
	return session.Query(query).Exec()
}

func insertData(session *gocql.Session) error {
	// Insert data three times
	for i := 1; i <= 3; i++ {
		id := gocql.TimeUUID()
		query := `INSERT INTO mb_demo_go.mb_demo_table (id, name, age) VALUES (?, ?, ?)`
		if err := session.Query(query, id, fmt.Sprintf("Name%d", i), i*10).Exec(); err != nil {
			return err
		}
	}
	return nil
}

func selectAndDisplayData(session *gocql.Session) {
	var id gocql.UUID
	var name string
	var age int

	query := `SELECT id, name, age FROM mb_demo_go.mb_demo_table`
	iter := session.Query(query).Iter()
	for iter.Scan(&id, &name, &age) {
		color.Printf("<comment>ID:</> <cyan>%s</>, <comment>Name:</> <cyan>%s</>, <comment>Age:</> <cyan>%d</>\n", id, name, age)
	}
	if err := iter.Close(); err != nil {
		fmt.Println("Error while fetching data:", err)
	}
}

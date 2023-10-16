const http = require('node:http');
const cassandra = require('cassandra-driver');


const hostname = '127.0.0.1';
const port = 5000;

//connect to a cassandra cluster
const client = new cassandra.Client({
    contactPoints: ['localhost'],
    localDataCenter: 'datacenter1'
});

//create a keyspace and a table 
const query_create_keyspace = "CREATE KEYSPACE IF NOT EXISTS xx_demo1 WITH replication = {'class': 'SimpleStrategy', 'replication_factor': '1' }";
const query_create_table = "CREATE TABLE IF NOT EXISTS xx_demo1.users (id int PRIMARY KEY, name text, email text)";

//truncate the table
const query_truncate_table = "TRUNCATE xx_demo1.users";

//3 insertions of data into the table
const query_insert1 = "INSERT INTO xx_demo1.users (id, name, email) VALUES (1, 'John', 'john.deere@gmail.com')";
const query_insert2 = "INSERT INTO xx_demo1.users (id, name, email) VALUES (2, 'Jane', 'jane.deere@gmail.com')";
const query_insert3 = "INSERT INTO xx_demo1.users (id, name, email) VALUES (3, 'Jack', 'jack.deere@gmail.com')";

//select all data from the table
const selectQuery = 'SELECT * FROM xx_demo1.users';

// Fonction pour exécuter une requête avec gestion d'erreur
async function executeQuery(query, successMessage) {
    try {
        await client.execute(query);
        console.log(successMessage);
    } catch (err) {
        console.error(`Error: ${err.message}`);
    }
}

async function main() {
    try {
        await client.connect();
        console.log('Connected to the cluster');

        await executeQuery(query_create_keyspace, 'Keyspace created');
        await executeQuery(query_create_table, 'Table created');
        await executeQuery(query_truncate_table, 'Table truncated');
        await executeQuery(query_insert1, 'Data inserted');
        await executeQuery(query_insert2, 'Data inserted');
        await executeQuery(query_insert3, 'Data inserted');
        const result = await client.execute(selectQuery);
        console.log('Selected Data:', result.rows);
    } catch (err) {
        console.error(`Error: ${err.message}`);
    } finally {
        client.shutdown();
    }
}

main();

const server = http.createServer((req, res) => {
    res.statusCode = 200;
    res.setHeader('Content-Type', 'text/plain');
    res.end("test");
});

server.listen(port, hostname, () => {
    console.log(`Server running at http://${hostname}:${port}/`);
}); 
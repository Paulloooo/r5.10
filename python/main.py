# Faut exécuter la commande pour télécharger le package : pip install cassandra-driver
# Pour exécuter en ligne de commande : python3 ./main.py

from cassandra.cluster import Cluster

# Connexion au cluster
cluster = Cluster(['localhost'])  # Remplacez 'localhost' par l'adresse de votre cluster
session = cluster.connect()

# Création d'un keyspace
session.execute("CREATE KEYSPACE IF NOT EXISTS XX_test_keyspace WITH replication = {'class': 'SimpleStrategy', 'replication_factor': 1}")

# Utilisation du keyspace
session.set_keyspace('XX_test_keyspace')

# Création d'une table
session.execute("""
    CREATE TABLE IF NOT EXISTS XX_test_table (
        id UUID PRIMARY KEY,
        name TEXT,
        age INT
    )
""")

# Tronquer la table
session.execute("TRUNCATE XX_test_table")

# Insertion de données
session.execute("INSERT INTO XX_test_table (id, name, age) VALUES (uuid(), 'John', 30)")
session.execute("INSERT INTO XX_test_table (id, name, age) VALUES (uuid(), 'Alice', 25)")
session.execute("INSERT INTO XX_test_table (id, name, age) VALUES (uuid(), 'Bob', 28)")

# Sélection de données
result = session.execute("SELECT * FROM XX_test_table")
for row in result:
    print(row)

# Fermer la session et la connexion au cluster
session.shutdown()
cluster.shutdown()
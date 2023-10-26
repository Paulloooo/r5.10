package ad_cassandra;

import java.io.File;
import java.net.InetSocketAddress;
import java.time.Duration;
import java.util.Arrays;

import com.datastax.oss.driver.api.core.CqlSession;
import com.datastax.oss.driver.api.core.config.DriverConfigLoader;
import com.datastax.oss.driver.api.core.cql.ResultSet;
import com.datastax.oss.driver.api.core.cql.SimpleStatement;
import com.datastax.oss.driver.api.core.cql.Statement;

public class App {

    public static void main(String[] args) {
        
        String keyspaceName = "ad_demo1";
        String tableName = "ad_javatable";

        
        try (CqlSession session = CqlSession.builder()
        		.addContactPoint(new InetSocketAddress("127.0.0.1", 9042))
        		.withLocalDatacenter("Mars")
                .build()) {

            
            session.execute(String.format("CREATE KEYSPACE IF NOT EXISTS %s WITH "
                    + "replication = {'class': 'SimpleStrategy', 'replication_factor': 1}", keyspaceName));

            
            session.execute(String.format("USE %s", keyspaceName));
            
            Statement<?> createTableStatement = SimpleStatement.builder(
                    String.format("CREATE TABLE IF NOT EXISTS %s (id UUID PRIMARY KEY, name TEXT, age INT, address MAP<TEXT,TEXT>)", tableName))
                    .setTimeout(Duration.ofSeconds(5))
                    .build();

            session.execute(createTableStatement);

            
            session.execute(String.format("TRUNCATE TABLE %s", tableName));

           
            session.execute(String.format("INSERT INTO %s (id, name, age, address) VALUES (uuid(), 'Albert', 39, {'rue':'Rue A'})", tableName));
            session.execute(String.format("INSERT INTO %s (id, name, age, address) VALUES (uuid(), 'Bernard', 37, {'rue':'Rue C'})", tableName));
            session.execute(String.format("INSERT INTO %s (id, name, age, address) VALUES (uuid(), 'Carles', 32, {'rue':'Rue B'})", tableName));

            
            ResultSet resultSet = session.execute(String.format("SELECT * FROM %s", tableName));
            resultSet.forEach(row -> {
                System.out.printf("ID: %s, Name: %s, Age: %d, Address: %s\n",
                        row.getUuid("id"), row.getString("name"), row.getInt("age"), row.getMap("address", String.class, String.class));
            });

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

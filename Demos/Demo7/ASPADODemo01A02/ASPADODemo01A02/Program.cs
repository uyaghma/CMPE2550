using Microsoft.Data.SqlClient;

namespace ASPADODemo01A02
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            var app = builder.Build();

            /*
             * Define a connection strint that includes information about your 
             * database server, DB name, authentication
             * */
            string connectionString = "Server=data.cnt.sast.ca,24680;" +
                                      "Database=demo_db2550_Northwind;" +
                                      "User Id=demoUser;" +
                                      "Password=temP2020#;" +
                                      "Encrypt=False";

            app.MapGet("/", () => "ASP ADO Demo 01");

            app.MapGet("/RetrieveData", () => {
                Console.WriteLine(" Inside Retrievedata handler");
                /* Use SqlConnetion Class to open a connetion to the DB
                 * Create and open the connection in a using block. This
                 * ensures that all reseources will be closed and disposed 
                 * when the code Exits
                 * 
                 */
                using (SqlConnection con = new SqlConnection(connectionString))
                {
                    // Open the connetion in try/ catch block

                    try
                    { 
                        con.Open();
                        Console.WriteLine("Connetion is open");

                        string query = "select * from Employees";

                        // You can use SqlCommand class to execute SQL queries

                                                    //          query  , connectionObject
                        using (SqlCommand command= new SqlCommand(query, con))
                        {
                            // Create SqlDataReader object to store your retrieved data

                            using ( SqlDataReader reader = command.ExecuteReader() )
                            {
                                while (reader.Read())
                                {
                                    // Access data using reader["ColumnName"] or reader.GetXXX() methods
                                    Console.WriteLine($"{reader["EmployeeID"]} |  {reader.GetString(1)} | |  {reader.GetString(2)}");
                                    // Return the data in JSON Format back to user
                                }
                            }

                        }

                    }
                    catch (SqlException ex)
                    {
                        Console.WriteLine(ex.Message);
                        // Return a JSON or string back to Client the way you did in ICA06 07
                    }
                }

            });

            app.Run();
        }
    }
}
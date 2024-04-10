using Microsoft.Data.SqlClient;
using System.Reflection.PortableExecutable;

namespace ica08
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            var app = builder.Build();
            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            string conData = "Server=data.cnt.sast.ca,24680;" +
                                      "Database=uyaghma1_ClassTrak;" +
                                      "User Id=uyaghma1;" +
                                      "Password=1Khamkalla;" +
                                      "Encrypt=False";

            app.MapGet("/", () => "Hello World!");

            app.MapGet("/retrieve", () =>
            {
                string data = "";
                using (SqlConnection con = new(conData))
                {
                    try
                    {
                        con.Open();
                        string query = "SELECT * FROM Students WHERE first_name like 'E%' OR first_name like 'F%' ORDER BY first_name FOR JSON AUTO";
                        
                        using SqlCommand cmd = new(query, con);
                        using SqlDataReader reader = cmd.ExecuteReader();
                        while (reader.Read()) { data = reader.GetString(0); }
                    }
                    catch (SqlException ex)
                    {
                        Console.WriteLine(ex.ToString());
                    }
                }
                return new
                {
                    data
                };
            });

            app.MapGet("/studentinfo", (string id) =>
            {
                string data = "";
                using (SqlConnection con = new(conData))
                {
                    try
                    {
                        con.Open();
                        string query = "SELECT c.class_id, c.class_desc, c.days, c.start_date, i.instructor_id, i.first_name, i.last_name FROM Students s " + 
                        "JOIN class_to_student cs ON s.student_id=cs.student_id " + 
                        "JOIN Classes c ON cs.class_id=c.class_id " + 
                        "JOIN Instructors i ON c.instructor_id=i.instructor_id " + 
                        $"WHERE s.student_id='{id}' FOR JSON PATH, INCLUDE_NULL_VALUES";

                        using SqlCommand cmd = new(query, con);
                        using SqlDataReader reader = cmd.ExecuteReader();
                        while (reader.Read()) { data = reader.GetString(0); }
                    }
                    catch (SqlException ex)
                    {
                        Console.WriteLine(ex.ToString());
                    }
                }
                return new
                {
                    data
                };
            });

            app.Run();
        }
    }
}

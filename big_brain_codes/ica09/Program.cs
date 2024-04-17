using Microsoft.Data.SqlClient;
using System.Reflection.PortableExecutable;
using System.Text.RegularExpressions;

namespace ica09
{
    public class Program
    {
        public static string CleanInputs(string input)
        {
            return Regex.Replace(input.Trim(), "<.*?>|&.*?;", string.Empty);
        }

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

            app.MapGet("/classget", () =>
            {
                string data = "";
                using (SqlConnection con = new(conData))
                {
                    try
                    {
                        con.Open();
                        string query = "SELECT * FROM Classes FOR JSON AUTO";

                        using SqlCommand cmd = new(query, con);
                        using SqlDataReader reader = cmd.ExecuteReader();
                        while (reader.Read()) { data += reader.GetString(0);}
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

            app.MapPut("/update", (string fname, string lname, int scid, int stid) =>
            {
                string status = "";
                using (SqlConnection con = new(conData))
                {
                    try
                    {
                        con.Open();
                        string query = $"UPDATE Students SET first_name='{CleanInputs(fname)}', last_name='{CleanInputs(lname)}', school_id={scid} WHERE student_id={stid}";
                        Console.WriteLine(query);

                        using (SqlCommand cmd = new(query, con))
                        {
                            int rows = cmd.ExecuteNonQuery();

                            if (rows < 0)
                                status = "Error updating";
                            else
                                status = "Updated successfully";
                        }
                    }
                    catch (SqlException ex)
                    {
                        status = "SQL error: " + ex.ToString();
                    }
                }
                return new
                {
                    status
                };
            });

            app.MapDelete("/delete", (int stid) =>
            {
                string status;
                using (SqlConnection con = new(conData))
                {
                    try
                    {
                        con.Open();
                        string query = $"DELETE FROM class_to_student WHERE student_id={stid}; DELETE FROM Results WHERE student_id={stid}; DELETE FROM Students WHERE student_id={stid};";

                        using (SqlCommand cmd = new(query, con))
                        {
                            int rows = cmd.ExecuteNonQuery();
                            if (rows < 0) status = "Error deleting record";
                            else status = "Record deleted";
                        }
                    }
                    catch (SqlException ex)
                    {
                        status = "SQL error: " + ex.ToString();            }
                }
                return new
                {
                    status
                };
            });

            app.MapPost("/add", (info sub) =>
            {
                string status = "";
                using (SqlConnection con = new SqlConnection(conData))
                {
                    try
                    {
                        con.Open();
                        string query = $"INSERT INTO Students (first_name, last_name, school_id) VALUES ('{sub.fname}', '{sub.lname}', {sub.scid}); SELECT SCOPE_IDENTITY();";

                        using (SqlCommand cmd = new SqlCommand(query, con))
                        {
                            object result = cmd.ExecuteScalar();

                            if (result != null)
                            {
                                int stid = Convert.ToInt32(result);

                                foreach (int classId in sub.cid)
                                {
                                    query = $"INSERT INTO class_to_student (class_id, student_id) VALUES ({classId}, {stid})";
                                    using (SqlCommand command = new SqlCommand(query, con))
                                    {
                                        int rows = command.ExecuteNonQuery();
                                        if (rows <= 0)
                                            status = "Failed to update successfully";
                                    }
                                }
                                status = "Added student successfully";
                            }
                            else
                            {
                                status = "Failed to insert student record";
                            }
                        }
                    }
                    catch (SqlException ex)
                    {
                        status = "SQL error: " + ex.ToString();
                    }
                }
                return new
                {
                    status
                };
            });

            app.Run();
        }
        record info(string fname, string lname, int scid, int[] cid);
    }

}

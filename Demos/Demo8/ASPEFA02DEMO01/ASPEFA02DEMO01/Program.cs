using ASPEFA02DEMO01.Models;

namespace ASPEFA02DEMO01
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            var app = builder.Build();

            app.MapGet("/", () => "Demo EF");

            app.MapGet("/RetData", () => {
                Console.WriteLine("Inside Ret Data part");
                using (var db = new DemoDb2550NorthwindContext())
                {
                    // Query style

                    /* var results = from p in db.Products
                                       //select p; // All columns
                                   select new { p.ProductId, p.ProductName };
                                     // Specific columns                    

                     return results.ToList();
                    */

                    // Method style 
                    var results = db.Products
                                   .Select(x => new { x.ProductId, x.ProductName });               
                    return results.ToList();

                }
            });

            // For inserting Category into DB
            app.MapPost("/InsertCategory", () =>
            {
                Console.WriteLine("Inside Insert Part");  
                // Add new data to DB

                // Create an object of the class/table
                Category c = new Category();

                c.CategoryName = "Test Category";
                c.Description = "Test Category for Demo";

                // Try to insert the record to DB
                try {
                    using (var db = new DemoDb2550NorthwindContext())
                    {
                        db.Categories.Add(c);  // Like firing Insert query
                        db.SaveChanges();  // Making the changes permanent

                        Console.WriteLine("Inserted Successfully");
                        return "Inserted Successfully";
                    }

                }
                catch (Exception ex) {
                    Console.WriteLine(ex.Message);
                    return "Error While performing Insert Operation";
                }


            });

            app.MapDelete("/DeleteCategory", () =>
            {
                Console.WriteLine("Inside Delete part");
                using (var db = new DemoDb2550NorthwindContext())
                {
                    int id = 44;  // Hard coding the value, you can replace value received from client side

                    try {
                        //Try to delete from DB here
                        if (db.Categories.Find(id) is Category e)
                        { // Inside here means a match has been found
                            db.Categories.Remove(e);
                            db.SaveChanges();
                            Console.WriteLine("Deleted ");
                            return "Category is deleted successfully";
                        }
                        else
                        {
                            return "Category not found in DB";
                        }
                    }
                    catch (Exception ex)
                    {
                        Console.WriteLine(ex.Message);
                        return "Error while performing Delete Operation";
                    }
                
                }

            });

            app.MapPut("/UpdateCategory", () =>
            {
                Console.WriteLine("Inside Update part");

                using (var db = new DemoDb2550NorthwindContext())
                {
                    try {
                        int id = 45; // Hard coded the value, use value from client side

                        // Retrive an instance of the record using Find()
                        if (db.Categories.Find(id) is Category e)
                        {
                            // Perform changes 
                            e.CategoryName = "Updated Name"; // Hard coded the value, use value from client side
                            e.Description = "Updated Description"; // Hard coded the value, use value from client side

                            // Save the changes to the DB
                            db.Categories.Update(e);
                            db.SaveChanges();
                            return "Updated Successfully";
                        }
                        else
                        {
                            return "Category not found";
                        }



                        return "Success";

                    }
                    catch (Exception ex)
                    {
                        Console.WriteLine(ex.Message);
                        return "Error While performing update part";
                    }
                }
            });
            app.Run();
        }
    }
}
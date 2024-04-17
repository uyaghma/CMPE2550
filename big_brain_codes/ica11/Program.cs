using ica10.RestaurantDB;

namespace ica11
{
    public class Program
    {
        public static void Main(string[] args)
        {
            var builder = WebApplication.CreateBuilder(args);
            builder.Services.AddControllers();
            var app = builder.Build();
            app.UseCors(x => x.AllowAnyMethod().AllowAnyHeader().SetIsOriginAllowed(origin => true));

            app.MapGet("/selections", () =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    var locations = db.Locations.Select(x => new
                    {
                        id = x.Locationid,
                        name = x.LocationName
                    }).ToList();

                    var items = db.Items.Select(x => new
                    {
                        name = x.ItemName,
                        price = x.ItemPrice,
                        id = x.Itemid
                    }).ToList();

                    var payments = db.Orders.Select(x => new
                    {
                        payment = x.PaymentMethod
                    }).Distinct().ToList();

                    return new { locations, items, payments };
                }
            });

            app.MapGet("/orders", (int id, int location) =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    var orders = db.Orders.Where(w => w.Locationid == location && w.Cid == id).Select(x => new
                    {
                        orderid = x.OrderId,
                        date = x.OrderDate,
                        payment = x.PaymentMethod,
                        item = x.Item.ItemName,
                        price = x.Item.ItemPrice,
                        count = x.ItemCount,
                        name = $"{x.CidNavigation.Fname} {x.CidNavigation.Lname}",
                        location = x.Location.LocationName,
                        cid = x.Cid
                    })
                    .ToList();
                    return orders;
                }
            });

            app.MapPost("/place", (int cid, int id, int count, string payment, int location) =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    if (db.Customers.Find(cid) is Customer cust)
                    {
                        db.Orders.Add(new Order { Cid = cid, Itemid = id, ItemCount = count, PaymentMethod = payment, Locationid = location });
                        db.SaveChanges();
                    }
                    return Results.NotFound("Not found");
                }
            });

            app.MapDelete("/delete/{id}/{cid}", (int id, int cid) =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    if (db.Orders.Find(id) is Order ord)
                    {
                        db.Orders.Remove(ord);
                        db.SaveChanges();
                    }

                    var orders = db.Orders.Where(w => w.Cid == cid && w.OrderId == id).Select(x => new
                    {
                        orderid = x.OrderId,
                        date = x.OrderDate,
                        payment = x.PaymentMethod,
                        item = x.Item.ItemName,
                        price = x.Item.ItemPrice,
                        count = x.ItemCount,
                        name = $"{x.CidNavigation.Fname} {x.CidNavigation.Lname}",
                        location = x.Location.LocationName
                    })
                    .ToList();
                    return orders;
                }
            });

            app.MapPut("/update", (int oid, int id, int item, int count, string payment, int location) =>
            {
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    if (db.Orders.Find(oid) is Order ord)
                    {
                        ord.PaymentMethod = payment;
                        ord.ItemCount = count;
                        ord.Itemid = item;

                        db.Orders.Update(ord);
                        db.SaveChanges();
                    }
                }
            });

            app.Run();
        }
    }
}

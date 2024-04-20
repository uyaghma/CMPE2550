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
                        locationid = x.Locationid,
                        cid = x.Cid
                    })
                    ?.ToList();
                    return orders;
                }
            });

            app.MapPost("/place", (int id, int item, int count, string payment, int location) =>
            {
                var message = "";
                var error = "";
                Random rnd = new Random();
                int oid = 0;
                DateTime? datetime = null;
                var locName = "";
                var itemName = "";
                double? itemPrice = null;
                string date = "";
                string time = "";

                using (var db = new Uyaghma1RestaurantsContext())
                {
                    if (db.Customers.Find(id) is Customer cust)
                    {
                        var newOrder = new Order { Cid = id, Itemid = item, ItemCount = count, PaymentMethod = payment, Locationid = location };
                        db.Orders.Add(newOrder);
                        db.SaveChanges();
                        oid = newOrder.OrderId;
                        datetime = db.Orders.Find(location, oid)?.OrderDate;

                        if (datetime != null)
                        {
                            date = datetime.Value.ToString("dddd, dd MMMM yyyy");
                            time = datetime.Value.ToString("h:mm tt");
                        }

                        locName = db.Locations.Where(x => x.Locationid == location).Select(x => x.LocationName).ToList()[0];
                        var items = db.Items.SingleOrDefault(x => x.Itemid == item);
                        itemName = items?.ItemName;
                        itemPrice = items?.ItemPrice;

                        message = $"Thank you, {cust.Fname}! <br>Your order will be ready in {rnd.Next(5, 36)} minutes. <br>";
                    }
                    else
                        error = "This customer does not exist!";
                }
                return new
                {
                    error,
                    date,
                    time,
                    message,
                    id,
                    count,
                    payment,
                    location,
                    oid,
                    locName,
                    itemName,
                    itemPrice
                };
            });

            app.MapDelete("/delete/{id}/{cid}/{loc}", (int id, int cid, int loc) =>
            {
                var message = "";
                var error = "";
                using (var db = new Uyaghma1RestaurantsContext())
                {
                    if (db.Orders.Find(loc, id) is Order ord)
                    {
                        db.Orders.Remove(ord);
                        db.SaveChanges();
                        message = "Order deleted successfully!";
                    }
                    else
                        error = "Order not deleted!";
                }
                return new
                {
                    error,
                    message,
                    cid,
                    loc
                };
            });

            app.MapPut("/update", (int oid, int id, int item, int count, string payment, int location) =>
            {
                var message = "";
                var error = "";
                DateTime? datetime = null;
                var locName = "";
                var itemName = "";
                double? itemPrice = null;
                string date = "";
                string time = "";

                using (var db = new Uyaghma1RestaurantsContext())
                {
                    if (db.Orders.Find(location, oid) is Order ord)
                    {
                        ord.PaymentMethod = payment;
                        ord.ItemCount = count;
                        ord.Itemid = item;

                        db.Orders.Update(ord);
                        db.SaveChanges();

                        datetime = db.Orders.Find(location, oid)?.OrderDate;
                        if (datetime != null)
                        {
                            date = datetime.Value.ToString("dddd, dd MMMM yyyy");
                            time = datetime.Value.ToString("h:mm tt");
                        }
                        locName = db.Locations.Where(x => x.Locationid == location).Select(x => x.LocationName).ToList()[0];
                        var items = db.Items.SingleOrDefault(x => x.Itemid == item);
                        itemName = items?.ItemName;
                        itemPrice = items?.ItemPrice;

                        message = $"Order #{oid} was updated successfully, changes can no longer be made.";
                    }
                    else
                        error = "Order was not updated successfully! Try again!";
                }
                return new
                {
                    error,
                    message,
                    item,
                    count,
                    payment,
                    date,
                    time,
                    locName,
                    itemName,
                    itemPrice,
                    oid
                };
            });

            app.Run();
        }
    }
}
